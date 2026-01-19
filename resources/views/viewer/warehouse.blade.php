@extends('layouts.app')

@section('title', 'Visualisation Entrepôt')

@push('styles')
<style>
    .warehouse-grid-container {
        display: grid;
        
        grid-template-rows: repeat({{ $totalRows }}, 50px); 
        grid-template-columns: repeat({{ $totalCols }}, 50px); 
        gap: 12px; 
        justify-content: center; 
        padding: 30px;
        background-color: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #dee2e6;
        overflow-x: auto; 
    }

    .storage-slot {
        position: relative;
        width: 50px;
        height: 50px;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .storage-slot:hover {
        transform: scale(1.1);
    }


    .slot-free {
        border: 2px solid #ffc107; 
        background-color: transparent;
        color: #ffc107;
    }
    .slot-free i { opacity: 0.5; }

    .slot-occupied {
        background-color: #e9ecef; 
        border: 2px solid #adb5bd; 
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Plan de l'entrepôt</h2>
        <h4 class="text-muted mb-3">{{ $freeSlots }} places libres</h4>
    </div>


    <div class="row justify-content-center">
       <div class="col-lg-10">

            <div class="warehouse-grid-container">

                @foreach($locations as $location)
                    @php
                        $isOccupied = $location->isOccupied();
                        $statusClass = $isOccupied ? 'slot-occupied' : 'slot-free';
                        $iconClass = $isOccupied ? 'fa-box' : 'fa-check';
                        
                        $tooltipText = $location->name;
                        if($isOccupied) {
                            $tooltipText .= " : OCP par " . $location->equipment->name;
                        } else {
                             $tooltipText .= " : Libre";
                        }
                    @endphp

                    <div class="storage-slot {{ $statusClass }}"
                         style="grid-row: {{ $location->grid_row_index }}; grid-column: {{ $location->grid_column_index }};"
                         data-bs-toggle="tooltip" 
                         data-bs-placement="top" 
                         title="{{ $tooltipText }}">
                        
                        {{-- Optional Icon --}}
                        <i class="fas {{ $iconClass }}"></i>
                    </div>
                @endforeach

                </div>

            <div class="d-flex justify-content-center gap-5 mt-5 small text-uppercase fw-bold text-muted">
                <div class="d-flex align-items-center">
                    <div class="storage-slot slot-free me-2" style="width: 30px; height: 30px;"></div> 
                    Places libres
                </div>
                <div class="d-flex align-items-center">
                    <div class="storage-slot slot-occupied me-2" style="width: 30px; height: 30px; transform: none;">
                        <i class="fas fa-box fa-xs"></i>
                    </div> 
                    Places occupées
                </div>
            </div>

       </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush