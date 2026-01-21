<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();

        // filtres par type / état / recherche
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('brand', 'like', "%$search%")
                  ->orWhere('serial_number', 'like', "%$search%");
            });
        }

        $equipments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.equipments.index', compact('equipments'));
    }

    public function create()
    {
        return view('admin.equipments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'brand'         => 'nullable|string|max:255',
            'model'         => 'nullable|string|max:255',
            'ip_address'    => 'nullable|ip',
            'type'          => 'required|in:PC,Écran,Routeur,Switch,Imprimante,Autre',
            'serial_number' => 'required|string|max:255|unique:equipment,serial_number',
            'state'         => 'required|in:Neuf,En service,En panne,Maintenance,HS',
            'supplier'      => 'nullable|string|max:255',
            'quantity'      => 'required|integer|min:0',
            'price'         => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'warranty'      => 'nullable|date',
            'image'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('equipment', 'public');
        }

        Equipment::create($data);

        return redirect()
            ->route('admin.equipments.index')
            ->with('success', 'Matériel créé avec succès.');
    }

    public function edit(Equipment $equipment)
    {
        return view('admin.equipments.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'brand'         => 'nullable|string|max:255',
            'model'         => 'nullable|string|max:255',
            'ip_address'    => 'nullable|ip',
            'type'          => 'required|in:PC,Écran,Routeur,Switch,Imprimante,Autre',
            'serial_number' => 'required|string|max:255|unique:equipment,serial_number,' . $equipment->id,
            'state'         => 'required|in:Neuf,En service,En panne,Maintenance,HS',
            'supplier'      => 'nullable|string|max:255',
            'quantity'      => 'required|integer|min:0',
            'price'         => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'warranty'      => 'nullable|date',
            'image'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('equipment', 'public');
        }

        $equipment->update($data);

        return redirect()
            ->route('admin.equipments.index')
            ->with('success', 'Matériel mis à jour avec succès.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete(); 
        return back()->with('success', 'Matériel archivé (soft delete).');
    }
}
