@extends('layouts.app')

@section('title', 'Rapports - SIAM')

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

    .report-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .report-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
        <h2>📄 Rapports de Sécurité</h2>
        <small class="text-muted">Consultez tous les rapports de scan générés</small>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('analyst.reports') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Équipement</label>
                    <select name="equipment_id" class="form-select">
                        <option value="">Tous les équipements</option>
                        @foreach(\App\Models\Equipment::all() as $eq)
                            <option value="{{ $eq->id }}" {{ request('equipment_id') == $eq->id ? 'selected' : '' }}>
                                {{ $eq->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Tous les status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des rapports -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">📚 Tous les Rapports</h5>
            <span class="badge bg-primary">{{ $scans->total() }} rapport(s)</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Équipement</th>
                        <th>IP</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Fichier</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scans as $scan)
                        <tr>
                            <td><strong>#{{ $scan->id }}</strong></td>
                            <td>{{ $scan->equipment->name ?? 'N/A' }}</td>
                            <td><code>{{ $scan->equipment->ip_address ?? 'N/A' }}</code></td>
                            <td><span class="badge bg-secondary">{{ $scan->scan_type }}</span></td>
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
                                    <span class="badge bg-success">
                                        <i class="bi bi-file-earmark-check"></i> Disponible
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="bi bi-file-earmark-x"></i> Manquant
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($scan->result)
                                        <button 
                                            type="button" 
                                            class="btn btn-outline-info"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#scanModal{{ $scan->id }}"
                                            title="Voir le rapport"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    @endif

                                    @if($scan->file_path && file_exists(storage_path('app/' . $scan->file_path)))
                                        <a 
                                            href="{{ route('analyst.scan.download', $scan) }}" 
                                            class="btn btn-outline-success"
                                            title="Télécharger"
                                        >
                                            <i class="bi bi-download"></i>
                                        </a>
                                    @endif
                                </div>

                                {{-- Modal résultats --}}
                                @if($scan->result)
                                    <div class="modal fade" id="scanModal{{ $scan->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header bg-dark text-white">
                                                    <h5 class="modal-title">📊 Rapport #{{ $scan->id }} - {{ $scan->equipment->name ?? 'N/A' }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-info">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>🖥️ Équipement :</strong> {{ $scan->equipment->name ?? 'N/A' }}<br>
                                                                <strong>📍 IP :</strong> <code>{{ $scan->equipment->ip_address ?? 'N/A' }}</code>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>📅 Date :</strong> {{ $scan->ended_at?->format('d/m/Y à H:i:s') }}<br>
                                                                <strong>🔍 Type :</strong> {{ $scan->scan_type }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <pre style="background: #f8f9fa; padding: 20px; border-radius: 5px; max-height: 500px; overflow-y: auto;">{{ $scan->result }}</pre>
                                                </div>
                                                <div class="modal-footer">
                                                    @if($scan->file_path && file_exists(storage_path('app/' . $scan->file_path)))
                                                        <a href="{{ route('analyst.scan.download', $scan) }}" class="btn btn-success">
                                                            💾 Télécharger (.txt)
                                                        </a>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-3">Aucun rapport trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($scans->hasPages())
            <div class="card-footer bg-white">
                {{ $scans->links() }}
            </div>
        @endif
    </div>

</div>

@endsection