@extends('layouts.app')

@section('page-title', 'Gestion des Utilisateurs')
@section('page-subtitle', 'Administration complète des comptes')

@section('content')
<div class="row g-4">
    {{-- Messages flash --}}
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    {{-- Header avec bouton Ajouter --}}
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title mb-1"><i class="fas fa-users-cog me-2"></i>Utilisateurs</h1>
                <p class="text-muted mb-0">Gérez les comptes, rôles et permissions</p>
            </div>
            <button class="btn btn-primary px-4 py-2 shadow-sm" id="btn-add-user">
                <i class="fas fa-user-plus me-2"></i>Ajouter
            </button>
        </div>
    </div>

    {{-- Formulaire ajout (slide-in card) --}}
    <div class="col-lg-8 col-xl-7" id="form-add-user" style="display:none;">
        <div class="content-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-bold text-white">
                    <i class="fas fa-user-plus me-2"></i>Nouvel utilisateur
                </h5>
                <button class="btn-close btn-close-white" id="btn-cancel" type="button"></button>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Nom complet</label>
                            <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Mot de passe</label>
                            <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Confirmation</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Rôle</label>
                            <select name="role_id" class="form-select form-select-lg @error('role_id') is-invalid @enderror" required>
                                <option value="">Choisir un rôle</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-check me-2"></i>Créer utilisateur
                        </button>
                        <button type="button" class="btn btn-outline-secondary px-4" id="btn-cancel-form">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tableau utilisateurs --}}
    <div class="col-12">
        <div class="content-card">
            <div class="card-header d-flex justify-content-between align-items-center">
    
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control form-control-sm border-start-0 bg-transparent" 
                           placeholder="Rechercher..." id="search-users">
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="users-table">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="border-top-0 py-3"><i class="fas fa-user me-1"></i>Nom</th>
                                <th class="border-top-0 py-3"><i class="fas fa-envelope me-1"></i>Email</th>
                                <th class="border-top-0 py-3"><i class="fas fa-user-tag me-1"></i>Rôle</th>
                                <th class="border-top-0 py-3 text-center"><i class="fas fa-cogs me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr class="user-row">
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>
                                    <i class="fas fa-envelope text-muted me-1"></i>
                                    {{ $user->email }}
                                </td>
                                <td>
                                    @if($user->role)
                                        @php $roleName = $user->role->name @endphp
                                        <span class="badge fs-6 px-3 py-2 fw-semibold rounded-pill
                                            @if($roleName === 'Admin') bg-danger text-white shadow-sm
                                            @elseif($roleName === 'Technicien' || $roleName === 'Magasinier') bg-warning text-dark shadow-sm
                                            @elseif($roleName === 'Analyste') bg-info text-white shadow-sm
                                            @else bg-secondary text-white shadow-sm @endif">
                                            {{ ucfirst($roleName) }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted px-3 py-2">Non assigné</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="btn btn-outline-primary tooltip-btn" 
                                           title="Modifier {{ $user->name }}" data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                              class="d-inline delete-form" style="display:inline-block;">
                                            @csrf @method('DELETE')
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline delete-user-form">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-outline-danger" 
        onclick="confirmDelete(this)" title="Supprimer {{ $user->name }}">
        <i class="fas fa-trash-alt"></i>
    </button>
</form>

                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-0">Aucun utilisateur trouvé</p>
                                    <small>Commencez par ajouter le premier administrateur</small>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($users->hasPages())
            <div class="card-footer bg-transparent border-0 py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted">
                        Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
                    </div>
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.content-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.content-card:hover { transform: translateY(-2px); }
.badge { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.tooltip-btn { transition: all 0.2s ease; }
.tooltip-btn:hover { transform: scale(1.1) !important; }
.table-hover tbody tr:hover { background-color: rgba(10,26,68,0.02) !important; }
#form-add-user { animation: slideInRight 0.4s ease-out; }
@keyframes slideInRight { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle formulaire
    document.getElementById('btn-add-user').addEventListener('click', function() {
        document.getElementById('form-add-user').style.display = 'block';
        document.getElementById('form-add-user').scrollIntoView({ behavior: 'smooth' });
    });
    
    document.getElementById('btn-cancel').addEventListener('click', hideForm);
    document.getElementById('btn-cancel-form').addEventListener('click', hideForm);
    
    function hideForm() {
        document.getElementById('form-add-user').style.display = 'none';
    }

    // Recherche en temps réel
    document.getElementById('search-users').addEventListener('input', function(e) {
        const search = e.target.value.toLowerCase();
        document.querySelectorAll('.user-row').forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(search) ? '' : 'none';
        });
    });

    // Tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Validation HTML5
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});

function confirmDelete(button) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Voulez-vous vraiment supprimer cet utilisateur ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}
</script>

@endpush
