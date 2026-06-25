@extends('layouts.app')
@section('title', 'Users')
@section('content')
<div class="d-flex justify-content-between"><h2>Users</h2><a href="{{ route('users.create') }}" class="btn btn-success">Add User</a></div>
<div class="card"><div class="card-body">
    <table class="table table-striped">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($users as $user)
            <tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ ucfirst($user->role) }}</td>
                <td><a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div></div>
@endsection