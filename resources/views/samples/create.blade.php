@extends('layouts.app')
@section('title', 'Add Sample to ' . $workOrder->wo_number)
@section('content')
<div class="card">
    <div class="card-header">Add Sample to Work Order #{{ $workOrder->wo_number }}</div>
    <div class="card-body">
        <form action="{{ route('samples.store', $workOrder) }}" method="POST">
            @csrf

            <div class="row">
                {{-- Sample Code Preview --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Sample Code (Preview)</label>
                        @php
                            $nextSequence = $workOrder->samples()->count() + 1;
                            if ($workOrder->amount_of_sample == 1) {
                                $previewCode = $workOrder->wo_number;
                            } else {
                                $roman = \App\Models\Sample::toRoman($nextSequence);
                                $previewCode = $workOrder->wo_number . '-' . $roman;
                            }
                        @endphp
                        <input type="text" class="form-control" value="{{ $previewCode }}" readonly style="background:#f0f0f0;">
                        <small class="text-muted">
                            @if($workOrder->amount_of_sample == 1)
                                Single sample – code equals work order number.
                            @else
                                Assigned as Roman numeral (I, II, III, ...).
                            @endif
                        </small>
                    </div>
                </div>

                {{-- Sample Type --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sample_type" class="form-label">Sample Type <span class="text-danger">*</span></label>
                        <input type="text" name="sample_type" id="sample_type" class="form-control" required>
                    </div>
                </div>

                {{-- Sampling Date --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sampling_date" class="form-label">Sampling Date <span class="text-danger">*</span></label>
                        <input type="date" name="sampling_date" id="sampling_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                {{-- Sample Description --}}
                <div class="col-12">
                    <div class="mb-3">
                        <label for="sample_description" class="form-label">Sample Description</label>
                        <textarea name="sample_description" id="sample_description" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                {{-- Geo Location --}}
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

                {{-- Coordinates --}}
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

            {{-- Tests – Checkboxes (filtered by matrix) --}}
            <h5 class="border-bottom pb-2 mt-4">Tests</h5>
            <div class="row">
                @forelse($testParameters as $param)
                    <div class="col-md-3 col-sm-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="test_ids[]" value="{{ $param->id }}" id="test_{{ $param->id }}">
                            <label class="form-check-label" for="test_{{ $param->id }}">
                                <strong>{{ $param->code }}</strong> – {{ $param->name }}
                                @if($param->unit)
                                    <br><small class="text-muted">Unit: {{ $param->unit }}</small>
                                @endif
                                @if($param->default_price)
                                    <br><small class="text-muted">Price: ${{ number_format($param->default_price, 2) }}</small>
                                @endif
                            </label>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-warning">No test parameters available for this sample matrix ({{ $workOrder->sample_matrix ?? 'Not set' }}).</p>
                        <p>Please ask an admin to add appropriate test parameters.</p>
                    </div>
                @endforelse
            </div>
            <small class="text-muted">Select all tests that apply to this sample.</small>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Save Sample & Tests</button>
                <a href="{{ route('samples.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ---- Dynamic Geo dropdowns with correct URLs ----
    const provinceSelect = document.getElementById('province_id');
    const districtSelect = document.getElementById('district_id');
    const communeSelect = document.getElementById('commune_id');
    const villageSelect = document.getElementById('village_id');

    const districtUrl = '{{ route("samples.districts", ":provinceId") }}';
    const communeUrl = '{{ route("samples.communes", ":districtId") }}';
    const villageUrl = '{{ route("samples.villages", ":communeId") }}';

    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        districtSelect.disabled = true;
        communeSelect.disabled = true;
        villageSelect.disabled = true;
        districtSelect.innerHTML = '<option value="">Select province first</option>';
        communeSelect.innerHTML = '<option value="">Select district first</option>';
        villageSelect.innerHTML = '<option value="">Select commune first</option>';
        if (provinceId) {
            const url = districtUrl.replace(':provinceId', provinceId);
            fetch(url)
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
            const url = communeUrl.replace(':districtId', districtId);
            fetch(url)
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
            const url = villageUrl.replace(':communeId', communeId);
            fetch(url)
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