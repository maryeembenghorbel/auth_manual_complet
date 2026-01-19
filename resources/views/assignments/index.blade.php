@extends('layouts.app')

@section('page-title', 'Affectations')
@section('page-subtitle', 'Gestion des attributions d\'équipements aux employés')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                <i class="fas fa-users fs-3 text-primary"></i>
            </div>
            <div>
                <h2 class="fw-bold mb-0">Affectations</h2>
                <small class="text-muted">Gestion des attributions d'équipements</small>
            </div>
        </div>

        <a href="{{ route('assignments.create') }}" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus me-2"></i>Nouvelle affectation
        </a>
    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="fas fa-list me-2 text-primary"></i>
                    {{ $assignments->count() }} affectation(s)
                </h6>

                <input type="text" class="form-control form-control-sm w-25"
                       placeholder="Rechercher équipement ou employé...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Équipement</th>
                        <th class="text-center">Employé / Service</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Date</th>
                        <th>Note</th>
                        <th class="text-center pe-3">Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($assignments as $a)
                    <tr>
                        {{-- ÉQUIPEMENT --}}
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-desktop text-info"></i>
                                <div>
                                    <div class="fw-semibold">{{ $a->equipment->name }}</div>
                                    <small class="text-muted">
                                        SN: {{ $a->equipment->serial_number ?? 'N/A' }}
                                    </small>
                                </div>
                            </div>
                        </td>

                        {{-- USER --}}
                        <td class="text-center">
                            <span class="fw-semibold">
                                {{ $a->user?->name ?? 'Service général' }}
                            </span>
                        </td>

                        {{-- LOCATION --}}
                        <td class="text-center">
                            <span class="badge bg-warning-subtle text-warning">
                                <i class="fas fa-location-dot me-1"></i>
                                {{ $a->location ?? 'Bureau' }}
                            </span>
                        </td>

                        {{-- STATUS --}}
                        <td class="text-center">
                            @if($a->status === 'attribué')
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Attribué
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-undo me-1"></i>Retourné
                                </span>
                            @endif
                        </td>

                        {{-- DATE --}}
                        <td class="text-center">
                            <div class="fw-semibold">{{ $a->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $a->created_at->format('H:i') }}</small>
                        </td>

                        {{-- NOTE --}}
                        <td>
                            @if($a->note)
                                <span data-bs-toggle="tooltip" title="{{ $a->note }}">
                                    {{ \Illuminate\Support\Str::limit($a->note, 60) }}
                                </span>
                            @else
                                <span class="text-muted fst-italic">—</span>
                            @endif
                        </td>

                        {{-- ACTION --}}
                        <td class="text-center pe-3">
                            @if($a->status === 'attribué')
                                <form method="POST" action="{{ route('assignments.return', $a->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Confirmer le retour ?')">
                                        <i class="fas fa-box-arrow-in-right"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-success fw-semibold">
                                    <i class="fas fa-check-double"></i>
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fs-1 d-block mb-3 opacity-50"></i>
                            Aucune affectation enregistrée
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Tooltips --}}
<script>
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el)
})
</script>
@endsection
