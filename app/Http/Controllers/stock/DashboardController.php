<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
{
    // Déjà existant
    $equipmentsCount = Equipment::count();
    $lastMovements = StockMovement::latest()->take(5)->get();

    // Nouveaux calculs (entry / exit)
    $totalEntry = StockMovement::where('type', 'entry')->sum('quantity');
    $totalExit = StockMovement::where('type', 'exit')->sum('quantity');

    $avgEntry = StockMovement::where('type', 'entry')->avg('quantity') ?? 0;
    $avgExit = StockMovement::where('type', 'exit')->avg('quantity') ?? 0;

    // Données graphique (par jour)
    $chartData = StockMovement::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw("SUM(CASE WHEN type='entry' THEN quantity ELSE 0 END) as total_entry"),
            DB::raw("SUM(CASE WHEN type='exit' THEN quantity ELSE 0 END) as total_exit")
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    return view('stock.dash', compact(
        'equipmentsCount',
        'lastMovements',
        'totalEntry',
        'totalExit',
        'avgEntry',
        'avgExit',
        'chartData'
    ));
}
}
