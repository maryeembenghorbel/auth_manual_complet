@extends('layouts.app')

@section('page-title', 'Modifier un utilisateur')
@section('page-subtitle', "Mise à jour des informations de l'utilisateur")

@section('content')
<div class="row justify-content-center g-4">
    <div class="col-lg-7 col-xl-6">
        <div class="content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-white">
                    <i class="fas fa-user-edit me-2"></i>Modifier l'utilisateur
                </h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                </a>
            </div>

            <div class="card-body p-4">
                {{-- Affichage erreurs --}}
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

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                Nom complet
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                Adresse email
                            </label>
                            <input type="email"
                                   name="email"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                Nouveau mot de passe
                                <small class="text-muted d-block">Laisser vide pour ne pas changer</small>
                            </label>
                            <input type="password"
                                   name="password"
                                   class="form-control form-control-lg @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">
                                Confirmation du mot de passe
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control form-control-lg">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">
                                Rôle
                            </label>
                            <select name="role_id"
                                    class="form-select form-select-lg @error('role_id') is-invalid @enderror"
                                    required>
                                <option value="">Sélectionner un rôle</option>
    @foreach($roles as $role)
    @if(strtolower($role->name) !== 'admin')
        <option value="{{ $role->id }}"
            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
            {{ ucfirst($role->name) }}
        </option>
    @endif
@endforeach

                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save me-2"></i>Mettre à jour
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-times me-1"></i>Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        animation: slideInUp 0.5s ease-out;
    }
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(15px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>
@endpush
