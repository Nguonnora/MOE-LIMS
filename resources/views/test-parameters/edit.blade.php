@extends('layouts.app')
@section('title', 'Edit Test Parameter')
@section('content')
<div class="card">
    <div class="card-header">Edit Parameter: {{ $testParameter->code }}</div>
    <div class="card-body">
        <form action="{{ route('test-parameters.update', $testParameter) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="code" class="form-label">Code *</label>
                        <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $testParameter->code) }}" required>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $testParameter->name) }}" required>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $testParameter->category) }}">
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label for="unit" class="form-label">Unit</label>
                        <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', $testParameter->unit) }}">
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label for="default_price" class="form-label">Default Price ($)</label>
                        <input type="number" step="0.01" name="default_price" id="default_price" class="form-control" value="{{ old('default_price', $testParameter->default_price) }}">
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="method" class="form-label">Method</label>
                        <input type="text" name="method" id="method" class="form-control" value="{{ old('method', $testParameter->method) }}">
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="reference_method" class="form-label">Reference Method</label>
                        <input type="text" name="reference_method" id="reference_method" class="form-control" value="{{ old('reference_method', $testParameter->reference_method) }}">
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label for="detection_limit" class="form-label">Detection Limit</label>
                        <input type="number" step="any" name="detection_limit" id="detection_limit" class="form-control" value="{{ old('detection_limit', $testParameter->detection_limit) }}">
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label for="quantification_limit" class="form-label">Quantification Limit</label>
                        <input type="number" step="any" name="quantification_limit" id="quantification_limit" class="form-control" value="{{ old('quantification_limit', $testParameter->quantification_limit) }}">
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="mb-3">
                        <label for="accreditation" class="form-label">Accreditation</label>
                        <input type="text" name="accreditation" id="accreditation" class="form-control" value="{{ old('accreditation', $testParameter->accreditation) }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_subcontracted" id="is_subcontracted" class="form-check-input" value="1" {{ $testParameter->is_subcontracted ? 'checked' : '' }}>
                        <label for="is_subcontracted" class="form-check-label">Is Subcontracted</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="2">{{ old('description', $testParameter->description) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-success">Update Parameter</button>
                <a href="{{ route('test-parameters.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection