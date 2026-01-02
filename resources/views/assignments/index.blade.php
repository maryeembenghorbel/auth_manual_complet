@extends('layouts.app')

@section('content')
<h2>Affectations</h2>
<a href="{{ route('assignments.create') }}" class="btn btn-primary mb-3">Nouvelle Affectation</a>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Équipement</th>
            <th>Employé / Service</th>
            <th>Location</th>
            <th>Statut</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assignments as $a)
        <tr>
            <td>{{ $a->equipment->name }}</td>
            <td>{{ $a->user?->name ?? 'N/A' }}</td>
            <td>{{ $a->location ?? 'N/A' }}</td>
            <td>{{ ucfirst($a->status) }}</td>
            <td>{{ $a->created_at->format('d/m/Y') }}</td>
            <td>
                @if($a->status == 'attribué')
                <form action="{{ route('assignments.return',$a->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-sm btn-danger">Retour</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
