@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard Magasinier</h2>

    {{-- KPI --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Total Équipements</h6>
                    <h3>{{ $equipmentsCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Total Entrées</h6>
                    <h3 class="text-success">{{ $totalEntry }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Total Sorties</h6>
                    <h3 class="text-danger">{{ $totalExit }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h6>Moyenne Entrée / Sortie</h6>
                    <h5>{{ number_format($avgEntry,1) }} / {{ number_format($avgExit,1) }}</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphique --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="mb-3">Entrées / Sorties de stock</h5>
            <canvas id="stockChart" height="100"></canvas>
        </div>
    </div>

    {{-- Derniers mouvements --}}
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = {!! json_encode($chartData->pluck('date')) !!};
    const entryData = {!! json_encode($chartData->pluck('total_entry')) !!};
    const exitData = {!! json_encode($chartData->pluck('total_exit')) !!};

    new Chart(document.getElementById('stockChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Entrées',
                    data: entryData,
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 128, 0, 0.1)',
                    tension: 0.2,
                    fill: true
                },
                {
                    label: 'Sorties',
                    data: exitData,
                    borderColor: 'red',
                    backgroundColor: 'rgba(255, 0, 0, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
