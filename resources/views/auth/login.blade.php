@extends('layouts.app')

@section('content')
<div class="auth-container">

    <div class="card auth-card">
        <h2 class="title">
            <i class="fa-solid fa-right-to-bracket"></i> Connexion
        </h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="form-group mb-3">
                <label class="label">
                    <i class="fa-solid fa-envelope"></i> Email
                </label>
                <input type="email" name="email" class="form-control input-custom" required>
                @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group mb-3">
                <label class="label">
                    <i class="fa-solid fa-lock"></i> Mot de passe
                </label>
                <input type="password" name="password" class="form-control input-custom" required>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Se connecter
            </button>

        </form>
    </div>

</div>
<style >
    /* ============================= */
/* LABELS ET INPUTS LOGIN */
/* ============================= */
.auth-card label {
    font-weight: 600;
    color: var(--blue-navy);
    display: block;
    margin-bottom: 5px;
}

.auth-card input {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #d0d4dd;
    border-radius: 8px;
    background: var(--input-bg);
    font-size: 15px;
    transition: 0.25s;
}

.auth-card input:focus {
    border-color: var(--blue-navy);
    box-shadow: 0 0 5px rgba(10,26,68,0.3);
    outline: none;
}

/* ============================= */
/* BOUTON LOGIN */
/* ============================= */
.btn-login {
    background: var(--burgundy);
    width: 100%;
    padding: 12px;
    font-size: 17px;
    border-radius: 8px;
    border: none;
    color: white;
    cursor: pointer;
    transition: 0.25s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-login:hover {
    background: #58081f;
}

.btn-login i {
    margin-right: 8px;
}

/* ============================= */
/* LIENS CONNEXION / INSCRIPTION */
/* ============================= */
.auth-links {
    text-align: center;
    margin-bottom: 20px;
}

.auth-links a {
    color: var(--blue-navy);
    text-decoration: none;
    font-weight: 500;
    margin: 0 10px;
    transition: 0.25s;
}

.auth-links a:hover {
    color: var(--burgundy);
}
</style >
@endsection