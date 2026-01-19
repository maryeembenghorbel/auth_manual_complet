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

        /* NAVBAR TOP */
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
        }

        /* SIDEBAR */
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

        .sidebar-header {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .sidebar-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            margin: 0 0 1rem 0;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            font-weight: 500;
        }

        .menu-link:hover, .menu-link.active {
            background: linear-gradient(90deg, var(--primary-navy), var(--secondary-burgundy));
            color: white;
            border-left-color: var(--accent-gold);
        }

        .menu-link i {
            font-size: 1.25rem;
            width: 2.25rem;
            text-align: center;
            margin-right: 0.75rem;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            padding: 2rem;
            transition: margin-left 0.3s ease;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-navy), var(--secondary-burgundy));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }

        .content-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
            .mobile-menu-toggle { display: block; }
        }

        @media (min-width: 993px) {
            .mobile-menu-toggle { display: none; }
        }
    </style>
</head>
<body>
  <!-- Navbar Top -->
<nav class="navbar-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between w-100">

            {{-- Logo / Titre --}}
            <a href="{{ route('home') }}" class="navbar-brand">
                <i class="fas fa-shield-alt"></i>
                SIAM
            </a>

            {{-- Zone droite : user / login + burger mobile --}}
            <div class="d-flex align-items-center gap-3">
                @auth
                    <div class="dropdown">
                        <a href="#"
                           class="text-white text-decoration-none d-flex align-items-center gap-2"
                           data-bs-toggle="dropdown">
                            <span class="avatar-circle d-flex align-items-center justify-content-center">
                                <i class="fas fa-user-shield"></i>
                            </span>
                            <span class="fw-semibold">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('home') }}">
                                    <i class="fas fa-home me-2"></i> Accueil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button class="dropdown-item fw-semibold text-danger">
                                        <i class="fas fa-right-from-bracket me-2"></i> Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-in-alt me-1"></i> Connexion
                    </a>
                @endauth

                <button class="mobile-menu-toggle d-md-none text-white" onclick="toggleSidebar()">
                    <i class="fas fa-bars fs-4"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

    </nav>
<!-- Sidebar (uniquement pour utilisateurs connectés) -->
@auth
    @php
        $roleName = strtolower(auth()->user()->role->name ?? '');
    @endphp
    
<nav id="sidebar" class="bg-light border-end vh-100 position-fixed">
    

    <ul class="nav flex-column sidebar-menu px-2">
        @if ($roleName === 'consultant' || $roleName === 'viewer')
            <li class="nav-item mb-1">
                <a href="{{ route('viewer.dashboard') }}" 
                class="nav-link d-flex align-items-center {{ request()->routeIs('viewer.dashboard') ? 'active bg-primary text-white rounded' : 'text-dark' }}">
                    <i class="fas fa-home me-2"></i>
                    Acceuil
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('viewer.equipement') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('viewer.equipement') ? 'active bg-primary text-white rounded' : 'text-dark' }}">
                    <i class="fas fa-desktop me-2"></i>
                    Équipements
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="{{ route('viewer.warehouse') }}" 
                class="nav-link d-flex align-items-center {{ request()->routeIs('viewer.warehouse') ? 'active bg-primary text-white rounded' : 'text-dark' }}">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    Plan entrepôt
                </a>
            </li>
    
    @else   
        <li class="nav-item mb-1">
            <a href="{{ route('home') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('home') ? 'active bg-primary text-white rounded' : 'text-dark' }}">
                <i class="fas fa-home me-2"></i>
                Accueil
            </a>
        </li>
    @endif
        

        @if($roleName == 'Admin')
            <!-- Dashboard Admin -->
            <li class="nav-item mb-1">
                <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active bg-primary text-white rounded' : 'text-dark' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard Admin
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="text-muted px-2">Administration</h6>
            </li>

            <!-- Gestion des utilisateurs -->
            <li class="nav-item mb-1">
                <a href="{{ route('admin.users.index') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'active bg-primary text-white rounded' : 'text-dark' }}">
                    <i class="fas fa-users-cog me-2"></i>
                    Utilisateurs
                </a>
            </li>
        @endif
        <!-- Gestion des équipements -->
@if($roleName == 'Admin')
<li class="nav-item mb-1">
    <a href="{{ route('admin.equipments.index') }}"
       class="nav-link d-flex align-items-center {{ request()->routeIs('admin.equipments.*') ? 'active bg-primary text-white rounded' : 'text-dark' }}">
        <i class="fas fa-desktop me-2"></i>
        Équipements
    </a>
</li>
@endif
</ul>
</nav>

     

<style>
    /* Sidebar responsive */
    @media (max-width: 992px) {
        #sidebar {
            position: relative;
            width: 100%;
            height: auto;
        }
    }

    .sidebar-menu .nav-link.active {
        font-weight: bold;
    }
</style>
@endauth

    <!-- Main Content -->
    <main class="main-content" style="@guest margin-left: 0; @endguest">
      

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
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

        // Auto-close sidebar on mobile after click
        document.querySelectorAll('.menu-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 993) {
                    document.getElementById('sidebar').classList.remove('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
