@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Tableau de bord Encadrant') }}</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Mes projets</h5>
                            
                            @if($projects->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nom du projet</th>
                                                <th>Date de début</th>
                                                <th>Date de fin</th>
                                                <th>Stagiaires</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($projects as $project)
                                                <tr>
                                                    <td>{{ $project->name }}</td>
                                                    <td>{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') : 'N/A' }}</td>
                                                    <td>{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') : 'N/A' }}</td>
                                                    <td>{{ $project->stagiaires_count ?? 0 }}</td>
                                                    <td>
                                                        <a href="{{ route('encadrant.projects.show', $project) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i> Voir
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                    {{ $projects->links() }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Aucun projet ne vous est actuellement assigné.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection