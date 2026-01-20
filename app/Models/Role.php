<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // <-- il faut importer le modèle User

class Role extends Model
{
    protected $fillable = ['name']; // Nom du rôle : Admin, Technicien, etc.

    // Un rôle peut avoir plusieurs utilisateurs
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
