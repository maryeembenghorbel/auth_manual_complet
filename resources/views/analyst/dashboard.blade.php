@extends('layouts.app')

@section('title', 'Dashboard Analyste')

@push('styles')
<style>
    :root {
      /*  --sidebar-width: 250px;
        --sidebar-bg: #1e293b;
        --sidebar-hover: #334155;
        --navbar-height: 60px;*/
    }

    body {
        background-color: #f8fafc;
    }

    /* Navbar principale */
    nav.navbar {
        padding-left: var(--sidebar-width) !important;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
      /*  background: #1e293b !important;*/
    }

   /* /* Sidebar 
    .analyst-sidebar {
        position: fixed;
        top: var(--navbar-height);
        left: 0;
        height: calc(100vh - var(--navbar-height));
        width: var(--sidebar-width);
        background: var(--sidebar-bg);
        color: white;
        padding-top: 20px;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        z-index: 999;
        transition: all 0.3s;
        background: #1e293b !important;

    }*/
/*
    .analyst-sidebar .sidebar-menu {
        list-style: none;
        padding: 20px 0;
        margin: 0;
    }

    .analyst-sidebar .sidebar-menu li {
        margin: 5px 0;
    }

    .analyst-sidebar .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 25px;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.2s;
        border-left: 3px solid transparent;
        font-size: 0.95rem;
    }

    .analyst-sidebar .sidebar-menu a:hover {
        background: var(--sidebar-hover);
        color: white;
        border-left-color: #3b82f6;
    }

    .analyst-sidebar .sidebar-menu a.active {
        background: var(--sidebar-hover);
        color: white;
        border-left-color: #3b82f6;
        font-weight: 600;
    }

    .analyst-sidebar .sidebar-menu a i {
        font-size: 1.3rem;
        width: 24px;
    }
*/
    .analyst-content {
        margin-left: 100px;  
        margin-top: 20px;     
        padding: 20px;        
        min-height: calc(100vh - 50px);
    }

    .page-header {
        background: white;
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .page-header h2 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        /*color: #1e293b;*/
    }

    @media (max-width: 768px) {
        nav.navbar {
            padding-left: 15px !important;
        }
/*
        .analyst-sidebar {
            transform: translateX(-100%);
        }
        
        .analyst-sidebar.show {
            transform: translateX(0);
        }
 */       
        .analyst-content {
            margin-left: 0;
        }
    }
</style>
@endpush
@section('content')
<!--
<div class="analyst-sidebar">
    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('analyst.dashboard') }}" class="{{ request()->routeIs('analyst.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('analyst.scans') }}" class="{{ request()->routeIs('analyst.scans') ? 'active' : '' }}">
                <i class="bi bi-search"></i>
                <span>Scans</span>
            </a>
        </li>
        <li>
            <a href="{{ route('analyst.reports') }}" class="{{ request()->routeIs('analyst.reports') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i>
                <span>Rapports</span>
            </a>
        </li>
    </ul>
</div>
-->
<!-- Main Content -->
<div class="analyst-content">

    <div class="page-header">
        <h2>🛡️ Dashboard Analyste</h2>
        <small class="text-muted">Vue d'ensemble de la sécurité</small>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle p-3" style="background: #dbeafe;">
                                <i class="bi bi-hdd-rack fs-3 text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">{{ $equipments->count() }}</h3>
                            <small class="text-muted">Équipements</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle p-3" style="background: #dcfce7;">
                                <i class="bi bi-search fs-3 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">{{ \App\Models\Scan::count() }}</h3>
                            <small class="text-muted">Scans effectués</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle p-3" style="background: #d1fae5;">
                                <i class="bi bi-check-circle fs-3 text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">{{ \App\Models\Scan::where('status', 'completed')->count() }}</h3>
                            <small class="text-muted">Réussis</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle p-3" style="background: #fef3c7;">
                                <i class="bi bi-file-earmark-text fs-3 text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">{{ \App\Models\Scan::whereNotNull('file_path')->count() }}</h3>
                            <small class="text-muted">Rapports</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@php
    $criticalEquipments = \App\Models\Equipment::where('status', 'needs_review')
        ->orderBy('critical_score_cumul', 'desc')
        ->get();
    $criticalCount = $criticalEquipments->count();
@endphp

@if($criticalCount > 0)
    <div class="card border-danger shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    ⚠️ ALERTES CRITIQUES - Équipements À Revoir
                </h5>
                <span class="badge bg-white text-danger fs-5">{{ $criticalCount }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-warning mb-3">
                <strong>⚠️ Action requise :</strong> {{ $criticalCount }} équipement(s) nécessitent une intervention immédiate suite à la détection de vulnérabilités critiques.
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Équipement</th>
                            <th>IP</th>
                            <th>CVE Critiques</th>
                            <th>Score Cumulé</th>
                            <th>Dernier Scan</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criticalEquipments->take(5) as $equipment)
                            <tr>
                                <td>
                                    <strong>{{ $equipment->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $equipment->type }}</small>
                                </td>
                                <td><code>{{ $equipment->ip_address }}</code></td>
                                <td class="text-center">
                                    <span class="badge bg-danger fs-6">
                                        {{ $equipment->critical_cve_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark fs-6">
                                        {{ number_format($equipment->critical_score_cumul, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        {{ $equipment->last_critical_scan_at ? $equipment->last_critical_scan_at->diffForHumans() : 'N/A' }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('analyst.equipments.scan', $equipment) }}" 
                                           class="btn btn-danger btn-sm"
                                           title="Scanner à nouveau">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </a>
                                        @php
                                            $lastScan = $equipment->scans()->latest()->first();
                                        @endphp
                                        @if($lastScan && $lastScan->file_path)
                                            <a href="{{ route('analyst.scan.download', $lastScan) }}" 
                                               class="btn btn-outline-success btn-sm"
                                               title="Télécharger">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($criticalCount > 5)
                <div class="text-center mt-3">
                    <a href="{{ route('analyst.reports') }}" class="btn btn-danger">
                        Voir tous les équipements critiques ({{ $criticalCount }})
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endif

{{-- Statistiques en cartes (OPTIONNEL - à ajouter sous les 4 cartes existantes si vous voulez) --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: #fee2e2;">
                            <i class="bi bi-exclamation-triangle fs-3 text-danger"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ $criticalCount }}</h3>
                        <small class="text-muted">Critiques</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: #fef3c7;">
                            <i class="bi bi-exclamation-circle fs-3 text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ \App\Models\Equipment::where('risk_level', 'medium')->count() }}</h3>
                        <small class="text-muted">Risque Moyen</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: #d1fae5;">
                            <i class="bi bi-shield-check fs-3 text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ \App\Models\Equipment::where('risk_level', 'low')->count() }}</h3>
                        <small class="text-muted">Sécurisés</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: #dbeafe;">
                            <i class="bi bi-hdd-rack fs-3 text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0">{{ \App\Models\Equipment::count() }}</h3>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0">📊 Derniers Scans Effectués</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Équipement</th>
                            <th>IP</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Scan::with('equipment')->latest()->take(5)->get() as $scan)
                            <tr>
                                <td><strong>#{{ $scan->id }}</strong></td>
                                <td>{{ $scan->equipment->name ?? 'N/A' }}</td>
                                <td><code>{{ $scan->equipment->ip_address ?? 'N/A' }}</code></td>
                                <td>{{ $scan->ended_at?->format('d/m/Y H:i') ?? 'En cours' }}</td>
                                <td>
                                    @if($scan->status === 'completed')
                                        <span class="badge bg-success">✓ Terminé</span>
                                    @elseif($scan->status === 'running')
                                        <span class="badge bg-warning">⏳ En cours</span>
                                    @else
                                        <span class="badge bg-danger">✗ Échoué</span>
                                    @endif
                                </td>
                                <td>
                                    @if($scan->file_path && file_exists(storage_path('app/' . $scan->file_path)))
                                        <a href="{{ route('analyst.scan.download', $scan) }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Aucun scan effectué
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0">
            <a href="{{ route('analyst.scans') }}" class="btn btn-outline-primary btn-sm">
                Voir tous les scans →
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">📈 Activité des 7 derniers jours</h6>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">⚡ Actions Rapides</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('analyst.scans') }}" class="btn btn-primary">
                            <i class="bi bi-search"></i> Lancer un nouveau scan
                        </a>
                        <a href="{{ route('analyst.reports') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-file-earmark-text"></i> Consulter les rapports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    const ctx = document.getElementById('activityChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Il y a 7j', 'Il y a 6j', 'Il y a 5j', 'Il y a 4j', 'Il y a 3j', 'Hier', 'Aujourd\'hui'],
                datasets: [{
                    label: 'Scans effectués',
                    data: [3, 5, 2, 8, 4, 6, 7],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 2
                        }
                    }
                }
            }
        });
    }
</script>
@endpush