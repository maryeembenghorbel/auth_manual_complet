@extends('layouts.app')

@section('page-title', 'Inscription')
@section('page-subtitle', 'Créer un compte pour accéder à SIAM')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 100px);">
    <div class="col-md-6 col-lg-5">
        <div class="content-card auth-card border-0">
            <div class="card-header text-center">
                <h5 class="mb-0 fw-bold text-white">
                    <i class="fa-solid fa-user-plus me-2"></i> Inscription
                </h5>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-3">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        Veuillez corriger les erreurs ci-dessous.
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    {{-- Nom --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fa-solid fa-user me-1"></i> Nom complet
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control form-control-lg @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fa-solid fa-envelope me-1"></i> Email
                        </label>
                        <input type="email"
                               name="email"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mot de passe --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fa-solid fa-lock me-1"></i> Mot de passe
                        </label>
                        <input type="password"
                               name="password"
                               class="form-control form-control-lg @error('password') is-invalid @enderror"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirmation --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fa-solid fa-lock me-1"></i> Confirmation du mot de passe
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control form-control-lg"
                               required>
                    </div>

                    {{-- Rôle (passé depuis le contrôleur) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fa-solid fa-user-shield me-1"></i> Rôle
                        </label>
                        <select name="role_id"
                                class="form-select form-select-lg @error('role_id') is-invalid @enderror"
                                required>
                            <option value="">Choisir un rôle</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-register mb-3">
                        <i class="fa-solid fa-user-plus me-2"></i>S'inscrire
                    </button>

                    <div class="auth-links">
                        <span class="text-muted small">Déjà un compte ?</span>
                        <a href="{{ route('login') }}">Se connecter</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .auth-card {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        animation: fadeIn 0.5s ease-out;
    }
    .auth-card .card-header {
        background: linear-gradient(135deg, var(--primary-navy), var(--secondary-burgundy));
        border-bottom: none;
    }
    .btn-register {
        background: linear-gradient(135deg, var(--primary-navy), var(--secondary-burgundy));
        width: 100%;
        padding: 12px;
        font-size: 17px;
        border-radius: 10px;
        border: none;
        color: #fff;
        cursor: pointer;
        transition: 0.25s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-register:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 25px rgba(10,26,68,0.4);
    }
    .auth-links a {
        color: var(--primary-navy);
        font-weight: 600;
        text-decoration: none;
    }
    .auth-links a:hover {
        color: var(--secondary-burgundy);
        text-decoration: underline;
    }
    @keyframes fadeIn {
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
@endsection
