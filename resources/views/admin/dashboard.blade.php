@extends("layouts.app")

@section("title", "Dashboard Admin")

@section("content")
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __("Tableau de bord Administrateur") }}</span>
                    <div class="btn-group">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-user-plus"></i> {{ __("Nouvel Utilisateur") }}
                        </a>
                        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm ms-2">
                            <i class="fas fa-plus-circle"></i> {{ __("Nouveau Projet") }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session("status"))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session("status") }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase">Utilisateurs</h6>
                                            <h2 class="mb-0">{{ $usersCount ?? '0' }}</h2>
                                        </div>
                                        <i class="fas fa-users fa-3x opacity-50"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex">
                                    <a href="{{ route('admin.users.index') }}" class="text-white">
                                        {{ __('Voir tout') }}
                                        <i class="fas fa-arrow-circle-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase">Projets</h6>
                                            <h2 class="mb-0">{{ $projectsCount ?? '0' }}</h2>
                                        </div>
                                        <i class="fas fa-project-diagram fa-3x opacity-50"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex">
                                    <a href="{{ route('admin.projects.index') }}" class="text-white">
                                        {{ __('Voir tout') }}
                                        <i class="fas fa-arrow-circle-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card bg-warning text-dark h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-uppercase">Stagiaires</h6>
                                            <h2 class="mb-0">{{ $internsCount ?? '0' }}</h2>
                                        </div>
                                        <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                                    </div>
                                </div>
                                <div class="card-footer d-flex">
                                    <a href="{{ route('admin.users.index', ['role' => 'intern']) }}" class="text-dark">
                                        {{ __('Voir tout') }}
                                        <i class="fas fa-arrow-circle-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Derniers Projets</h6>
                                    <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-outline-primary">
                                        {{ __('Tout voir') }}
                                    </a>
                                </div>
                                <div class="list-group list-group-flush">
                                    @forelse($projects as $project)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $project->name }}</h6>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit me-1"></i> Modifier
                                                    </a>
                                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                                            <i class="fas fa-trash me-1"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <p class="mb-1">{{ Str::limit($project->description, 50) }}</p>
                                            <small>Encadrant: {{ $project->encadrant->name ?? 'Non assigné' }}</small>
                                        </div>
                                    @empty
                                        <div class="list-group-item">
                                            <p class="mb-0 text-muted">Aucun projet pour le moment</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Derniers Utilisateurs</h6>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                                        {{ __('Tout voir') }}
                                    </a>
                                </div>
                                <div class="list-group list-group-flush">
                                    @forelse($users as $user)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <div>
                                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                                    <span class="badge bg-{{ $user->getRoleColor() }}">
                                                        {{ $user->getRoleNames()->first() ?? 'Aucun rôle' }}
                                                    </span>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit me-1"></i> Modifier
                                                    </a>
                                                    @if($user->id !== Auth::id())
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                            <i class="fas fa-trash me-1"></i> Supprimer
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="mb-1 small text-muted">{{ $user->email }}</p>
                                        </div>
                                    @empty
                                        <div class="list-group-item">
                                            <p class="mb-0 text-muted">Aucun utilisateur pour le moment</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- جدول المشاريع --}}
                    <div class="card mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Projets</h6>
                            <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-outline-primary">
                                {{ __('Tout voir') }}
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Description</th>
                                            <th>Encadrant</th>
                                            <th>Durée</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($projects as $project)
                                            <tr>
                                                <td>{{ $project->id }}</td>
                                                <td>{{ $project->name }}</td>
                                                <td>{{ Str::limit($project->description, 50) }}</td>
                                                <td>{{ $project->encadrant->name ?? 'Non assigné' }}</td>
                                                <td>{{ $project->duree }} mois</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-outline-primary">
                                                            <i class="fas fa-edit me-1"></i> Modifier
                                                        </a>
                                                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                                                <i class="fas fa-trash me-1"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Aucun projet pour le moment</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- جدول المستخدمين --}}
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Utilisateurs</h6>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                                {{ __('Tout voir') }}
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Email</th>
                                            <th>Rôle</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $user->getRoleColor() }}">
                                                        {{ $user->getRoleNames()->first() ?? 'Aucun rôle' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                                                            <i class="fas fa-edit me-1"></i> Modifier
                                                        </a>
                                                        @if($user->id !== Auth::id())
                                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                                    <i class="fas fa-trash me-1"></i> Supprimer
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Aucun utilisateur pour le moment</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
