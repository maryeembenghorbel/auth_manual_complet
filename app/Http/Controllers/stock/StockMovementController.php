<?php

namespace App\Http\Controllers\stock;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Equipment;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with('equipment', 'user')
            ->latest()
            ->get();

        return view('stock.movements.index', compact('movements'));
    }

    public function create()
    {
        $equipments = Equipment::all();
        return view('stock.movements.create', compact('equipments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'type' => 'required|in:entry,exit',
            'quantity' => 'required|integer|min:1',
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);

        // Si sortie, vérifier stock
        if ($request->type === 'exit' && $equipment->quantity < $request->quantity) {
            return back()->withErrors('Stock insuffisant');
        }

        // Mise à jour du stock
        if ($request->type === 'entry') {
            $equipment->quantity += $request->quantity;
        } else {
            $equipment->quantity -= $request->quantity;
        }

        $equipment->save();

        // ✅ CORRIGÉ ligne 49
        StockMovement::create([
            'equipment_id' => $equipment->id,
            'type' => $request->type === 'entry' ? 'entrée' : 'sortie',  // ← ÇA !
            'quantity' => $request->quantity,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('stock.movements.index')
            ->with('success', 'Mouvement enregistré');
    }
}
