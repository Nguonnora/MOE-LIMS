@extends('layouts.app')
@section('title', 'New Work Order')
@section('content')
<div class="card">
    <div class="card-header">Register New Work Order & Sample</div>
    <div class="card-body">
        <form action="{{ route('samples.store') }}" method="POST">
            @csrf

            <!-- Work Order Details -->
            <h5 class="border-bottom pb-2">Work Order Details</h5>
            <div class="row">
                <div class="col-md-6">
                    <!-- Client Dropdown -->
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Select Client</label>
                        <select name="client_id" id="client_id" class="form-select">
                            <option value="">-- Select from registered clients --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    data-email="{{ $client->email }}"
                                    data-phone="{{ $client->phone }}"
                                    data-org="{{ $client->organization }}">
                                    {{ $client->display_name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Or fill in the fields below for a new client.</small>
                    </div>
                    <div class="mb-3">
                        <label for="client_name" class="form-label">Client Name</label>
                        <input type="text" name="client_name" id="client_name" class="form-control" placeholder="If not in list">
                    </div>
                    <div class="mb-3">
                        <label for="client_email" class="form-label">Client Email</label>
                        <input type="email" name="client_email" id="client_email" class="form-control" placeholder="client@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="client_phone" class="form-label">Phone</label>
                        <input type="text" name="client_phone" id="client_phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="client_organization" class="form-label">Organization</label>
                        <input type="text" name="client_organization" id="client_organization" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order_date" class="form-label">Order Date *</label>
                        <input type="date" name="order_date" id="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="expected_completion_date" class="form-label">Expected Completion</label>
                        <input type="date" name="expected_completion_date" id="expected_completion_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority *</label>
                        <select name="priority" id="priority" class="form-select" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="project_description" class="form-label">Project Description</label>
                        <textarea name="project_description" id="project_description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <!-- Sample Details -->
            <h5 class="border-bottom pb-2 mt-4">Sample Details</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sample_type" class="form-label">Sample Type *</label>
                        <input type="text" name="sample_type" id="sample_type" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="sample_matrix" class="form-label">Matrix</label>
                        <input type="text" name="sample_matrix" id="sample_matrix" class="form-control" placeholder="Water, Soil, Air">
                    </div>
                    <div class="mb-3">
                        <label for="sampling_location" class="form-label">Sampling Location</label>
                        <input type="text" name="sampling_location" id="sampling_location" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="sampling_date" class="form-label">Sampling Date *</label>
                        <input type="date" name="sampling_date" id="sampling_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="sampling_time" class="form-label">Sampling Time</label>
                        <input type="time" name="sampling_time" id="sampling_time" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="sampling_method" class="form-label">Sampling Method</label>
                        <input type="text" name="sampling_method" id="sampling_method" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="container_type" class="form-label">Container Type</label>
                        <input type="text" name="container_type" id="container_type" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="preservation_method" class="form-label">Preservation Method</label>
                        <input type="text" name="preservation_method" id="preservation_method" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="received_date" class="form-label">Received Date *</label>
                        <input type="date" name="received_date" id="received_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="received_time" class="form-label">Received Time</label>
                        <input type="time" name="received_time" id="received_time" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="received_by" class="form-label">Received By</label>
                        <input type="text" name="received_by" id="received_by" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="sample_quantity" class="form-label">Quantity</label>
                        <div class="row">
                            <div class="col-8">
                                <input type="number" step="any" name="sample_quantity" id="sample_quantity" class="form-control">
                            </div>
                            <div class="col-4">
                                <input type="text" name="quantity_unit" id="quantity_unit" class="form-control" placeholder="Unit">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="sample_description" class="form-label">Description</label>
                <textarea name="sample_description" id="sample_description" class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label for="sample_condition" class="form-label">Condition</label>
                <input type="text" name="sample_condition" id="sample_condition" class="form-control">
            </div>

            <!-- Tests -->
            <h5 class="border-bottom pb-2 mt-4">Tests</h5>
            <div id="tests-container">
                <div class="test-item row g-3 mb-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Parameter</label>
                        <select name="tests[0][test_parameter_id]" class="form-select test-parameter-select" data-index="0">
                            <option value="">-- Select from library --</option>
                            @foreach($testParameters as $param)
                                <option value="{{ $param->id }}"
                                    data-code="{{ $param->code }}"
                                    data-name="{{ $param->name }}"
                                    data-category="{{ $param->category }}"
                                    data-unit="{{ $param->unit }}"
                                    data-method="{{ $param->method }}"
                                    data-reference="{{ $param->reference_method }}"
                                    data-price="{{ $param->default_price }}">
                                    {{ $param->code }} - {{ $param->name }}
                                </option>
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

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('test-parameter-select')) {
            populateTestFields(e.target);
        }
        // Auto-fill client fields when a client is selected
        if (e.target.id === 'client_id') {
            const selectedOption = e.target.options[e.target.selectedIndex];
            if (selectedOption.value) {
                document.getElementById('client_name').value = selectedOption.text;
                document.getElementById('client_email').value = selectedOption.dataset.email || '';
                document.getElementById('client_phone').value = selectedOption.dataset.phone || '';
                document.getElementById('client_organization').value = selectedOption.dataset.org || '';
            } else {
                document.getElementById('client_name').value = '';
                document.getElementById('client_email').value = '';
                document.getElementById('client_phone').value = '';
                document.getElementById('client_organization').value = '';
            }
        }
    });

    document.getElementById('add-test').addEventListener('click', function() {
        const container = document.getElementById('tests-container');
        const templateRow = container.querySelector('.test-item');
        const newRow = templateRow.cloneNode(true);
        const inputs = newRow.querySelectorAll('[name^="tests[0]"]');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace('tests[0]', 'tests[' + testIndex + ']'));
            }
            if (!input.classList.contains('test-parameter-select')) {
                input.value = '';
            } else {
                input.selectedIndex = 0;
            }
        });
        const removeBtn = newRow.querySelector('.remove-test');
        if (removeBtn) removeBtn.style.display = 'inline-block';
        container.appendChild(newRow);
        testIndex++;
    });

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