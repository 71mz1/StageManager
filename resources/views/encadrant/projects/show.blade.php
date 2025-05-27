@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Détails du Projet: {{ $project->name }}</h4>
                    <a href="{{ route('encadrant.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informations du Projet</h5>
                            <dl class="row">
                                <dt class="col-sm-4">Description:</dt>
                                <dd class="col-sm-8">{{ $project->description ?? 'Non spécifiée' }}</dd>

                                <dt class="col-sm-4">Date de début:</dt>
                                <dd class="col-sm-8">
                                    {{ $project->start_date ? $project->start_date->format('d/m/Y') : 'Non définie' }}
                                </dd>

                                <dt class="col-sm-4">Date de fin:</dt>
                                <dd class="col-sm-8">
                                    {{ $project->end_date ? $project->end_date->format('d/m/Y') : 'Non définie' }}
                                </dd>

                                <dt class="col-sm-4">Priorité:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-{{ $project->priority === 'Haute' ? 'danger' : ($project->priority === 'Moyenne' ? 'warning' : 'info') }}">
                                        {{ $project->priority ?? 'Normale' }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Stagiaires Assignés</h5>
                            @if($project->stagiaires->count() > 0)
                                <div class="list-group">
                                    @foreach($project->stagiaires as $stagiaire)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $stagiaire->name }}</h6>
                                                    <small class="text-muted">{{ $stagiaire->email }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Aucun stagiaire n'est actuellement assigné à ce projet.
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Tâches</h5>
                            </div>
                            
                            @if($project->tasks->count() > 0)
                                <div class="list-group">
                                    @foreach($project->tasks as $task)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">{{ $task->title }}</h6>
                                                <span class="badge bg-{{ $task->status === 'Terminé' ? 'success' : ($task->status === 'En cours' ? 'primary' : 'secondary') }}">
                                                    {{ $task->status }}
                                                </span>
                                            </div>
                                            <p class="mb-1">{{ $task->description }}</p>
                                            <small class="text-muted">
                                                @if($task->assignee)
                                                    Assigné à: {{ $task->assignee->name }}
                                                @else
                                                    Non assigné
                                                @endif
                                                @if($task->duration)
                                                     • Durée: {{ $task->duration }}
                                                @endif
                                            </small>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Aucune tâche n'a été créée pour ce projet.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Ajouter une nouvelle tâche</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('encadrant.tasks.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="title" class="form-label">Titre de la tâche</label>
                                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                                           id="title" name="title" required>
                                                    @error('title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Statut</label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="A faire">A faire</option>
                                                        <option value="En cours">En cours</option>
                                                        <option value="Terminé">Terminé</option>
                                                        <option value="Bloqué">Bloqué</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                     id="description" name="description" rows="3" required></textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="duration" class="form-label">Durée estimée</label>
                                                    <input type="text" class="form-control" id="duration" name="duration" 
                                                           placeholder="Ex: 2 jours, 8 heures, etc.">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="assignee_id" class="form-label">Assigner à</label>
                                                    <select class="form-select @error('assignee_id') is-invalid @enderror" 
                                                            id="assignee_id" name="assignee_id">
                                                        <option value="">Sélectionner un stagiaire</option>
                                                        @foreach($project->stagiaires as $stagiaire)
                                                            <option value="{{ $stagiaire->id }}">
                                                                {{ $stagiaire->name }} ({{ $stagiaire->email }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('assignee_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="reset" class="btn btn-outline-secondary me-md-2">Annuler</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-plus-circle me-1"></i> Créer la tâche
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
