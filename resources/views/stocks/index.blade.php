@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-3 px-lg-4">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4 shadow-sm">
                        <i class="bi bi-arrow-repeat display-4 text-primary"></i>
                    </div>
                    <div>
                        <h1 class="h3 fw-bold mb-1 lh-sm">Mouvements de stock</h1>
                        <p class="text-muted mb-0 small">Suivi complet des entrées et sorties d'équipements</p>
                    </div>
                </div>
                <a href="{{ route('stocks.create') }}" class="btn btn-primary btn-lg px-4 py-2 shadow-lg">
                    <i class="bi bi-plus-circle-fill me-2"></i>
                    Ajouter mouvement
                </a>
            </div>
        </div>
    </div>

    <!-- Movements Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-xl border-0 overflow-hidden">
                <div class="card-header bg-gradient py-4 border-0" style="background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%) !important;">
                    <h5 class="card-title mb-0 text-white fw-bold">
                        <i class="bi bi-table me-2"></i>
                        Historique des mouvements
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 table-hover align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="py-4 ps-4 border-0">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary fs-6 px-2 py-1">Équipement</span>
                                        </div>
                                    </th>
                                    <th class="text-center py-4 border-0" style="width: 140px;">
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <i class="bi bi-arrow-up-circle-fill text-success fs-4"></i>
                                            <small>Type</small>
                                        </div>
                                    </th>
                                    <th class="text-center py-4 border-0" style="width: 130px;">
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <i class="bi bi-hash text-primary fs-4"></i>
                                            <small>Quantité</small>
                                        </div>
                                    </th>
                                    <th class="text-center py-4 border-0" style="width: 180px;">
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <i class="bi bi-person-circle text-secondary fs-4"></i>
                                            <small>Utilisateur</small>
                                        </div>
                                    </th>
                                    <th class="text-center py-4 border-0 pe-4">
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <i class="bi bi-calendar3 text-info fs-4"></i>
                                            <small>Date</small>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $m)
                                <tr class="animate__animated animate__fadeIn">
                                    <td class="ps-4 py-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-lg bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow">
                                                    <i class="bi bi-pc-display-horizontal fs-3"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $m->equipment->name }}</h6>
                                                <small class="text-muted">ID: {{ $m->equipment->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-4">
                                        @if($m->type === 'entrée')
                                            <span class="badge bg-success-subtle text-success border border-success px-3 py-2 fs-6 fw-semibold">
                                                <i class="bi bi-arrow-up-circle-fill me-1"></i>
                                                Entrée +{{ $m->quantity }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 fs-6 fw-semibold">
                                                <i class="bi bi-arrow-down-circle-fill me-1"></i>
                                                Retrait -{{ $m->quantity }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center py-4">
                                        <div class="display-6 fw-bold {{ $m->type === 'entrée' ? 'text-success' : 'text-danger' }}">
                                            {{ $m->quantity }}
                                        </div>
                                        <small class="text-muted">unités</small>
                                    </td>
                                    <td class="text-center py-4">
                                        <div class="avatar-md mx-auto mb-2 bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm">
                                            <i class="bi bi-person-fill fs-4 text-primary"></i>
                                        </div>
                                        <div class="fw-semibold">{{ $m->user?->name ?? 'Système automatique' }}</div>
                                    </td>
                                    <td class="pe-4 py-4 text-center">
                                        <div class="timeline-badge bg-info border-info mx-auto mb-2"></div>
                                        <div class="fw-bold text-dark mb-1">{{ $m->created_at->format('d MMM Y') }}</div>
                                        <div class="text-muted small">{{ $m->created_at->format('HH:mm') }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center gap-3">
                                            <div class="avatar-xxl bg-light rounded-circle d-flex align-items-center justify-content-center shadow-lg">
                                                <i class="bi bi-inbox display-1 text-muted opacity-50"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-muted mb-2">Aucun mouvement</h4>
                                                <p class="text-muted mb-0">Commencez par ajouter votre premier mouvement de stock</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
