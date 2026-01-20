@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Scan de Vulnérabilité (Nmap)</h2>

    <div class="card p-4 shadow">
        <form method="POST" action="{{ route('scan.run') }}">
            @csrf

            <label class="form-label fw-bold">Adresse IP à scanner :</label>
            <input type="text" name="ip" class="form-control" placeholder="Ex : 192.168.1.10" required>

            <button type="submit" class="btn btn-primary mt-3 w-100">
                Lancer le scan
            </button>
        </form>
    </div>

    @isset($formatted)
    <div class="card mt-4 p-4 shadow">
        <h4>Résultat du scan pour : <span class="text-primary">{{ $ip }}</span></h4>

        <p><strong>Statut :</strong> {{ $formatted['status'] }}</p>

        <h5>Ports ouverts :</h5>
        @if(count($formatted['open_ports']) > 0)
            <ul class="list-group">
                @foreach($formatted['open_ports'] as $port)
                    <li class="list-group-item">
                        🔹 Port {{ $port['port'] }} : {{ $port['service'] }}
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Aucun port ouvert détecté.</p>
        @endif

        <h5 class="mt-3">Résultat brut :</h5>
        <pre class="bg-dark text-white p-3 rounded">{{ $output }}</pre>
    </div>
    @endisset
</div>
@endsection
