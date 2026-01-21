<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Scan;
use Illuminate\Support\Facades\Log;

class ScanController extends Controller
{
 
    public function index()
    {
        $equipments = Equipment::with('scans')->get();
        return view('analyst.dashboard', compact('equipments'));
    }

   
    public function scansIndex()
    {
        $equipments = Equipment::with('scans')->get();
        return view('analyst.scans', compact('equipments'));
    }

  
    public function reportsIndex(Request $request)
    {
        $query = Scan::with('equipment')->whereNotNull('file_path');

        if ($request->filled('equipment_id')) {
            $query->where('equipment_id', $request->equipment_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('ended_at', $request->date);
        }

        $scans = $query->orderBy('ended_at', 'desc')->paginate(20);

        return view('analyst.reports', compact('scans'));
    }

    
    public function launch(Equipment $equipment)
    {
        set_time_limit(300); 

        $ip = trim((string)($equipment->ip_address ?? ''));
        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            return back()->withErrors('Adresse IP invalide : ' . ($equipment->ip_address ?? 'N/A'));
        }

        $scan = Scan::create([
            'equipment_id' => $equipment->id,
            'scan_type'    => 'port-scan',
            'started_at'   => now(),
            'status'       => 'running',
        ]);

        Log::info('Démarrage scan PHP natif', [
            'scan_id' => $scan->id,
            'ip' => $equipment->ip_address,
            'equipment' => $equipment->name,
        ]);

        $ports = [21, 22, 23, 25, 53, 80, 110, 143, 443, 445, 3306, 3389, 5432, 8080, 8443];
        $openPorts = [];
        $closedPorts = [];

        foreach ($ports as $port) {
            $connection = @fsockopen($equipment->ip_address, $port, $errno, $errstr, 3);
            
            if ($connection) {
                $openPorts[] = $port;
                fclose($connection);
            } else {
                $closedPorts[] = $port;
            }
        }

        $result = $this->generateReport($equipment, $scan, $openPorts, $closedPorts, $ports);

        $scanDirectory = storage_path('app/scans');
        if (!file_exists($scanDirectory)) {
            mkdir($scanDirectory, 0755, true);
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "scan_{$scan->id}_{$equipment->name}_{$timestamp}.txt";
        $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $filename);

        $relativePath = 'scans/' . $filename;
        $absolutePath = storage_path('app/' . $relativePath);

        file_put_contents($absolutePath, $result, LOCK_EX);

        $scan->update([
            'ended_at' => now(),
            'status' => 'completed',
            'result' => $result,
            'file_path' => $relativePath,
        ]);

        Log::info('Scan terminé', [
            'scan_id' => $scan->id,
            'open_ports' => count($openPorts),
            'file_saved' => $absolutePath,
        ]);

        return back()->with('success', 
            "✅ Scan terminé : " . count($openPorts) . " port(s) ouvert(s) détecté(s)<br>" .
            "📁 Rapport sauvegardé : <code>" . basename($filename) . "</code>"
        );
    }

   
    private function generateReport($equipment, $scan, $openPorts, $closedPorts, $ports)
    {
        $report = "================================================================\n";
        $report .= "         RAPPORT DE SCAN DE SECURITE - SIAM                     \n";
        $report .= "================================================================\n\n";
        
        $report .= "[INFO] INFORMATIONS GENERALES\n";
        $report .= "----------------------------------------------------------------\n";
        $report .= "Equipement       : " . $equipment->name . "\n";
        $report .= "Type             : " . $equipment->type . "\n";
        $report .= "Adresse IP       : " . $equipment->ip_address . "\n";
        $report .= "Date du scan     : " . now()->format('d/m/Y à H:i:s') . "\n";
        $report .= "ID du scan       : #" . $scan->id . "\n";
        $report .= "Ports scannes    : " . count($ports) . "\n\n";
        
        $report .= "[OPEN] PORTS OUVERTS (" . count($openPorts) . ")\n";
        $report .= "----------------------------------------------------------------\n";
        
        if (!empty($openPorts)) {
            foreach ($openPorts as $port) {
                $service = $this->getServiceName($port);
                $report .= sprintf("  [+] Port %5d/tcp  |  OUVERT  |  %-20s\n", $port, $service);
            }
        } else {
            $report .= "  [+] Aucun port ouvert detecte - Bonne securite !\n";
        }
        
        $report .= "\n[CLOSED] PORTS FERMES/FILTRES (" . count($closedPorts) . ")\n";
        $report .= "----------------------------------------------------------------\n";
        $report .= "  " . implode(', ', $closedPorts) . "\n\n";
        
        $report .= "[ANALYSIS] ANALYSE DE SECURITE\n";
        $report .= "----------------------------------------------------------------\n";
        
        if (count($openPorts) === 0) {
            $report .= "  [OK] Niveau de securite : BON\n";
            $report .= "  [OK] Aucun port vulnerable expose\n";
        } elseif (count($openPorts) <= 3) {
            $report .= "  [!] Niveau de securite : MOYEN\n";
            $report .= "  [!] " . count($openPorts) . " port(s) expose(s) - Verifier leur necessite\n";
        } else {
            $report .= "  [!!] Niveau de securite : ATTENTION\n";
            $report .= "  [!!] " . count($openPorts) . " ports exposes - Audit recommande\n";
        }
        
        $report .= "\n" . str_repeat("=", 64) . "\n";
        $report .= "Rapport genere par SIAM - Systeme d'Information et d'Alerte de Menaces\n";
        $report .= str_repeat("=", 64) . "\n";

        return $report;
    }

    
    private function getServiceName($port)
    {
        $services = [
            21 => 'FTP',
            22 => 'SSH',
            23 => 'Telnet',
            25 => 'SMTP',
            53 => 'DNS',
            80 => 'HTTP',
            110 => 'POP3',
            143 => 'IMAP',
            443 => 'HTTPS',
            445 => 'SMB',
            3306 => 'MySQL',
            3389 => 'RDP (Remote Desktop)',
            5432 => 'PostgreSQL',
            8080 => 'HTTP Proxy',
            8443 => 'HTTPS Alt',
        ];

        return $services[$port] ?? 'Service inconnu';
    }

    
    public function scanEquipment(Equipment $equipment)
    {
        return $this->launch($equipment);
    }

    
    public function downloadScan(Scan $scan)
    {
        $absolutePath = storage_path('app/' . $scan->file_path);

        if (!$scan->file_path || !file_exists($absolutePath)) {
            return back()->withErrors('❌ Fichier de scan introuvable : ' . basename($scan->file_path ?? 'unknown'));
        }

        return response()->download(
            $absolutePath,
            basename($absolutePath),
            [
                'Content-Type' => 'text/plain; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . basename($absolutePath) . '"'
            ]
        );
    }
}