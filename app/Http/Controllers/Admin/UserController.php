<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Liste tous les utilisateurs

 public function index() {
    $users = User::with('role')
                 ->whereHas('role', function($q){
                     $q->where('name', '!=', 'Admin');
                 })
                 ->paginate(10); // pagination
    $roles = Role::where('name', '!=', 'Admin')->get(); // rôles sans Admin
    return view('admin.users.index', compact('users','roles'));
}



    // Formulaire création
    public function create() {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // Stocke le nouvel utilisateur
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé !');
    }

    // Formulaire édition
    public function edit(User $user) {
        $roles = Role::all();
        return view('admin.users.edit', compact('user','roles'));
    }

    // Met à jour
    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|confirmed|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        if($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour !');
    }

    // Supprimer
    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé !');
    }
}
