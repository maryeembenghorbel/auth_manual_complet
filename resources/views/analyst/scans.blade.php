@extends('layouts.app')

@section('title', 'Scans - SIAM')

@push('styles')
<style>
    :root {
        --sidebar-width: 250px;
        --sidebar-bg: #1e293b;
        --sidebar-hover: #334155;
    }

    body {
        background-color: #f8fafc;
    }

    /* Sidebar */
    .analyst-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: var(--sidebar-width);
        background: var(--sidebar-bg);
        color: white;
        padding-top: 80px;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        z-index: 999;
    }

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
    }

    .analyst-content {
     margin-left: 100px;  /* ⬅️ Réduit de 250px à 220px (30px vers la gauche) */
        margin-top: 20px;     /* ⬆️ Réduit de 60px à 50px (10px vers le haut) */
        padding: 20px;        /* Réduit le padding aussi */
        min-height: calc(100vh - 50px);
    }

    .page-header {
        background: white;
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
</style>
@endpush

@section('content')

<!-- Sidebar -->
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

<!-- Main Content -->
<div class="analyst-content">

    <div class="page-header">
        <h2>🔍 Scanner les Équipements</h2>
        <small class="text-muted">Lancez des scans de vulnérabilités sur vos équipements</small>
    </div>

    {{-- Messages --}}
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

    <!-- Tableau des équipements -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">📋 Équipements Disponibles</h5>
            <span class="badge bg-primary">{{ $equipments->count() }} équipement(s)</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Adresse IP</th>
                        <th>État</th>
                        <th>Dernier Scan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipments as $equipment)
                        <tr>
                            <td><strong>{{ $equipment->name }}</strong></td>
                            <td><span class="badge bg-secondary">{{ $equipment->type }}</span></td>
                            <td><code>{{ $equipment->ip_address }}</code></td>
                            <td><span class="badge bg-success">✓ Actif</span></td>
                            <td>
                                @php
                                    $lastScan = $equipment->scans()->latest('ended_at')->first();
                                @endphp

                                @if($lastScan)
                                    <div class="small">
                                        <strong>📅</strong> {{ $lastScan->ended_at?->format('d/m/Y H:i') ?? 'En cours' }}<br>
                                        @if($lastScan->status === 'completed')
                                            <span class="badge bg-success">✓ Terminé</span>
                                        @elseif($lastScan->status === 'running')
                                            <span class="badge bg-warning">⏳ En cours</span>
                                        @else
                                            <span class="badge bg-danger">✗ Échoué</span>
                                        @endif
                                    </div>

                                    {{-- Boutons d'action --}}
                                    <div class="mt-2">
                                        @if($lastScan->result)
                                            <button 
                                                type="button" 
                                                class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#scanModal{{ $lastScan->id }}"
                                            >
                                                👁️ Voir
                                            </button>
                                        @endif

                                        @if($lastScan->file_path && file_exists(storage_path('app/' . $lastScan->file_path)))
                                            <a 
                                                href="{{ route('analyst.scan.download', $lastScan) }}" 
                                                class="btn btn-sm btn-success"
                                            >
                                                💾 Télécharger
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Modal résultats --}}
                                    @if($lastScan->result)
                                        <div class="modal fade" id="scanModal{{ $lastScan->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark text-white">
                                                        <h5 class="modal-title">📊 Résultats du Scan #{{ $lastScan->id }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-info">
                                                            <strong>🖥️ Équipement :</strong> {{ $equipment->name }}<br>
                                                            <strong>📍 IP :</strong> <code>{{ $equipment->ip_address }}</code><br>
                                                            <strong>📅 Date :</strong> {{ $lastScan->ended_at?->format('d/m/Y à H:i:s') }}
                                                        </div>
                                                        <pre style="background: #f8f9fa; padding: 20px; border-radius: 5px;">{{ $lastScan->result }}</pre>
                                                    </div>
                                                    <div class="modal-footer">
                                                        @if($lastScan->file_path && file_exists(storage_path('app/' . $lastScan->file_path)))
                                                            <a href="{{ route('analyst.scan.download', $lastScan) }}" class="btn btn-success">
                                                                💾 Télécharger
                                                            </a>
                                                        @endif
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted"><em>Aucun scan</em></span>
                                @endif
                            </td>
                            <td>
                                <form 
                                    method="POST" 
                                    action="{{ route('analyst.equipments.scan', $equipment) }}"
                                    onsubmit="return confirm('⚠️ Lancer un scan sur {{ $equipment->name }} ?');"
                                >
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="btn btn-danger btn-sm"
                                        @if($lastScan && $lastScan->status === 'running') disabled @endif
                                    >
                                        @if($lastScan && $lastScan->status === 'running')
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            En cours...
                                        @else
                                            🔍 Lancer scan
                                        @endif
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Aucun équipement trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Guide -->
    <div class="mt-4">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="card-title">💡 Guide d'utilisation</h6>
                <ul class="mb-0 small">
                    <li>Cliquez sur <strong>"🔍 Lancer scan"</strong> pour analyser un équipement</li>
                    <li>Le scan détecte les ports ouverts et les services actifs</li>
                    <li>Cliquez sur <strong>"👁️ Voir"</strong> pour consulter les résultats</li>
                    <li>Cliquez sur <strong>"💾 Télécharger"</strong> pour sauvegarder le rapport</li>
                </ul>
            </div>
        </div>
    </div>

</div>

@endsection