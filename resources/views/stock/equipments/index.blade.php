@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Équipements</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipments as $equipment)
            <tr>
                <td>{{ $equipment->name }}</td>
                <td>{{ $equipment->type }}</td>
                <td>{{ $equipment->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
