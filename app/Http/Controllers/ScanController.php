<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Scan;
use Illuminate\Support\Facades\Log;

class ScanController extends Controller
{
    /**
     * Dashboard principal - Statistiques
     */
    public function index()
    {
        $equipments = Equipment::with('scans')->get();
        return view('analyst.dashboard', compact('equipments'));
    }

    /**
     * Page Scans - Liste des équipements à scanner
     */
    public function scansIndex()
    {
        $equipments = Equipment::with('scans')->get();
        return view('analyst.scans', compact('equipments'));
    }

    /**
     * Page Rapports - Liste de tous les rapports
     */
    public function reportsIndex(Request $request)
    {
        $query = Scan::with('equipment')->whereNotNull('file_path');

        // Filtrer par équipement
        if ($request->filled('equipment_id')) {
            $query->where('equipment_id', $request->equipment_id);
        }

        // Filtrer par status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtrer par date
        if ($request->filled('date')) {
            $query->whereDate('ended_at', $request->date);
        }

        $scans = $query->orderBy('ended_at', 'desc')->paginate(20);

        return view('analyst.reports', compact('scans'));
    }

    /**
     * Lancer un scan de ports sur un équipement
     */
    public function launch(Equipment $equipment)
    {
        // Validation de l'adresse IP
        if (!filter_var($equipment->ip_address, FILTER_VALIDATE_IP)) {
            return back()->withErrors('Adresse IP invalide : ' . $equipment->ip_address);
        }

        // Création du scan en base de données
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

        // Scan des ports courants
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

        // Construction du rapport détaillé
        $result = $this->generateReport($equipment, $scan, $openPorts, $closedPorts, $ports);

        // Création du répertoire de sauvegarde si nécessaire
        $scanDirectory = storage_path('app/scans');
        if (!file_exists($scanDirectory)) {
            mkdir($scanDirectory, 0755, true);
        }

        // Génération du nom de fichier
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "scan_{$scan->id}_{$equipment->name}_{$timestamp}.txt";
        $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $filename);

        // Chemin RELATIF (bonne pratique Laravel)
        $relativePath = 'scans/' . $filename;
        $absolutePath = storage_path('app/' . $relativePath);

        // Écriture du fichier
        file_put_contents($absolutePath, $result, LOCK_EX);

        // Mise à jour du scan
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

    /**
     * Générer le rapport de scan formaté
     */
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

    /**
     * Obtenir le nom du service associé à un port
     */
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

    /**
     * Alias pour lancer un scan
     */
    public function scanEquipment(Equipment $equipment)
    {
        return $this->launch($equipment);
    }

    /**
     * Télécharger le rapport d'un scan
     */
    public function downloadScan(Scan $scan)
    {
        // Chemin absolu sur le disque
        $absolutePath = storage_path('app/' . $scan->file_path);

        // Vérifier que le fichier existe
        if (!$scan->file_path || !file_exists($absolutePath)) {
            return back()->withErrors('❌ Fichier de scan introuvable : ' . basename($scan->file_path ?? 'unknown'));
        }

        // Télécharger le fichier
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