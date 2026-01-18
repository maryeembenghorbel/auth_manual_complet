<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        return view('stock.dash', [
            'equipmentsCount' => Equipment::count(),
            'lastMovements' => StockMovement::latest()->take(5)->get(),
        ]);
    }
}
