@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-3 px-lg-4">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-success bg-opacity-10 p-3 rounded-4 shadow-sm">
                    <i class="bi bi-plus-circle-fill display-4 text-success"></i>
                </div>
                <div>
                    <h1 class="h3 fw-bold mb-1 lh-sm">Ajouter mouvement stock</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-separatorless mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('stocks.index') }}" class="text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>Mouvements
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Nouveau</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card shadow-xl border-0 overflow-hidden">
                <div class="card-header bg-gradient py-4 border-0 text-white position-relative overflow-hidden" 
                     style="background: linear-gradient(135deg, #198754 0%, #146c43 100%) !important;">
                    <div class="position-relative z-index-1">
                        <h3 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-box-seam-fill"></i>
                            Nouveau mouvement
                        </h3>
                        <p class="mb-0 opacity-90 mt-1">Enregistrement d'entrée ou sortie d'équipement</p>
                    </div>
                </div>
                
                <!-- Alerts -->
                @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm m-3 animate__animated animate__shakeX">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm m-3 animate__animated animate__fadeIn">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('stocks.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="card-body p-4 p-lg-5">
                        <!-- Equipment Select -->
                        <div class="mb-4">
                            <label for="equipment_id" class="form-label fw-bold">
                                <i class="bi bi-pc-display-horizontal me-2 text-primary"></i>
                                Équipement
                            </label>
                            <select name="equipment_id" id="equipment_id" class="form-select form-select-lg" required>
                                <option value="">Choisir un équipement...</option>
                                @foreach($equipments as $eq)
                                <option value="{{ $eq->id }}" data-stock="{{ $eq->quantity }}">
                                    {{ $eq->name }} 
                                    <span class="badge bg-light text-dark ms-2">{{ $eq->quantity }} en stock</span>
                                </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Veuillez sélectionner un équipement
                            </div>
                        </div>

                        <!-- Type Select -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="type" class="form-label fw-bold">
                                    <i class="bi bi-arrow-up-down me-2"></i>
                                    Type de mouvement
                                </label>
                                <select name="type" id="type" class="form-select form-select-lg" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="entry">Entrée <i class="bi bi-arrow-up-circle text-success ms-1"></i></option>
                                    <option value="exit">Sortie <i class="bi bi-arrow-down-circle text-danger ms-1"></i></option>
                                </select>
                                <div class="invalid-feedback">
                                    Type obligatoire
                                </div>
                            </div>

                            <!-- Stock Info Dynamic -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Stock actuel</label>
                                <div id="stock-info" class="p-4 rounded-3 bg-light border border-dashed border-secondary-subtle text-center">
                                    <i class="bi bi-info-circle fs-1 text-muted mb-2 opacity-75"></i>
                                    <p class="mb-0 text-muted small">Sélectionnez un équipement</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-5">
                            <label for="quantity" class="form-label fw-bold fs-6">
                                <i class="bi bi-hash me-2 text-info"></i>
                                Quantité
                            </label>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity" 
                                   class="form-control form-control-lg" 
                                   min="1" 
                                   placeholder="Ex: 5" 
                                   required>
                            <div class="form-text">
                                Nombre d'unités à ajouter ou retirer
                            </div>
                            <div class="invalid-feedback">
                                Quantité minimale : 1
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-grid d-md-flex gap-3 justify-content-md-between">
                            <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="bi bi-arrow-left me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow-lg">
                                <i class="bi bi-check-lg me-2"></i>
                                Enregistrer mouvement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const equipmentSelect = document.getElementById('equipment_id');
    const typeSelect = document.getElementById('type');
    const quantityInput = document.getElementById('quantity');
    const stockInfo = document.getElementById('stock-info');
    
    // Dynamic stock info
    equipmentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stock = selectedOption.dataset.stock;
        if (stock) {
            stockInfo.innerHTML = `
                <div class="d-flex flex-column align-items-center gap-2">
                    <div class="display-6 fw-bold text-success">${stock}</div>
                    <small class="text-muted">unités disponibles</small>
                </div>
            `;
        }
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endsection
