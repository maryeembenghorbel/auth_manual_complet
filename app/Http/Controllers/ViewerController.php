<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment; 
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ViewerController extends Controller
{
    public function index()
    {
        $totalEquipments = DB::table('equipment')->count();
        $assignedEquipments = DB::table('assignments')->where('status', 'attribué')->count();
        $availableEquipments = $totalEquipments - $assignedEquipments;
        
        $equipments = DB::table('equipment')
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();

        return view('viewer.dash', compact('totalEquipments', 'assignedEquipments', 'availableEquipments', 'equipments'));
    }


    public function equipements()
{
    $equipments = Equipment::orderBy('created_at', 'desc')->paginate(10);

    return view('viewer.equipement', compact('equipments'));
}
}