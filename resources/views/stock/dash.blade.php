@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard Magasinier</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h5>Total équipements</h5>
            <h3>{{ $equipmentsCount }}</h3>
        </div>
    </div>

    <h4>Derniers mouvements</h4>
    <ul class="list-group">
        @foreach($lastMovements as $movement)
            <li class="list-group-item">
                {{ $movement->type }} - {{ $movement->quantity }}
            </li>
        @endforeach
    </ul>
</div>
@endsection
