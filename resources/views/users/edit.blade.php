@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="card">
    <div class="card-header">Edit User: {{ $user->name }}</div>
    <div class="card-body">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (leave blank to keep current)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="technician" {{ $user->role == 'technician' ? 'selected' : '' }}>Technician</option>
                    <option value="approver" {{ $user->role == 'approver' ? 'selected' : '' }}>Approver</option>
                    <option value="receptionist" {{ $user->role == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                    <option value="viewer" {{ $user->role == 'viewer' ? 'selected' : '' }}>Viewer</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-success">Update User</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection