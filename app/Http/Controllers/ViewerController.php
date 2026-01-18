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


    public function equipements(Request $request)
    {
        $query = Equipment::query();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%")
                  ->orWhere('model', 'LIKE', "%{$search}%")
                  ->orWhere('serial_number', 'LIKE', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($state = $request->input('state')) {
            $query->where('state', $state);
        }

        $types = Equipment::select('type')->distinct()->pluck('type');

        $equipments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('viewer.equipement', compact('equipments', 'types'));
    }
}