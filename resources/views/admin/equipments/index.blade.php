@extends('layouts.app')

@section('page-title', 'Matériel & Équipements')
@section('page-subtitle', 'Gestion du parc informatique et réseau')

@section('content')
<div class="row g-4">
    {{-- Header + stats --}}
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-desktop me-2"></i>Équipements
            </h1>
            <p class="text-muted mb-0">
                Gérez le cycle de vie, le stock et les catégories de votre matériel.
            </p>
        </div>
        <a href="{{ route('admin.equipments.create') }}" class="btn btn-primary px-4">
            <i class="fas fa-plus-circle me-2"></i>Ajouter un matériel
        </a>
    </div>

    
    {{-- Cartes statistiques de sécurité --}}
 
    <div class="col-12">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="content-card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">{{ \App\Models\Equipment::where('status', 'needs_review')->count() }}</h3>
                                <small class="text-white-50">Équipements Critiques</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle fa-2x opacity-75"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">{{ \App\Models\Equipment::where('risk_level', 'medium')->count() }}</h3>
                                <small>Risque Moyen</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shield-alt fa-2x opacity-75"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">{{ \App\Models\Equipment::where('risk_level', 'low')->count() }}</h3>
                                <small class="text-white-50">Sécurisés</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="content-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-desktop fa-2x opacity-75"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-0">{{ \App\Models\Equipment::count() }}</h3>
                                <small class="text-white-50">Total Équipements</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="content-card mb-2">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Type</label>
                        <select name="type" class="form-select">
                            <option value="">Tous</option>
                            @foreach(['PC','Écran','Routeur','Switch','Imprimante','Autre'] as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">État</label>
                        <select name="state" class="form-select">
                            <option value="">Tous</option>
                            @foreach(['Neuf','En service','En panne','Maintenance','HS'] as $state)
                                <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>
                                    {{ $state }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{--  Filtre par niveau de risque --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Risque Sécurité</label>
                        <select name="risk_level" class="form-select">
                            <option value="">Tous</option>
                            <option value="needs_review" {{ request('risk_level') == 'needs_review' ? 'selected' : '' }}>
                                🔴 Critique
                            </option>
                            <option value="medium" {{ request('risk_level') == 'medium' ? 'selected' : '' }}>
                                🟡 Moyen
                            </option>
                            <option value="low" {{ request('risk_level') == 'low' ? 'selected' : '' }}>
                                🟢 Faible
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Recherche</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Nom, marque, n° de série..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <button class="btn btn-outline-secondary me-2" type="submit">
                            Filtrer
                        </button>
                        <a href="{{ route('admin.equipments.index') }}" class="btn btn-link text-muted">
                            Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tableau principal --}}
    <div class="col-12">
        <div class="content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-white">
                    <i class="fas fa-list me-2" style="color: black;"></i>Liste des équipements
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Matériel</th>
                                <th>Type</th>
                                <th>État</th>
                                <th>Niveau de Risque</th> 
                                <th>CVE Critiques</th> 
                                <th>Stock</th>
                                <th>Prix</th>
                                <th>Acheté le</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($equipments as $equipment)
                            {{--  Ligne rouge si critique --}}
                            <tr class="{{ $equipment->status == 'needs_review' ? 'table-danger' : '' }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($equipment->image)
                                            <img src="{{ asset('storage/'.$equipment->image) }}"
                                                 class="rounded me-3"
                                                 style="width:45px;height:45px;object-fit:cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3"
                                                 style="width:45px;height:45px;">
                                                <i class="fas fa-box text-secondary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $equipment->name }}
                                                {{--  Badge "À REVOIR" --}}
                                                @if($equipment->status == 'needs_review')
                                                    <span class="badge bg-danger ms-1">À REVOIR</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">
                                                {{ $equipment->brand }} {{ $equipment->model }}
                                                @if($equipment->serial_number)
                                                    · SN: {{ $equipment->serial_number }}
                                                @endif
                                                @if($equipment->ip_address)
                                                    <br>· IP: <code>{{ $equipment->ip_address }}</code>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info text-white px-3 py-2 rounded-pill">
                                        {{ $equipment->type }}
                                    </span>
                                </td>
                                <td>
                                    @php $state = $equipment->state; @endphp
                                    <span class="badge px-3 py-2 rounded-pill
                                        @if($state === 'Neuf') bg-success
                                        @elseif($state === 'En service') bg-primary
                                        @elseif($state === 'En panne') bg-warning text-dark
                                        @elseif($state === 'Maintenance') bg-info text-dark
                                        @else bg-danger @endif">
                                        {{ $state }}
                                    </span>
                                </td>

                                {{--  Niveau de Risque --}}
                                <td>
                                    @if($equipment->status == 'needs_review')
                                        <span class="badge bg-danger px-3 py-2 rounded-pill">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            CRITIQUE
                                        </span>
                                    @elseif($equipment->risk_level == 'medium')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            MOYEN
                                        </span>
                                    @elseif($equipment->risk_level == 'low')
                                        <span class="badge bg-success px-3 py-2 rounded-pill">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            FAIBLE
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                            <i class="fas fa-question-circle me-1"></i>
                                            NON SCANNÉ
                                        </span>
                                    @endif
                                </td>

                                {{--  CVE Critiques --}}
                              
                                <td class="text-center">
                                    @if($equipment->critical_cve_count > 0)
                                        <div>
                                            <span class="badge bg-dark px-3 py-1 rounded-pill fs-6">
                                                {{ $equipment->critical_cve_count }} CVE
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                Score: {{ number_format($equipment->critical_score_cumul, 1) }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($equipment->quantity <= 0)
                                        <span class="badge bg-danger">Rupture</span>
                                    @elseif($equipment->quantity < 5)
                                        <span class="badge bg-warning text-dark">Stock faible ({{ $equipment->quantity }})</span>
                                    @else
                                        <span class="badge bg-success">Stock : {{ $equipment->quantity }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($equipment->price)
                                        {{ number_format($equipment->price, 2, ',', ' ') }} TND
                                    @else
                                        <span class="text-muted">–</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $equipment->purchase_date ? $equipment->purchase_date->format('d/m/Y') : '—' }}
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.equipments.edit', $equipment) }}"
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.equipments.destroy', $equipment) }}" method="POST"
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-delete-equipment"
                                                    data-equipment-name="{{ $equipment->name }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                
                                <td colspan="9" class="text-center py-5 text-muted">
                                    Aucun équipement trouvé. Ajoutez votre premier matériel.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($equipments->hasPages())
            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-muted">
                    Page {{ $equipments->currentPage() }} / {{ $equipments->lastPage() }}
                </span>
                {{ $equipments->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

@include('admin.equipments._delete-modal')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete-equipment');
    const deleteModal   = new bootstrap.Modal(document.getElementById('deleteEquipmentModal'));
    const nameSpan      = document.getElementById('deleteEquipmentName');
    const confirmBtn    = document.getElementById('confirmDeleteEquipmentBtn');
    let currentForm     = null;

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const name = this.dataset.equipmentName;
            currentForm = this.closest('form');
            nameSpan.textContent = name;
            deleteModal.show();
        });
    });

    confirmBtn.addEventListener('click', function () {
        if (currentForm) currentForm.submit();
    });
});
</script>
@endpush

@endsection