@extends('layouts.app')
@section('title', 'New Work Order')
@section('content')
<div class="card">
    <div class="card-header">Register New Work Order & Sample</div>
    <div class="card-body">
        <form action="{{ route('samples.store') }}" method="POST">
            @csrf
            <h5 class="border-bottom pb-2">Work Order Details</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3"><label>Client Name *</label><input type="text" name="client_name" class="form-control" required></div>
                    <div class="mb-3"><label>Client Email</label><input type="email" name="client_email" class="form-control"></div>
                    <div class="mb-3"><label>Phone</label><input type="text" name="client_phone" class="form-control"></div>
                    <div class="mb-3"><label>Organization</label><input type="text" name="client_organization" class="form-control"></div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3"><label>Order Date *</label><input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                    <div class="mb-3"><label>Expected Completion</label><input type="date" name="expected_completion_date" class="form-control"></div>
                    <div class="mb-3"><label>Priority *</label>
                        <select name="priority" class="form-control" required>
                            <option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option>
                        </select>
                    </div>
                    <div class="mb-3"><label>Project Description</label><textarea name="project_description" class="form-control" rows="2"></textarea></div>
                </div>
            </div>

            <h5 class="border-bottom pb-2 mt-4">Sample Details</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3"><label>Sample Type *</label><input type="text" name="sample_type" class="form-control" required></div>
                    <div class="mb-3"><label>Matrix</label><input type="text" name="sample_matrix" class="form-control" placeholder="Water, Soil, Air"></div>
                    <div class="mb-3"><label>Sampling Location</label><input type="text" name="sampling_location" class="form-control"></div>
                    <div class="mb-3"><label>Sampling Date *</label><input type="date" name="sampling_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                    <div class="mb-3"><label>Sampling Time</label><input type="time" name="sampling_time" class="form-control"></div>
                    <div class="mb-3"><label>Sampling Method</label><input type="text" name="sampling_method" class="form-control"></div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3"><label>Container Type</label><input type="text" name="container_type" class="form-control"></div>
                    <div class="mb-3"><label>Preservation Method</label><input type="text" name="preservation_method" class="form-control"></div>
                    <div class="mb-3"><label>Received Date *</label><input type="date" name="received_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                    <div class="mb-3"><label>Received Time</label><input type="time" name="received_time" class="form-control"></div>
                    <div class="mb-3"><label>Received By</label><input type="text" name="received_by" class="form-control"></div>
                    <div class="mb-3"><label>Quantity</label>
                        <div class="row"><div class="col-8"><input type="number" step="any" name="sample_quantity" class="form-control"></div>
                        <div class="col-4"><input type="text" name="quantity_unit" class="form-control" placeholder="Unit"></div></div>
                    </div>
                </div>
            </div>
            <div class="mb-3"><label>Description</label><textarea name="sample_description" class="form-control" rows="2"></textarea></div>
            <div class="mb-3"><label>Condition</label><input type="text" name="sample_condition" class="form-control"></div>

            <h5 class="border-bottom pb-2 mt-4">Tests</h5>
            <div id="tests-container">
                <div class="test-item row g-3 mb-3">
                    <div class="col-md-3"><input type="text" name="tests[0][test_name]" class="form-control" placeholder="Test Name *" required></div>
                    <div class="col-md-2"><input type="text" name="tests[0][test_code]" class="form-control" placeholder="Code"></div>
                    <div class="col-md-2"><input type="text" name="tests[0][test_category]" class="form-control" placeholder="Category"></div>
                    <div class="col-md-2"><input type="text" name="tests[0][parameter]" class="form-control" placeholder="Parameter"></div>
                    <div class="col-md-1"><input type="text" name="tests[0][unit]" class="form-control" placeholder="Unit"></div>
                    <div class="col-md-2"><input type="number" step="0.01" name="tests[0][price]" class="form-control" placeholder="Price" value="0"></div>
                    <div class="col-md-12"><input type="text" name="tests[0][method]" class="form-control" placeholder="Method"></div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" id="add-test">Add Another Test</button>

            <div class="mt-4"><button type="submit" class="btn btn-success">Register</button> <a href="{{ route('samples.index') }}" class="btn btn-secondary">Cancel</a></div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    let testIndex = 1;
    document.getElementById('add-test').addEventListener('click', function() {
        const container = document.getElementById('tests-container');
        const newItem = document.createElement('div');
        newItem.className = 'test-item row g-3 mb-3';
        newItem.innerHTML = `
            <div class="col-md-3"><input type="text" name="tests[${testIndex}][test_name]" class="form-control" placeholder="Test Name *" required></div>
            <div class="col-md-2"><input type="text" name="tests[${testIndex}][test_code]" class="form-control" placeholder="Code"></div>
            <div class="col-md-2"><input type="text" name="tests[${testIndex}][test_category]" class="form-control" placeholder="Category"></div>
            <div class="col-md-2"><input type="text" name="tests[${testIndex}][parameter]" class="form-control" placeholder="Parameter"></div>
            <div class="col-md-1"><input type="text" name="tests[${testIndex}][unit]" class="form-control" placeholder="Unit"></div>
            <div class="col-md-2"><input type="number" step="0.01" name="tests[${testIndex}][price]" class="form-control" placeholder="Price" value="0"></div>
            <div class="col-md-12"><input type="text" name="tests[${testIndex}][method]" class="form-control" placeholder="Method"></div>
        `;
        container.appendChild(newItem);
        testIndex++;
    });
</script>
@endpush
@endsection