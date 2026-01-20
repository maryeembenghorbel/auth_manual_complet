<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // --- INSCRIPTION ---
    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
      $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|max:255|unique:users',
    'password' => 'required|string|confirmed|min:8',
    'role_id' => 'required|exists:roles,id',
]);

      

      $user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => bcrypt($request->password),
    'role_id' => $request->role_id,
]);


        return redirect()->route('login')->with('success', 'Compte créé !');
    }

    // --- CONNEXION ---
    public function showLogin() {
        return view('auth.login');
    }

   public function login(Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Redirection selon rôle
        $user = Auth::user(); // récupère l'utilisateur connecté
        return $this->redirectByRole($user);
    }

    return back()->withErrors(['email' => 'Identifiants incorrects.']);
}
private function redirectByRole($user)
{
    $user->load('role'); // charge la relation

    $roleName = $user->role->name;

    switch ($roleName) {
        case 'Admin':
            return redirect()->route('admin.dashboard');
        case 'Technicien':
        case 'Magasinier':
            return redirect()->route('stock.dashboard');
       case 'Analyste':
       case 'Analyste Sécurité':
    return redirect()->route('analyst.dashboard'); // <--- corrigé

        case 'Consultant':
        default:
            return redirect()->route('viewer.dashboard');
    }
}
public function index()
{
    return view('admin.users.index', [
        'users' => User::with('role')->get(),
        'roles' => Role::all()
    ]);
}





    // --- DECONNEXION ---
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

  // --- PAGE D'ACCUEIL APRÈS LOGIN ---
public function home() {
    $message = "Bienvenue sur votre page !"; // ton message simple
    return view('home', compact('message'));
}

}
