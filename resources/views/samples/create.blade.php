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
                <!-- First test row (template) -->
                <div class="test-item row g-3 mb-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Parameter</label>
                        <select name="tests[0][test_parameter_id]" class="form-select test-parameter-select" data-index="0">
                            <option value="">-- Select from library --</option>
                            @foreach($testParameters as $param)
                                <option value="{{ $param->id }}" data-code="{{ $param->code }}" data-name="{{ $param->name }}" data-category="{{ $param->category }}" data-unit="{{ $param->unit }}" data-method="{{ $param->method }}" data-reference="{{ $param->reference_method }}" data-price="{{ $param->default_price }}">{{ $param->code }} - {{ $param->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Test Name</label>
                        <input type="text" name="tests[0][test_name]" class="form-control test-name" placeholder="Test Name" required>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Code</label>
                        <input type="text" name="tests[0][test_code]" class="form-control test-code" placeholder="Code">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Category</label>
                        <input type="text" name="tests[0][test_category]" class="form-control test-category" placeholder="Category">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Parameter</label>
                        <input type="text" name="tests[0][parameter]" class="form-control test-parameter" placeholder="Parameter">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Unit</label>
                        <input type="text" name="tests[0][unit]" class="form-control test-unit" placeholder="Unit">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="tests[0][price]" class="form-control test-price" placeholder="Price" value="0">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Method</label>
                        <input type="text" name="tests[0][method]" class="form-control test-method" placeholder="Method">
                        <input type="hidden" name="tests[0][reference_method]" class="test-reference-method">
                    </div>
                    <div class="col-md-12 mt-2">
                        <button type="button" class="btn btn-sm btn-danger remove-test" style="display:none;">Remove</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" id="add-test">Add Another Test</button>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Register</button>
                <a href="{{ route('samples.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let testIndex = 1;

    // Function to populate fields from selected parameter
    function populateTestFields(selectElement) {
        const row = selectElement.closest('.test-item');
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        if (selectedOption.value) {
            row.querySelector('.test-name').value = selectedOption.dataset.name || '';
            row.querySelector('.test-code').value = selectedOption.dataset.code || '';
            row.querySelector('.test-category').value = selectedOption.dataset.category || '';
            row.querySelector('.test-parameter').value = selectedOption.dataset.parameter || '';
            row.querySelector('.test-unit').value = selectedOption.dataset.unit || '';
            row.querySelector('.test-method').value = selectedOption.dataset.method || '';
            row.querySelector('.test-reference-method').value = selectedOption.dataset.reference || '';
            row.querySelector('.test-price').value = selectedOption.dataset.price || 0;
        } else {
            // Clear fields if "Select" is chosen
            row.querySelector('.test-name').value = '';
            row.querySelector('.test-code').value = '';
            row.querySelector('.test-category').value = '';
            row.querySelector('.test-parameter').value = '';
            row.querySelector('.test-unit').value = '';
            row.querySelector('.test-method').value = '';
            row.querySelector('.test-reference-method').value = '';
            row.querySelector('.test-price').value = 0;
        }
    }

    // Attach event listener for change on test-parameter-select
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('test-parameter-select')) {
            populateTestFields(e.target);
        }
    });

    // Add new test row
    document.getElementById('add-test').addEventListener('click', function() {
        const container = document.getElementById('tests-container');
        const templateRow = container.querySelector('.test-item');
        const newRow = templateRow.cloneNode(true);
        // Update indexes
        const inputs = newRow.querySelectorAll('[name^="tests[0]"]');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace('tests[0]', 'tests[' + testIndex + ']'));
            }
            // Reset values except select dropdown
            if (!input.classList.contains('test-parameter-select')) {
                input.value = '';
            } else {
                input.selectedIndex = 0;
            }
        });
        // Show remove button
        const removeBtn = newRow.querySelector('.remove-test');
        if (removeBtn) removeBtn.style.display = 'inline-block';
        // Clear any existing data attributes from options? No need.
        container.appendChild(newRow);
        testIndex++;
    });

    // Remove test row (delegated)
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-test')) {
            const row = e.target.closest('.test-item');
            if (row && document.querySelectorAll('.test-item').length > 1) {
                row.remove();
            } else {
                alert('You must keep at least one test.');
            }
        }
    });
</script>
@endpush
@endsection