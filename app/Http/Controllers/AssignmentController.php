<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index() {
        $assignments = Assignment::with('equipment','user')->latest()->get();
        return view('assignments.index', compact('assignments'));
    }

    public function create() {
        $equipments = Equipment::all();
        $users = User::all();
        return view('assignments.create', compact('equipments','users'));
    }

    public function store(Request $request) {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'user_id' => 'nullable|exists:users,id',
            'location' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:5000',
        ]);

        Assignment::create([
            'equipment_id' => $request->equipment_id,
            'user_id' => $request->user_id,
            'location' => $request->location,
            'note' => $request->note,
            'assigned_at' => now(),
        ]);

        // ✅ CORRIGÉ : admin. prefix
        return redirect()->route('admin.assignments.index')->with('success','Affectation enregistrée !');
    }

    public function return(Assignment $assignment) {
        $assignment->status = 'retourné';
        $assignment->save();

        // ✅ DÉJÀ BON
        return redirect()->route('admin.assignments.index')->with('success','Équipement retourné !');
    }
}
