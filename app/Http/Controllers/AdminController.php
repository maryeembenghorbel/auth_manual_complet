<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\User;
use App\Models\Assignment; // ou Assignment selon ton modèle
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Stats ÉQUIPEMENTS
        $totalEquipments = Equipment::count();
        $lowStock = Equipment::where('quantity', '<', 5)->where('quantity', '>', 0)->count();
        $inPancre = Equipment::whereIn('state', ['En panne', 'HS'])->count();
        $totalValue = Equipment::whereNotNull('price')->sum('price');

        // Stats USERS
        $totalUsers = User::count();
        $adminCount = User::whereHas('role', fn($q) => $q->where('name', 'Admin'))->count();
        $activeTechs = User::whereHas('role', fn($q) => $q->whereIn('name', ['Technicien', 'Magasinier']))
                           ->whereNotNull('email_verified_at')->count();
        $pendingUsers = User::whereNull('email_verified_at')->count();

        // Stats AFFECTATIONS
        $totalAssignments = Assignment::count();
        $inUse = Assignment::where('status', 'attribué')->count();
        $returnedThisMonth = Assignment::where('status', 'retourné')
                                      ->whereMonth('created_at', now()->month)->count();

        // Activité récente (simulée ou via logs)
        $recentActivities = [
            ['title' => 'Nouvel équipement ajouté', 'icon' => 'fas fa-desktop', 'time' => 'il y a 2h'],
            ['title' => 'Affectation PC à Ahmed', 'icon' => 'fas fa-exchange-alt', 'time' => 'hier'],
            ['title' => 'Utilisateur validé', 'icon' => 'fas fa-user-check', 'time' => '2 jours'],
        ];

        return view('admin.dashboard', compact(
            'totalEquipments', 'lowStock', 'inPancre', 'totalValue',
            'totalUsers', 'adminCount', 'activeTechs', 'pendingUsers',
            'totalAssignments', 'inUse', 'returnedThisMonth',
            'recentActivities'
        ));
    }
}

