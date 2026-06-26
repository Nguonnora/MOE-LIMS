@extends('layouts.app')
@section('title', 'Add Sample to ' . $workOrder->wo_number)
@section('content')
<div class="card">
    <div class="card-header">Add Sample to Work Order #{{ $workOrder->wo_number }}</div>
    <div class="card-body">
        <form action="{{ route('samples.store', $workOrder) }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sample_type" class="form-label">Sample Type <span class="text-danger">*</span></label>
                        <input type="text" name="sample_type" id="sample_type" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sampling_date" class="form-label">Sampling Date <span class="text-danger">*</span></label>
                        <input type="date" name="sampling_date" id="sampling_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="sample_description" class="form-label">Sample Description</label>
                        <textarea name="sample_description" id="sample_description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="province_id" class="form-label">Province</label>
                        <select name="province_id" id="province_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="district_id" class="form-label">District</label>
                        <select name="district_id" id="district_id" class="form-select" disabled>
                            <option value="">Select province first</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="commune_id" class="form-label">Commune</label>
                        <select name="commune_id" id="commune_id" class="form-select" disabled>
                            <option value="">Select district first</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="village_id" class="form-label">Village</label>
                        <select name="village_id" id="village_id" class="form-select" disabled>
                            <option value="">Select commune first</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="coordinate_system" class="form-label">Coordinate System</label>
                        <select name="coordinate_system" id="coordinate_system" class="form-select">
                            <option value="N/A">N/A</option>
                            <option value="DD">DD (Decimal Degrees)</option>
                            <option value="UTM">UTM</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="coordinate_x" class="form-label">UTM X / Degree N</label>
                        <input type="text" name="coordinate_x" id="coordinate_x" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="coordinate_y" class="form-label">UTM Y / Degree E</label>
                        <input type="text" name="coordinate_y" id="coordinate_y" class="form-control">
                    </div>
                </div>
            </div>

            <h5 class="border-bottom pb-2 mt-4">Tests</h5>
            <div id="tests-container">
                <div class="test-item row g-3 mb-3 align-items-end">
                    <div class="col-md-3">
                        <label>Parameter</label>
                        <select name="tests[0][test_parameter_id]" class="form-select test-parameter-select">
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
                    <div class="col-md-2"><label>Test Name</label><input type="text" name="tests[0][test_name]" class="form-control test-name" placeholder="Test Name" required></div>
                    <div class="col-md-1"><label>Code</label><input type="text" name="tests[0][test_code]" class="form-control test-code" placeholder="Code"></div>
                    <div class="col-md-2"><label>Category</label><input type="text" name="tests[0][test_category]" class="form-control test-category" placeholder="Category"></div>
                    <div class="col-md-2"><label>Parameter</label><input type="text" name="tests[0][parameter]" class="form-control test-parameter" placeholder="Parameter"></div>
                    <div class="col-md-1"><label>Unit</label><input type="text" name="tests[0][unit]" class="form-control test-unit" placeholder="Unit"></div>
                    <div class="col-md-1"><label>Price</label><input type="number" step="0.01" name="tests[0][price]" class="form-control test-price" placeholder="Price" value="0"></div>
                    <div class="col-md-12"><label>Method</label><input type="text" name="tests[0][method]" class="form-control test-method" placeholder="Method"><input type="hidden" name="tests[0][reference_method]" class="test-reference-method"></div>
                    <div class="col-md-12 mt-2"><button type="button" class="btn btn-sm btn-danger remove-test" style="display:none;">Remove</button></div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" id="add-test">Add Another Test</button>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Save Sample & Tests</button>
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

    // ---- Dynamic Geo dropdowns ----
    const provinceSelect = document.getElementById('province_id');
    const districtSelect = document.getElementById('district_id');
    const communeSelect = document.getElementById('commune_id');
    const villageSelect = document.getElementById('village_id');

    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        districtSelect.disabled = true;
        communeSelect.disabled = true;
        villageSelect.disabled = true;
        districtSelect.innerHTML = '<option value="">Select province first</option>';
        communeSelect.innerHTML = '<option value="">Select district first</option>';
        villageSelect.innerHTML = '<option value="">Select commune first</option>';
        if (provinceId) {
            fetch('{{ route("samples.districts", "") }}/' + provinceId)
                .then(res => res.json())
                .then(data => {
                    districtSelect.disabled = false;
                    districtSelect.innerHTML = '<option value="">Select district</option>';
                    data.forEach(district => {
                        districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                    });
                });
        }
    });

    districtSelect.addEventListener('change', function() {
        const districtId = this.value;
        communeSelect.disabled = true;
        villageSelect.disabled = true;
        communeSelect.innerHTML = '<option value="">Select district first</option>';
        villageSelect.innerHTML = '<option value="">Select commune first</option>';
        if (districtId) {
            fetch('{{ route("samples.communes", "") }}/' + districtId)
                .then(res => res.json())
                .then(data => {
                    communeSelect.disabled = false;
                    communeSelect.innerHTML = '<option value="">Select commune</option>';
                    data.forEach(commune => {
                        communeSelect.innerHTML += `<option value="${commune.id}">${commune.name}</option>`;
                    });
                });
        }
    });

    communeSelect.addEventListener('change', function() {
        const communeId = this.value;
        villageSelect.disabled = true;
        villageSelect.innerHTML = '<option value="">Select commune first</option>';
        if (communeId) {
            fetch('{{ route("samples.villages", "") }}/' + communeId)
                .then(res => res.json())
                .then(data => {
                    villageSelect.disabled = false;
                    villageSelect.innerHTML = '<option value="">Select village</option>';
                    data.forEach(village => {
                        villageSelect.innerHTML += `<option value="${village.id}">${village.name}</option>`;
                    });
                });
        }
    });
</script>
@endpush
@endsection