<!-- resources/views/stock/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-5 text-center">
    <!-- Gros message -->
    <h1 class="display-3 fw-bold text-primary">
        Bonjour, {{ Auth::user()->name }} !
    </h1>
    <h2 class="text-secondary mb-4">
        Rôle : {{ Auth::user()->role->name ?? 'Non défini' }}
    </h2>
    <p class="fs-4 text-muted">
        Bienvenue sur votre tableau de bord. Gérez vos stocks et suivez les mouvements facilement.
    </p>
</div>
@endsection
