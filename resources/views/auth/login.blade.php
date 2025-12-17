@extends('layouts.app')

@section('page-title', 'Connexion')
@section('page-subtitle', 'Accédez à la plateforme SIAM')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 100px);">
    <div class="col-md-6 col-lg-4">
        <div class="content-card auth-card border-0">
            <div class="card-header text-center">
                <h5 class="mb-0 fw-bold text-white">
                    <i class="fa-solid fa-right-to-bracket me-2"></i>Connexion
                </h5>
            </div>

            <div class="card-body p-4">
                {{-- Message d’erreur global --}}
                @if ($errors->has('email'))
                    <div class="alert alert-danger border-0 shadow-sm mb-3">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        Identifiants invalides, veuillez réessayer.
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="needs-validation" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">
                            <i class="fa-solid fa-envelope me-1"></i> Email
                        </label>
                        <input type="email"
                               name="email"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               required
                               autofocus>
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

                    {{-- Se souvenir de moi --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login mb-3">
                        <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Se connecter
                    </button>

                    <div class="auth-links">
                        <span class="text-muted small">Pas de compte ?</span>
                        <a href="{{ route('register') }}">Créer un compte</a>
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
    .btn-login {
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
    .btn-login:hover {
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
