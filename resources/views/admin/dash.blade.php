@extends('layouts.app')

@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Gestion du parc informatique')

@section('content')
<div class="row g-4">
    {{-- HEADER --}}
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-primary bg-opacity-20 p-2 rounded-circle me-3">
                        <i class="fas fa-tachometer-alt text-primary fs-4"></i>
                    </div>
                    <div>
                        <h1 class="page-title mb-1">Bienvenue, {{ Auth::user()->name }} !</h1>
                        <p class="text-muted mb-0">Admin Dashboard - {{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            <div class="input-group" style="max-width: 350px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Rechercher...">
            </div>
        </div>
    </div>

  {{-- STATS = --}}
<div class="row g-4 mb-4">
    {{--  Équipements Critiques --}}
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100 shadow-sm border-0 overflow-hidden">
            <div class="card-body text-center py-4 position-relative">
                <div class="position-absolute top-0 end-0 p-3">
                    <i class="fas fa-exclamation-triangle text-danger opacity-75"></i>
                </div>
                <i class="fas fa-shield-virus fa-3x text-danger mb-3 opacity-75"></i>
                <h3 class="mb-1 fw-bold">{{ \App\Models\Equipment::where('status', 'needs_review')->count() }}</h3>
                <p class="text-muted mb-2">Équipements Critiques</p>
            </div>
        </div>
    </div>

{{--  Équipements Sécurisés --}}
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100 shadow-sm border-0 overflow-hidden">
            <div class="card-body text-center py-4 position-relative">
                <div class="position-absolute top-0 end-0 p-3">
                    <i class="fas fa-check-circle text-success opacity-75"></i>
                </div>
                <i class="fas fa-check-circle fa-3x text-success mb-3 opacity-75"></i>
                <h3 class="mb-1 fw-bold">{{ \App\Models\Equipment::where('risk_level', 'low')->count() }}</h3>
                <p class="text-muted mb-2">Équipements Sécurisés</p>
            </div>
        </div>
    </div>

    {{--  Total équipements --}}
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100 shadow-sm border-0 overflow-hidden">
            <div class="card-body text-center py-4 position-relative">
                <div class="position-absolute top-0 end-0 p-3">
                    <i class="fas fa-chart-line text-primary opacity-75"></i>
                </div>
                <i class="fas fa-desktop fa-3x text-primary mb-3 opacity-75"></i>
                <h3 class="mb-1 fw-bold">{{ \App\Models\Equipment::count() ?? 0 }}</h3>
                <p class="text-muted mb-2">Total équipements</p>
            </div>
        </div>
    </div>

    {{-- Utilisateurs --}}
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100 shadow-sm border-0 overflow-hidden">
            <div class="card-body text-center py-4 position-relative">
                <div class="position-absolute top-0 end-0 p-3">
                    <i class="fas fa-users text-info opacity-75"></i>
                </div>
                <i class="fas fa-users fa-3x text-info mb-3 opacity-75"></i>
                <h3 class="mb-1 fw-bold">{{ \App\Models\User::count() ?? 0 }}</h3>
                <p class="text-muted mb-2">Utilisateurs</p>
            </div>
        </div>
    </div>

    {{--Affectations --}}
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100 shadow-sm border-0 overflow-hidden">
            <div class="card-body text-center py-4 position-relative">
                <div class="position-absolute top-0 end-0 p-3">
                    <i class="fas fa-exchange-alt text-success opacity-75"></i>
                </div>
                <i class="fas fa-exchange-alt fa-3x text-success mb-3 opacity-75"></i>
                <h3 class="mb-1 fw-bold">{{ \App\Models\Assignment::count() ?? 0 }}</h3>
                <p class="text-muted mb-2">Affectations totales</p>
            </div>
        </div>
    </div>

    {{-- Stock critique --}}
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100 shadow-sm border-0 overflow-hidden bg-gradient-warning text-white">
            <div class="card-body text-center py-4">
                <i class="fas fa-exclamation-triangle fa-3x mb-3 opacity-90"></i>
                <h3 class="mb-1 fw-bold">{{ \App\Models\Equipment::where('quantity', '<', 5)->count() ?? 0 }}</h3>
                <p class="mb-0 small">Stock critique</p>
                @if(\App\Models\Equipment::where('quantity', '<', 5)->count() ?? 0 > 0)
                    <a href="{{ route('admin.equipments.index') }}?quantity_max=5" class="btn btn-sm btn-light mt-2">Remplir</a>
                @endif
            </div>
        </div>
    </div>
</div>
    {{-- QUICK ACTIONS --}}
    <div class="row g-4">
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('admin.users.index') }}" class="content-card text-decoration-none h-100 shadow-sm hover-lift">
                <div class="card-body text-center py-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 mb-3 mx-auto" style="width: 70px; height: 70px;">
                        <i class="fas fa-users fs-2 text-primary"></i>
                    </div>
                    <h6 class="mb-1 fw-bold">Utilisateurs</h6>
                    <p class="text-muted mb-0 small">Gestion des comptes</p>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('admin.equipments.index') }}" class="content-card text-decoration-none h-100 shadow-sm hover-lift">
                <div class="card-body text-center py-4">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 mb-3 mx-auto" style="width: 70px; height: 70px;">
                        <i class="fas fa-desktop fs-2 text-info"></i>
                    </div>
                    <h6 class="mb-1 fw-bold">Équipements</h6>
                    <p class="text-muted mb-0 small">Inventaire complet</p>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('admin.assignments.index') }}" class="content-card text-decoration-none h-100 shadow-sm hover-lift">
                <div class="card-body text-center py-4">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 mb-3 mx-auto" style="width: 70px; height: 70px;">
                        <i class="fas fa-exchange-alt fs-2 text-success"></i>
                    </div>
                    <h6 class="mb-1 fw-bold">Affectations</h6>
                    <p class="text-muted mb-0 small">Suivi des prêts</p>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
}
</style>
@endsection
