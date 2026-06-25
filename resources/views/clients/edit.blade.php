@extends('layouts.app')
@section('title', 'Edit Client')
@section('content')
<div class="card">
    <div class="card-header">Edit Client: {{ $client->name }}</div>
    <div class="card-body">
        <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $client->name) }}" required>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="organization" class="form-label">Organization</label>
                        <input type="text" name="organization" id="organization" class="form-control" value="{{ old('organization', $client->organization) }}">
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $client->email) }}">
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $client->phone) }}">
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $client->address) }}">
                    </div>
                </div>
                <div class="col-md-3 col-12">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $client->city) }}">
                    </div>
                </div>
                <div class="col-md-3 col-12">
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" name="country" id="country" class="form-control" value="{{ old('country', $client->country) }}">
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="tax_id" class="form-label">Tax ID</label>
                        <input type="text" name="tax_id" id="tax_id" class="form-control" value="{{ old('tax_id', $client->tax_id) }}">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes', $client->notes) }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ $client->is_active ? 'checked' : '' }}>
                        <label for="is_active" class="form-check-label">Active</label>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-success">Update Client</button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection