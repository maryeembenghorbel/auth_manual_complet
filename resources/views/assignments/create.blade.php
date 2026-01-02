@extends('layouts.app')

@section('content')
<h2>Nouvelle Affectation</h2>

<form action="{{ route('assignments.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Équipement</label>
        <select name="equipment_id" class="form-control" required>
            @foreach($equipments as $eq)
            <option value="{{ $eq->id }}">{{ $eq->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Employé / Service</label>
        <select name="user_id" class="form-control">
            <option value="">Aucun</option>
            @foreach($users as $u)
            <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Location (optionnel)</label>
        <input type="text" name="location" class="form-control">
    </div>

    <button class="btn btn-success">Enregistrer</button>
</form>
@endsection
