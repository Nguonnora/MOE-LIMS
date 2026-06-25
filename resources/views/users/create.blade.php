@extends('layouts.app')
@section('title', 'Add User')
@section('content')
<div class="card"><div class="card-header">Add User</div><div class="card-body">
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
        <div class="mb-3"><label>Confirm Password</label><input type="password" name="password_confirmation" class="form-control" required></div>
        <div class="mb-3"><label>Role</label>
            <select name="role" class="form-control">
                <option value="admin">Admin</option><option value="technician">Technician</option>
                <option value="approver">Approver</option><option value="receptionist">Receptionist</option>
                <option value="viewer">Viewer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Create</button> <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div></div>
@endsection