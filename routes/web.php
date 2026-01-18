<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\Admin\EquipmentController;
Route::get('/', function () { 
    return view('welcome'); 
});

// Routes pour invités
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Routes pour utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/home', [AuthController::class, 'home'])->name('home');
});

// Dashboards par rôle
// routes/web.php

// Admin
Route::get('/admin/dashboard', function() {
    return view('admin.dash'); // <--- point vers admin/dash.blade.php
})->name('admin.dashboard');

// Stock (Technicien / Magasinier)
Route::middleware(['auth', 'role:Magasinier'])
    ->prefix('stock')
    ->name('stock.')
    ->group(function () {

        // Dashboard magasinier
        Route::get('/dashboard', [\App\Http\Controllers\Stock\DashboardController::class, 'index'])
            ->name('dashboard');

        // Consultation équipements (lecture seule)
        Route::get('/equipments', [StockEquipmentController::class, 'index'])
            ->name('equipments.index');

        // Gestion du stock
        Route::get('/movements', [StockController::class, 'index'])->name('movements.index');
        Route::get('/movements/create', [StockController::class, 'create'])->name('movements.create');
        Route::post('/movements', [StockController::class, 'store'])->name('movements.store');

        // Affectations
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
        Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
        Route::patch('/assignments/{assignment}/return', [AssignmentController::class, 'return'])->name('assignments.return');
    });

// Scan (Analyste / Analyste Sécurité)
Route::get('/scan/dashboard', function() {
    return view('scan.dash'); // scan/dash.blade.php
})->name('scan.dashboard');

// Viewer / Consultant
Route::get('/viewer/dashboard', function() {
    return view('viewer.dash'); // viewer/dash.blade.php
})->name('viewer.dashboard');


use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', function () {
        return view('admin.dash'); // ou utiliser AdminController si tu veux
    })->name('dashboard');

    Route::resource('users', UserController::class);
});
// routes/web.php


Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function() {

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::get('/scan', [ScanController::class, 'scanForm'])->name('scan.form');
Route::post('/scan', [ScanController::class, 'runScan'])->name('scan.run');


Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('equipments', EquipmentController::class);
    });

use App\Http\Controllers\StockController;

Route::middleware(['auth'])->group(function () {
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/stocks/create', [StockController::class, 'create'])->name('stocks.create');
    Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');
});
use App\Http\Controllers\AssignmentController;

Route::middleware(['auth'])->group(function () {
    Route::get('/assignments', [AssignmentController::class,'index'])->name('assignments.index');
    Route::get('/assignments/create', [AssignmentController::class,'create'])->name('assignments.create');
    Route::post('/assignments', [AssignmentController::class,'store'])->name('assignments.store');
    Route::patch('/assignments/{assignment}/return', [AssignmentController::class,'return'])->name('assignments.return');
});
