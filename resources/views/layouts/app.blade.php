<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIAM - Secure Inventory & Asset Manager')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')

    <style>
        :root {
            --primary-navy: #0A1A44;
            --secondary-burgundy: #7A0A2A;
            --accent-gold: #F4C430;
            --light-bg: #f8fafc;
            --card-bg: #ffffff;
            --sidebar-width: 280px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--light-bg) 0%, #e2e8f0 100%);
            margin: 0;
            overflow-x: hidden;
        }

        .navbar-top {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: var(--primary-navy);
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(10,26,68,0.3);
            z-index: 1030;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            top: 80px;
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - 80px);
            background: var(--card-bg);
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
            z-index: 1020;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .nav-link.active {
            background-color: #0d6efd !important;
            color: white !important;
            border-radius: 5px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            padding: 2rem;
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); width: 100%; position: relative; top: 0; height: auto; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
        }
    </style>
</head>
<body>
<nav class="navbar-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between w-100">
            <a href="{{ route('home') }}" class="navbar-brand">
                <i class="fas fa-shield-alt"></i> SIAM
            </a>

            <div class="d-flex align-items-center gap-3">
                @auth
                    <div class="dropdown">
                        <a href="#" class="text-white text-decoration-none d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                            <span class="fw-semibold">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fas fa-home me-2"></i> Accueil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger"><i class="fas fa-right-from-bracket me-2"></i> Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Connexion</a>
                @endauth
                <button class="btn text-white d-lg-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars fs-4"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

@auth
    @php $roleName = strtolower(auth()->user()->role->name ?? ''); @endphp
    
    <nav id="sidebar" class="sidebar border-end">
        <ul class="nav flex-column p-3">
            
            {{-- Menu pour Viewer / Consultant --}}
            @if ($roleName === 'consultant')
                <li class="nav-item mb-1">
                    <a href="{{ route('viewer.equipement') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('viewer.equipement') ? 'active' : 'text-dark' }}">
                        <i class="fas fa-desktop me-2"></i> Équipements
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('viewer.warehouse') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('viewer.warehouse') ? 'active' : 'text-dark' }}">
                        <i class="fas fa-map-marked-alt me-2"></i> Plan entrepôt
                    </a>
                </li>
            @endif

            {{-- Menu Admin --}}
            @if($roleName == 'admin')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-dark' }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard Admin
                    </a>
                </li>

                <li class="nav-item mt-3"><h6 class="text-muted px-2">Gestion</h6></li>

                <li class="nav-item mb-1">
                    <a href="{{ route('admin.users.index') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'active' : 'text-dark' }}">
                        <i class="fas fa-users-cog me-2"></i> Utilisateurs
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('admin.equipments.index') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.equipments.*') ? 'active' : 'text-dark' }}">
                        <i class="fas fa-desktop me-2"></i> Équipements
                    </a>
                </li>

                 <li class="nav-item mb-1">
                    <a href="{{ route('stocks.index') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('stocks.*') ? 'active' : 'text-dark' }}">
                        <i class="fas fa-boxes-stacked me-2"></i> Stock
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('admin.assignments.index') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.assignments.*') ? 'active' : 'text-dark' }}">
                        <i class="fas fa-exchange-alt me-2"></i> Affectations
                    </a>
                    </li>
            @endif



            {{-- Menu Magasinier --}}
            @if ($roleName === 'magasinier')
                <li class="nav-item mb-1">
                    <a href="{{ route('stock.dashboard') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('stock.dashboard') ? 'active' : 'text-dark' }}">
                        <i class="fas fa-warehouse me-2"></i> Dashboard Stock
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('stock.movements.index') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('stock.movements.*') ? 'active' : 'text-dark' }}">
                        <i class="fas fa-exchange-alt me-2"></i> Mouvements
                    </a>
                </li>
            @endif

            {{-- Menu Analyste --}}
            @if($roleName==='analyste')
                <li class="nav-item mb-1">
                    <a href="{{ route('analyst.dashboard') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('analyst.dashboard') ? 'active' : 'text-dark' }}">
                        <i class="bi bi-speedometer2"></i>Dashboard
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('analyst.scans') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('analyst.scans') ? 'active' : 'text-dark' }}">
                        <i class="bi bi-search"></i>Scans
                    </a>
                </li>
        
                <li class="nav-item mb-1">
                    <a href="{{ route('analyst.reports') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('analyst.reports') ? 'active' : 'text-dark' }}">
                        <i class="bi bi-file-earmark-text"></i>Rapports
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endauth

<main class="main-content" style="@guest margin-left: 0; @endguest">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li><i class="fas fa-exclamation-triangle me-1"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
    }
</script>
@stack('scripts')
@yield('scripts')
</body>
</html>