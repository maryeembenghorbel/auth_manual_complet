@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Mouvements de stock</h2>

    <a href="{{ route('stock.movements.create') }}" class="btn btn-primary mb-3">
        Nouveau mouvement
    </a>

    <table class="table">
        <thead>
            <tr>
                <th>Équipement</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Par</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $movement)
            <tr>
                <td>{{ $movement->equipment->name }}</td>
                <td>
                    <span class="badge bg-{{ $movement->type === 'in' ? 'success' : 'danger' }}">
                        {{ strtoupper($movement->type) }}
                    </span>
                </td>
                <td>{{ $movement->quantity }}</td>
                <td>{{ $movement->user->name }}</td>
                <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
