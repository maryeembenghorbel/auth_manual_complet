@extends('layouts.app')

@section('content')
<div class="auth-container">

    <div class="card auth-card">
        <h2 class="title">
            <i class="fa-solid fa-user-plus"></i> Inscription
        </h2>

       <form action="{{ route('register') }}" method="POST">
    @csrf

    <!-- Nom -->
    <div class="form-group mb-3">
        <label class="label">
            <i class="fa-solid fa-user"></i> Nom
        </label>
        <input type="text" name="name" class="form-control input-custom" required>
        @error('name')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>

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

    <!-- Mot de passe -->
    <div class="form-group mb-3">
        <label class="label">
            <i class="fa-solid fa-lock"></i> Mot de passe
        </label>
        <input type="password" name="password" class="form-control input-custom" required>
        @error('password')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>

    <!-- Confirmation -->
    <div class="form-group mb-3">
        <label class="label">
            <i class="fa-solid fa-lock"></i> Confirmation
        </label>
        <input type="password" name="password_confirmation" class="form-control input-custom" required>
    </div>

    <!-- Rôle -->
    <div class="form-group mb-3">
        <label class="label">
            <i class="fa-solid fa-user-shield"></i> Rôle
        </label>
        <select name="role_id" class="form-control input-custom" required>
            <option value="" disabled selected>Choisir un rôle</option>
           @foreach(App\Models\Role::where('name', '!=', 'Admin')->get() as $role)
    <option value="{{ $role->id }}">{{ $role->name }}</option>
@endforeach

        </select>
        @error('role_id')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-register">
        <i class="fa-solid fa-user-plus"></i> S'inscrire
    </button>

</form>


@endsection
