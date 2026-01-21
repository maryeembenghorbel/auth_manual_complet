<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class AnalystDashboardController extends Controller
{
    /**
     * Affiche le dashboard analyste
     */
    public function dashboard()
    {
        $equipments = Equipment::all();

        return view('analyst.dashboard', compact('equipments'));
    }
}