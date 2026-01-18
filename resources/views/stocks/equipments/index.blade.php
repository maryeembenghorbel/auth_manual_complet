@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Équipements en stock</h4>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>État</th>
                <th>Quantité</th>
                <th>Alerte</th>
            </tr>
        </thead>
        <tbody>
        @foreach($equipments as $e)
            <tr>
                <td>{{ $e->name }}</td>
                <td>{{ $e->type }}</td>
                <td>{{ $e->state }}</td>
                <td>{{ $e->quantity }}</td>
                <td>
                    @if($e->quantity <= 5)
                        <span class="badge bg-danger">Stock faible</span>
                    @else
                        <span class="badge bg-success">OK</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $equipments->links() }}
</div>
@endsection
