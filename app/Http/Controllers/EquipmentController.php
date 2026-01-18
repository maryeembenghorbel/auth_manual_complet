<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::latest()->paginate(10);
        return view('stock.equipments.index', compact('equipments'));
    }
}
