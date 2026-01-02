@extends('layouts.app')

@section('page-title', 'Modifier un équipement')
@section('page-subtitle', "Mise à jour des informations du matériel")

@section('content')
<div class="row justify-content-center g-4">
    <div class="col-lg-8 col-xl-7">
        <div class="content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-white">
                    <i class="fas fa-tools me-2"></i>Modifier l'équipement
                </h5>
                <a href="{{ route('admin.equipments.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                </a>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <strong>Attention :</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li><i class="fas fa-exclamation-triangle me-1"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.equipments.update', $equipment) }}" method="POST"
                      enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        {{-- mêmes champs que create mais avec old() + valeurs du modèle --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom du matériel</label>
                            <input type="text" name="name" class="form-control form-control-lg"
                                   value="{{ old('name', $equipment->name) }}" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Marque</label>
                            <input type="text" name="brand" class="form-control form-control-lg"
                                   value="{{ old('brand', $equipment->brand) }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Modèle</label>
                            <input type="text" name="model" class="form-control form-control-lg"
                                   value="{{ old('model', $equipment->model) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Type</label>
                            <select name="type" class="form-select form-select-lg" required>
                                @foreach(['PC','Écran','Routeur','Switch','Imprimante','Autre'] as $type)
                                    <option value="{{ $type }}" {{ old('type', $equipment->type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Numéro de série</label>
                            <input type="text" name="serial_number" class="form-control form-control-lg"
                                   value="{{ old('serial_number', $equipment->serial_number) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">État</label>
                            <select name="state" class="form-select form-select-lg" required>
                                @foreach(['Neuf','En service','En panne','Maintenance','HS'] as $state)
                                    <option value="{{ $state }}" {{ old('state', $equipment->state) == $state ? 'selected' : '' }}>
                                        {{ $state }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fournisseur</label>
                            <input type="text" name="supplier" class="form-control form-control-lg"
                                   value="{{ old('supplier', $equipment->supplier) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Quantité</label>
                            <input type="number" name="quantity" class="form-control form-control-lg"
                                   value="{{ old('quantity', $equipment->quantity) }}" min="0" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Prix unitaire (TND)</label>
                            <input type="number" step="0.01" name="price" class="form-control form-control-lg"
                                   value="{{ old('price', $equipment->price) }}">
                        </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fin de garantie</label>
                        <input type="date" name="warranty" class="form-control form-control-lg"
                               value="{{ old('warranty', optional($equipment->warranty)->format('Y-m-d')) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Image</label>
                        <input type="file" name="image" class="form-control form-control-lg mb-2">

                        @if($equipment->image)
                            <small class="text-muted d-block mb-2">Image actuelle :</small>
                            <img src="{{ asset('storage/'.$equipment->image) }}"
                                 class="rounded"
                                 style="width:80px;height:80px;object-fit:cover;">
                        @endif
                    </div>
                </div> {{-- fin .row g-4 --}}

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                    <a href="{{ route('admin.equipments.index') }}" class="btn btn-outline-secondary px-4">
                        Annuler
                    </a>
                </div>
            </form>
        </div> {{-- fin .card-body --}}
    </div>     {{-- fin .content-card --}}
</div>         {{-- fin .col --}}
</div>         {{-- fin .row --}}
@endsection
