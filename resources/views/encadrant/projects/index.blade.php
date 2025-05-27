@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Mes Projets</h4>
                    <a href="{{ route('encadrant.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                    </a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($projects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom du Projet</th>
                                        <th>Date de début</th>
                                        <th>Date de fin</th>
                                        <th>Stagiaires</th>
                                        <th>Tâches</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ $project->start_date ? $project->start_date->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $project->end_date ? $project->end_date->format('d/m/Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $project->stagiaires_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $project->tasks_count ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('encadrant.projects.show', $project) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $projects->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            Vous n'avez aucun projet assigné pour le moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.4em 0.6em;
    }
</style>
@endsection
