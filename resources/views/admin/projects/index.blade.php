@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Projects') }}</span>
                    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">
                        {{ __('Add New Project') }}
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <!-- Search Form -->
                    <div class="mb-4">
                        <form action="{{ route('admin.projects.index') }}" method="GET" class="row g-3">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Rechercher par nom..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i> Rechercher
                                    </button>
                                </div>
                            </div>
                            @if(request('search'))
                            <div class="col-md-4">
                                <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Réinitialiser
                                </a>
                            </div>
                            @endif
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Supervisor</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                    <tr>
                                        <td>{{ $project->id }}</td>
                                        <td>{{ $project->name }}</td>
                                        <td>{{ Str::limit($project->description, 50) }}</td>
                                        <td>{{ $project->encadrant->name ?? 'N/A' }}</td>
                                        <td class="text-nowrap">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Project Actions">
                                                <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-info" data-bs-toggle="tooltip" title="Voir">
                                                    <i class="fas fa-eye me-1"></i> Voir
                                                </a>
                                                <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-warning" data-bs-toggle="tooltip" title="Modifier">
                                                    <i class="fas fa-edit me-1"></i> Modifier
                                                </a>
                                                <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="Supprimer">
                                                        <i class="fas fa-trash me-1"></i> Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No projects found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Enable Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
