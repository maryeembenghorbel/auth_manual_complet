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

        // Filtre par niveau de risque
        if ($request->filled('risk_level')) {
            if ($request->risk_level === 'critical') {
                $query->whereHas('equipment', function($q) {
                    $q->where('status', 'needs_review');
                });
            } else {
                $query->whereHas('equipment', function($q) use ($request) {
                    $q->where('risk_level', $request->risk_level);
                });
            }
        }

        $scans = $query->orderBy('ended_at', 'desc')->paginate(20);

        return view('analyst.reports', compact('scans'));
    }

    //scan PHP natif par ports (fsockopen)
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

        //  Scan PHP natif 
        foreach ($ports as $port) {
            $connection = @fsockopen($equipment->ip_address, $port, $errno, $errstr, 3);
            
            if ($connection) {
                $openPorts[] = $port;
                fclose($connection);
            } else {
                $closedPorts[] = $port;
            }
        }

        // Exécution Nmap automatique
        $nmapPath = '"C:\Program Files (x86)\Nmap\nmap.exe"';
        $command = $nmapPath . 
            ' -Pn -sV --script vulners --script-args mincvss=4.0 ' . 
            escapeshellarg($equipment->ip_address);
        
        $nmapOutput = shell_exec($command . ' 2>&1');

        //  Nettoyage automatique de la sortie Nmap
        
        $cleanedNmapOutput = $this->cleanNmapOutput($nmapOutput ?? '');

        //  Extraction automatique des CVE
        $cves = $this->extractCves($nmapOutput ?? '');

        // Calcul du risque critique et marquage automatique
        $criticalCves = array_filter($cves, function($cve) {
            return $cve['score'] >= 7.0; // CVE critiques (CVSS ≥ 7.0)
        });

        $criticalScoreCumul = array_sum(array_column($criticalCves, 'score'));
        $criticalCount = count($criticalCves);

        // Conditions pour marquer "À revoir"
        $needsReview = ($criticalScoreCumul >= 20) || ($criticalCount >= 3);

        if ($needsReview) {
            $equipment->update([
                'status' => 'needs_review',
                'risk_level' => 'high',
                'critical_cve_count' => $criticalCount,
                'critical_score_cumul' => round($criticalScoreCumul, 2),
                'last_critical_scan_at' => now(),
            ]);

            Log::warning('⚠️ Équipement marqué CRITIQUE', [
                'equipment_id' => $equipment->id,
                'equipment_name' => $equipment->name,
                'critical_cves' => $criticalCount,
                'cumul_score' => round($criticalScoreCumul, 2),
            ]);
        } else {
            $equipment->update([
                'status' => 'reviewed',
                'risk_level' => count($cves) > 0 ? 'medium' : 'low',
                'critical_cve_count' => $criticalCount,
                'critical_score_cumul' => round($criticalScoreCumul, 2),
            ]);
        }

        //  Génération du rapport 
        $result = $this->generateReport($equipment, $scan, $openPorts, $closedPorts, $ports, $cleanedNmapOutput, $cves, $criticalScoreCumul, $needsReview);

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
            'vulnerabilities' => count($cves),
            'needs_review' => $needsReview,
            'file_saved' => $absolutePath,
        ]);

        // Message de succès 
        $successMessage = "✅ Scan terminé : " . count($openPorts) . " port(s) ouvert(s) détecté(s)\n" .
            "🔴 Vulnérabilités détectées : " . count($cves) . "\n";
        
        if ($needsReview) {
            $successMessage .= "⚠️ ALERTE : Équipement marqué 'À REVOIR'\n" .
                "Score critique cumulé : " . round($criticalScoreCumul, 2) . " | CVE critiques : {$criticalCount}\n";
        }
        
        $successMessage .= "📁 Rapport sauvegardé : " . basename($filename);

        return back()->with('success', $successMessage);
    }
   //Fonction de nettoyage Nmap
    private function cleanNmapOutput(string $output): string
    {
        // Supprimer fingerprint, HTML et messages inutiles
        $patterns = [
            '/fingerprint-strings:.*?(?=\n\d+\/tcp|\Z)/si',
            '/<!DOCTYPE html>.*$/si',
            '/NEXT SERVICE FINGERPRINT.*$/si',
            '/=+NEXT SERVICE FINGERPRINT.*?(?=\n=+|\Z)/s',
            '/Starting Nmap.*\n/',
            '/Nmap scan report.*\n/',
            '/Host is up.*\n/',
            '/Not shown:.*\n/',
            '/Service detection performed.*\n/',
            '/Nmap done:.*\n/',
        ];

        $cleaned = preg_replace($patterns, '', $output);
        return trim($cleaned);
    }

    // Extraction automatique des CVE
    private function extractCves(string $output): array
    {
        preg_match_all(
            '/(CVE-\d{4}-\d+)\s+([\d.]+)/',
            $output,
            $matches,
            PREG_SET_ORDER
        );

        $cves = [];
        foreach ($matches as $match) {
            $cves[] = [
                'id' => $match[1],
                'score' => (float) $match[2],
                'severity' => $this->cvssToSeverity((float) $match[2]),
            ];
        }

        // Trier par score décroissant
        usort($cves, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return $cves;
    }

    // Conversion CVSS → Gravité
    private function cvssToSeverity(float $score): string
    {
        if ($score >= 9.0) return 'CRITIQUE';
        if ($score >= 7.0) return 'ELEVE';
        if ($score >= 4.0) return 'MOYEN';
        return 'FAIBLE';
    }

    //  Génération du rapport 
    private function generateReport($equipment, $scan, $openPorts, $closedPorts, $ports, $cleanedNmapOutput, $cves, $criticalScoreCumul, $needsReview)
    {
        $riskLevel = $needsReview ? 'CRITIQUE' : (count($cves) > 0 ? 'ELEVE' : (count($openPorts) > 3 ? 'MOYEN' : 'FAIBLE'));

        $report = "================================================================\n";
        $report .= "         RAPPORT DE SCAN DE SECURITE - SIAM                     \n";
        $report .= "================================================================\n\n";
        
        //  EXECUTIVE SUMMARY 
        $report .= "[EXECUTIVE SUMMARY]\n";
        $report .= "----------------------------------------------------------------\n";
        $report .= "Niveau de risque global : {$riskLevel}\n";
        
        if ($needsReview) {
            $report .= "*** EQUIPEMENT MARQUE 'A REVOIR' ***\n";
            $report .= "Score critique cumule   : " . round($criticalScoreCumul, 2) . "\n";
        }
        
        $report .= "Ports ouverts detectes  : " . count($openPorts) . "\n";
        $report .= "Vulnerabilites detectees: " . count($cves) . "\n";
        $report .= "Date du scan            : " . now()->format('d/m/Y à H:i:s') . "\n\n";

        $report .= "[INFO] INFORMATIONS GENERALES\n";
        $report .= "----------------------------------------------------------------\n";
        $report .= "Equipement       : " . $equipment->name . "\n";
        $report .= "Type             : " . $equipment->type . "\n";
        $report .= "Adresse IP       : " . $equipment->ip_address . "\n";
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
        
        // Section Vulnérabilités 
        $report .= "[VULNERABILITES DETECTEES]\n";
        $report .= "----------------------------------------------------------------\n";

        if (empty($cves)) {
            $report .= "  [OK] Aucune vulnerabilite critique detectee.\n";
        } else {
            foreach ($cves as $cve) {
                $severityIcon = $this->getSeverityIcon($cve['severity']);
                $report .= sprintf(
                    "  %s %s | Score: %.1f | Gravite: %s\n",
                    $severityIcon,
                    $cve['id'],
                    $cve['score'],
                    $cve['severity']
                );
            }
        }

        // Sortie Nmap nettoyée (sans bruit)
        $report .= "\n[NMAP SCAN - SERVICES & DETAILS TECHNIQUES]\n";
        $report .= "----------------------------------------------------------------\n";
        if (!empty($cleanedNmapOutput)) {
            $report .= $cleanedNmapOutput . "\n";
        } else {
            $report .= "Aucun resultat Nmap disponible.\n";
        }

        $report .= "\n[ANALYSIS] ANALYSE DE SECURITE\n";
        $report .= "----------------------------------------------------------------\n";
        
        if (count($cves) > 0) {
            $report .= "  [!!] ATTENTION : Vulnerabilites critiques detectees\n";
            $report .= "  [!!] Action requise : Appliquer les correctifs immediatement\n";
        } elseif (count($openPorts) === 0) {
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

    private function getSeverityIcon(string $severity): string
    {
        return match($severity) {
            'CRITIQUE' => '[!!!]',
            'ELEVE' => '[!!]',
            'MOYEN' => '[!]',
            default => '[i]',
        };
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