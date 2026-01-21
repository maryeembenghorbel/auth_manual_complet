@extends('layouts.app')

@section('title', 'Consultation Équipements')

@section('content')
<div class="container-fluid py-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-primary">Liste des Équipements</h2>
            <p class="text-muted">Consultation et recherche dans l'inventaire</p>
        </div>
    </div>

    {{-- 1. SECTION FILTRES --}}
    <div class="card mb-4 border-0 shadow-sm bg-light">
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row g-3">
                
                {{-- Recherche --}}
                <div class="col-md-4">
                    <label class="form-label small text-muted fw-bold">Recherche</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" 
                               placeholder="Nom, Marque, Série..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Type --}}
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-bold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">Tous les types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted fw-bold">État</label>
                    <select name="state" class="form-select">
                        <option value="">Tous les états</option>
                        <option value="Neuf" {{ request('state') == 'Neuf' ? 'selected' : '' }}>Neuf</option>
                        <option value="En service" {{ request('state') == 'En service' ? 'selected' : '' }}>En service</option>
                        <option value="En réparation" {{ request('state') == 'En réparation' ? 'selected' : '' }}>En réparation</option>
                        <option value="HS" {{ request('state') == 'HS' ? 'selected' : '' }}>HS</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        Filtrer
                    </button>

                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary" title="Réinitialiser">
                            <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4">Équipement</th>
                            <th>Info Technique</th>
                            <th>État</th>
                            <th>Date d'ajout</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipments as $equipment)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    {{-- Image (Optionnel) --}}
                                    @if($equipment->image)
                                        <img src="{{ asset('storage/' . $equipment->image) }}" class="rounded me-3" width="40" height="40" style="object-fit:cover;">
                                    @else
                                        <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center text-secondary" style="width:40px; height:40px;">
                                            <i class="fas fa-cube"></i>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <div class="fw-bold text-dark">{{ $equipment->name }}</div>
                                        <div class="small text-muted">{{ $equipment->brand }} {{ $equipment->model }}
                                           @if($equipment->ip_address)
                                               <br><small class="text-muted">IP: <code>{{ $equipment->ip_address }}</code></small>
                                           @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border">{{ $equipment->type }}</span>
                                <div class="small text-muted font-monospace mt-1">{{ $equipment->serial_number }}</div>
                            </td>

                            <td>
                                @php
                                    $st = strtolower($equipment->state);
                                    $badge = 'bg-secondary';
                                    if(in_array($st, ['neuf', 'excellent', 'en service', 'fonctionnel'])) $badge = 'bg-success';
                                    if(in_array($st, ['panne', 'hs', 'broken'])) $badge = 'bg-danger';
                                    if(in_array($st, ['reparation', 'maintenance'])) $badge = 'bg-warning text-dark';
                                @endphp
                                <span class="badge {{ $badge }}">{{ ucfirst($equipment->state) }}</span>
                            </td>

                            <td class="text-muted small">
                                {{ $equipment->created_at ? $equipment->created_at->format('d/m/Y') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-search fa-2x mb-3 opacity-50"></i><br>
                                Aucun résultat trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $equipments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection