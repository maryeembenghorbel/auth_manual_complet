@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Nouveau mouvement</h2>

    <form method="POST" action="{{ route('stock.movements.store') }}">
        @csrf

        <div class="mb-3">
            <label>Équipement</label>
            <select name="equipment_id" class="form-control" required>
                @foreach($equipments as $equipment)
                    <option value="{{ $equipment->id }}">
                        {{ $equipment->name }} (Stock: {{ $equipment->quantity }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="in">Entrée</option>
                <option value="out">Sortie</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Quantité</label>
            <input type="number" name="quantity" class="form-control" min="1" required>
        </div>

        <button class="btn btn-success">Valider</button>
    </form>
</div>
@endsection
