@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Project Details') }}</span>
                    <div>
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> {{ __('Edit') }}
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">{{ __('Name') }}:</div>
                        <div class="col-md-8">{{ $project->name }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">{{ __('Description') }}:</div>
                        <div class="col-md-8">{{ $project->description ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">{{ __('Supervisor') }}:</div>
                        <div class="col-md-8">{{ $project->encadrant->name ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">{{ __('Created At') }}:</div>
                        <div class="col-md-8">{{ $project->created_at->format('Y-m-d H:i:s') }}</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">{{ __('Last Updated') }}:</div>
                        <div class="col-md-8">{{ $project->updated_at->format('Y-m-d H:i:s') }}</div>
                    </div>

                    <!-- Stagiaires Section -->
                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">{{ __('Assigned Interns') }}:</div>
                        <div class="col-md-8">
                            @if($project->stagiaires->count() > 0)
                                <ul class="list-group">
                                    @foreach($project->stagiaires as $stagiaire)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $stagiaire->name }}
                                            <form action="{{ route('admin.projects.remove-stagiaire', [$project, $stagiaire]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to remove this intern from the project?')">
                                                    <i class="fas fa-user-minus"></i> Remove
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">{{ __('No interns assigned to this project.') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Add Stagiaire Form -->
                    <div class="row mb-4">
                        <div class="col-md-4 font-weight-bold">{{ __('Add Interns') }}:</div>
                        <div class="col-md-8">
                            <form action="{{ route('admin.projects.add-stagiaire', $project) }}" method="POST" class="form-inline">
                                @csrf
                                <div class="form-group mr-2">
                                    <select name="stagiaire_id" class="form-control select2" style="min-width: 200px;">
                                        <option value="">{{ __('Select an intern') }}</option>
                                        @foreach($stagiaires as $stagiaire)
                                            @if(!$project->stagiaires->contains($stagiaire->id))
                                                <option value="{{ $stagiaire->id }}">{{ $stagiaire->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> {{ __('Add') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Add more project details as needed -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('Confirm Delete') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete this project? This action cannot be undone.') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <form id="deleteForm" action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete() {
        $('#deleteModal').modal('show');
    }
</script>
@endpush
@endsection
