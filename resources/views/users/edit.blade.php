@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
<div class="card"><div class="card-header">Edit: {{ $user->name }}</div><div class="card-body">
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" value="{{ $user->name }}" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $user->email }}" required></div>
        <div class="mb-3"><label>Password (leave blank to keep)</label><input type="password" name="password" class="form-control"></div>
        <div class="mb-3"><label>Confirm Password</label><input type="password" name="password_confirmation" class="form-control"></div>
        <div class="mb-3"><label>Role</label>
            <select name="role" class="form-control">
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="technician" {{ $user->role == 'technician' ? 'selected' : '' }}>Technician</option>
                <option value="approver" {{ $user->role == 'approver' ? 'selected' : '' }}>Approver</option>
                <option value="receptionist" {{ $user->role == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                <option value="viewer" {{ $user->role == 'viewer' ? 'selected' : '' }}>Viewer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update</button> <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div></div>
@endsection