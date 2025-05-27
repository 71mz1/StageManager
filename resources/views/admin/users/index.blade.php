@extends("layouts.app")

@section("title", "Gestion des Utilisateurs")

@section("content")
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ __('User Management') }}</h1>
        <a href="{{ route("admin.users.create") }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> {{ __('Add User') }}
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Roles') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->pluck("name")->join(", ") }}</td>
                            <td>{{ $user->type ?? "N/A" }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route("admin.users.edit", $user) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route("admin.users.destroy", $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                {{ __('No users found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
            
            <style>
                /* تحسين تنسيق أزرار التصفح */
                .pagination {
                    justify-content: center;
                    margin-top: 1rem;
                    margin-bottom: 0;
                }
                .pagination .page-link {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.875rem;
                }
                .pagination .page-item:not(:first-child) .page-link {
                    margin-left: 0.25rem;
                }
                .pagination .page-item:last-child .page-link,
                .pagination .page-item:first-child .page-link {
                    border-radius: 0.25rem;
                }
                .pagination .page-item.active .page-link {
                    background-color: #0d6efd;
                    border-color: #0d6efd;
                }
            </style>
        </div>
    </div>
</div>
@endsection
