@extends('layouts.app')

@section('title', 'Liste des équipements - Consultant')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary">Inventaire complet</h2>
            <p class="text-muted">Consultation détaillée de l'ensemble du parc informatique</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="ps-4">Image</th>
                            <th>Équipement (Marque/Modèle)</th>
                            <th>Tech (Type/Série)</th>
                            <th>État</th>
                            <th class="text-center">Stock</th>
                            <th class="text-end">Prix</th>
                            <th>Fournisseur</th>
                            <th>Dates (Achat / Garantie)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipments as $equipment)
                        <tr>
                            <td class="ps-4">
                                @if($equipment->image)
                                    <img src="{{ asset('storage/' . $equipment->image) }}" 
                                         alt="Img" 
                                         class="rounded shadow-sm"
                                         style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #dee2e6;">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center bg-light text-secondary border" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-camera-slash"></i>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold text-dark">{{ $equipment->name }}</div>
                                <div class="small text-muted">
                                    <span class="fw-semibold">{{ $equipment->brand }}</span> 
                                    @if($equipment->model)
                                        - {{ $equipment->model }}
                                    @endif
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border mb-1">
                                    {{ $equipment->type }}
                                </span>
                                <br>
                                <small class="font-monospace text-muted">{{ $equipment->serial_number }}</small>
                            </td>

                            <td>
                                @php
                                    $badgeClass = match(strtolower($equipment->state)) {
                                        'neuf', 'excellent' => 'bg-success',
                                        'en service', 'fonctionnel' => 'bg-info text-dark',
                                        'panne', 'hs', 'broken' => 'bg-danger',
                                        'reparation', 'maintenance' => 'bg-warning text-dark',
                                        'bientot_hs' => 'bg-warning',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($equipment->state) }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="badge rounded-pill bg-light text-dark border px-3">
                                    {{ $equipment->quantity }}
                                </span>
                            </td>

                            <td class="text-end fw-bold text-secondary">
                                @if($equipment->price)
                                    {{ number_format($equipment->price, 2, ',', ' ') }} <small>DT</small>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <span class="text-muted small">
                                    <i class="fas fa-truck me-1"></i> {{ $equipment->supplier ?? 'Inconnu' }}
                                </span>
                            </td>

                            <td style="min-width: 140px;">
                                <div class="small text-muted mb-1">
                                    <i class="far fa-calendar-alt me-1"></i> Achat: 
                                    {{ $equipment->purchase_date ? \Carbon\Carbon::parse($equipment->purchase_date)->format('d/m/Y') : '-' }}
                                </div>

                                @if($equipment->warranty)
                                    @php
                                        $warrantyDate = \Carbon\Carbon::parse($equipment->warranty);
                                        $isExpired = $warrantyDate->isPast();
                                    @endphp
                                    <div class="small {{ $isExpired ? 'text-danger fw-bold' : 'text-success' }}">
                                        <i class="fas {{ $isExpired ? 'fa-exclamation-circle' : 'fa-shield-alt' }} me-1"></i>
                                        Fin: {{ $warrantyDate->format('d/m/Y') }}
                                    </div>
                                @else
                                    <div class="small text-muted">Pas de garantie</div>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i><br>
                                Aucun équipement trouvé dans l'inventaire
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $equipments->links() }} 
            </div>
        </div>
    </div>
</div>
@endsection