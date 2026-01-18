<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Equipment;

class StockEquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::all();
        return view('stock.equipments.index', compact('equipments'));
    }
}
