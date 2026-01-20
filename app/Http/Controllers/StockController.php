<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    // Liste des mouvements
    public function index()
    {
        $movements = StockMovement::with('equipment','user')->latest()->get();
        return view('stocks.index', compact('movements'));
    }

    // Formulaire ajouter/retirer stock
    public function create()
    {
        $equipments = Equipment::all();
        return view('stocks.create', compact('equipments'));
    }

    // Stocker le mouvement ✅ CORRIGÉ
    public function store(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'type' => 'required|in:entry,exit',
            'quantity' => 'required|integer|min:1',
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);

        // Vérification si c'est une sortie et que la quantité est suffisante
        if ($request->type == 'exit' && $request->quantity > $equipment->quantity) {
            return redirect()->back()->with('error', 
                "Impossible de retirer {$request->quantity} unités. Stock disponible : {$equipment->quantity}."
            );
        }

        // Mettre à jour la quantité
        if ($request->type == 'entry') {
            $equipment->quantity += $request->quantity;
        } else {
            $equipment->quantity -= $request->quantity;
        }
        $equipment->save();

        StockMovement::create([
            'equipment_id' => $equipment->id,
            'type' => $request->type == 'entry' ? 'entrée' : 'sortie',  // ← ÇA !
            'quantity' => $request->quantity,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('stocks.index')->with('success', 'Mouvement enregistré !');
    }
}
