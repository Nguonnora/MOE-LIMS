@extends('layouts.app')
@section('title', 'Clients')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h2 class="mb-0">Clients</h2>
    @if(in_array(auth()->user()->role, ['admin', 'receptionist']))
        <a href="{{ route('clients.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Add Client
        </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Organization</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td><strong>{{ $client->name }}</strong></td>
                        <td>{{ $client->organization ?? 'N/A' }}</td>
                        <td>{{ $client->email ?? 'N/A' }}</td>
                        <td>{{ $client->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $client->is_active ? 'success' : 'danger' }}">
                                {{ $client->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-warning">Edit</a>
                            @if(auth()->user()->role == 'admin')
                                <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this client?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">No clients found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection