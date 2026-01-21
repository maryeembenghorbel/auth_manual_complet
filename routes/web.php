<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ViewerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\stock\StockEquipmentController;
use App\Http\Controllers\stock\StockMovementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\StockController; 

Route::get('/', function () { 
    return redirect()->route('login'); 
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/home', [AuthController::class, 'home'])->name('home');
});

// Dashboards par rôle


// =============================================================================
// ADMIN - UNIQUEMENT ROLE ADMIN
// =============================================================================
Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', function() {
            return view('admin.dash');
        })->name('dashboard');
        
        // Users CRUD
        Route::resource('users', UserController::class);
        
        // Equipments CRUD
        Route::resource('equipments', EquipmentController::class);
        
        // Assignments ADMIN
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
        Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
        Route::patch('/assignments/{assignment}/return', [AssignmentController::class, 'return'])->name('assignments.return');
    });

// Stock (Technicien / Magasinier)
Route::middleware(['auth', 'role:Magasinier'])
    ->prefix('stock')
    ->name('stock.')
    ->group(function () {

        // Dashboard magasinier
        Route::get('/dashboard', [\App\Http\Controllers\stock\DashboardController::class, 'index'])
            ->name('dashboard');

        // Consultation équipements (lecture seule)
        Route::get('/equipments', [StockEquipmentController::class, 'index'])
            ->name('equipments.index');

        // Gestion du stock
        Route::get('/movements', [StockMovementController::class, 'index']) ->name('movements.index');
        Route::get('/movements/create', [StockMovementController::class, 'create']) ->name('movements.create'); 
        Route::post('/movements', [StockMovementController::class, 'store']) ->name('movements.store');

        // Affectations
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
        Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
        Route::patch('/assignments/{assignment}/return', [AssignmentController::class, 'return'])->name('assignments.return');
    });

// =============================================================================
// SCAN - Analyste
// =============================================================================
Route::middleware(['auth'])->group(function() {
    Route::get('/scan/dashboard', function() {
        return view('scan.dash');
    })->name('scan.dashboard');
    Route::get('/scan', [ScanController::class, 'scanForm'])->name('scan.form');
    Route::post('/scan', [ScanController::class, 'runScan'])->name('scan.run');
});

// Viewer / Consultant
Route::middleware(['auth'])->prefix('viewer')->name('viewer.')->group(function () {
    Route::get('/dashboard', [ViewerController::class, 'index'])->name('dashboard');
    
    Route::get('/equipements', [ViewerController::class, 'equipements'])->name('equipement');

    Route::get('/warehouse', [ViewerController::class, 'warehouseVisual'])->name('warehouse');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/stocks/create', [StockController::class, 'create'])->name('stocks.create');
    Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');
});



