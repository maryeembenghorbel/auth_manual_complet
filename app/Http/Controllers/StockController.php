<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with('equipment','user')->latest()->get();
        return view('stocks.index', compact('movements'));
    }

    public function create()
    {
        $equipments = Equipment::all();
        return view('stocks.create', compact('equipments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'type' => 'required|in:entry,exit',
            'quantity' => 'required|integer|min:1',
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);

        if ($request->type == 'exit' && $request->quantity > $equipment->quantity) {
            return redirect()->back()->with('error', 
                "Impossible de retirer {$request->quantity} unités. Stock disponible : {$equipment->quantity}."
            );
        }

        if ($request->type == 'entry') {
            $equipment->quantity += $request->quantity;
        } else {
            $equipment->quantity -= $request->quantity;
        }
        $equipment->save();

        StockMovement::create([
            'equipment_id' => $equipment->id,
            'type' => $request->type, // == 'entry' ? 'entrée' : 'sortie'
            'quantity' => $request->quantity,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('stocks.index')->with('success', 'Mouvement enregistré !');
    }
}