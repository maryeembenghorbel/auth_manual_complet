@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Tableau de Bord - Consultant</h2>
            <p class="text-muted">Vue d'ensemble de l'inventaire</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3 shadow-sm h-100">
                <div class="card-header">Total Équipements</div>
                <div class="card-body">
                    <h1 class="card-title">{{ $totalEquipments ?? 0 }}</h1>
                    <p class="card-text">Matériels enregistrés</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3 shadow-sm h-100">
                <div class="card-header">En Utilisation</div>
                <div class="card-body">
                    <h1 class="card-title">{{ $assignedEquipments ?? 0 }}</h1>
                    <p class="card-text">Matériels affectés</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3 shadow-sm h-100">
                <div class="card-header">En Stock</div>
                <div class="card-body">
                    <h1 class="card-title">{{ $availableEquipments ?? 0 }}</h1>
                    <p class="card-text">Disponibles immédiatement</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Derniers Équipements Ajoutés</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>N° série</th>
                            <th>État</th>
                            <th>Date d'ajout</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipments as $equipment)
                        <tr>
                            <td><strong>{{ $equipment->name }}</strong></td>
                            <td><span class="badge bg-secondary">{{ $equipment->type }}</span></td>
                            <td>{{ $equipment->serial_number }}</td>
                            <td>
                                @php
                                    $s = strtolower($equipment->state);
                                @endphp
                                @if($s == 'fonctionnel' || $s == 'en service' || $s == 'neuf')
                                    <span class="badge bg-success">{{ ucfirst($equipment->state) }}</span>
                                @elseif($s == 'panne' || $s == 'hs')
                                    <span class="badge bg-danger">{{ ucfirst($equipment->state) }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ ucfirst($equipment->state) }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($equipment->created_at)->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucun équipement trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection