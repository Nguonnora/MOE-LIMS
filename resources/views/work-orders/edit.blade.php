@extends('layouts.app')
@section('title', 'Edit Work Order')
@section('content')
<div class="card">
    <div class="card-header">Edit Work Order: {{ $workOrder->wo_number }}</div>
    <div class="card-body">
        <form action="{{ route('work-orders.update', $workOrder) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Client Name <span class="text-danger">*</span></label>
                        <select name="client_id" id="client_id" class="form-select" required>
                            <option value="">-- Select a client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $workOrder->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ old('contact_person', $workOrder->contact_person) }}">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $workOrder->phone) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="reception_date" class="form-label">Reception Date <span class="text-danger">*</span></label>
                        <input type="date" name="reception_date" id="reception_date" class="form-control" value="{{ old('reception_date', $workOrder->reception_date ? $workOrder->reception_date->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                        <select name="priority" id="priority" class="form-select" required>
                            <option value="low" {{ $workOrder->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $workOrder->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $workOrder->priority == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="project_description" class="form-label">Project Description</label>
                        <textarea name="project_description" id="project_description" class="form-control" rows="2">{{ old('project_description', $workOrder->project_description) }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="purpose_id" class="form-label">Purpose of Analysis</label>
                        <div class="input-group">
                            <select name="purpose_id" id="purpose_id" class="form-select">
                                <option value="">-- Select a purpose --</option>
                                @foreach($purposes as $purpose)
                                    <option value="{{ $purpose->id }}" {{ $workOrder->purpose_id == $purpose->id ? 'selected' : '' }}>{{ $purpose->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="purpose_name" id="purpose_name" class="form-control" placeholder="Or type new" style="flex:0 0 180px;">
                            <button class="btn btn-outline-success" type="button" id="addPurposeBtn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="sample_matrix" class="form-label">Sample Matrix <span class="text-danger">*</span></label>
                        <select name="sample_matrix" id="sample_matrix" class="form-select" required>
                            <option value="">Select</option>
                            <option value="Liquid" {{ $workOrder->sample_matrix == 'Liquid' ? 'selected' : '' }}>Liquid</option>
                            <option value="Solid" {{ $workOrder->sample_matrix == 'Solid' ? 'selected' : '' }}>Solid</option>
                            <option value="Gas" {{ $workOrder->sample_matrix == 'Gas' ? 'selected' : '' }}>Gas</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="amount_of_sample" class="form-label">Number of Samples <span class="text-danger">*</span></label>
                        <input type="number" name="amount_of_sample" id="amount_of_sample" class="form-control" min="1" value="{{ old('amount_of_sample', $workOrder->amount_of_sample) }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="draft" {{ $workOrder->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ $workOrder->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="in_progress" {{ $workOrder->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $workOrder->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $workOrder->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Update Work Order</button>
                <a href="{{ route('work-orders.show', $workOrder) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('addPurposeBtn').addEventListener('click', function() {
        const nameInput = document.getElementById('purpose_name');
        const name = nameInput.value.trim();
        if (!name) { alert('Please type a purpose name.'); return; }
        const data = {
            name: name,
            _token: '{{ csrf_token() }}'
        };
        fetch('{{ route("purposes.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                const select = document.getElementById('purpose_id');
                const opt = document.createElement('option');
                opt.value = result.purpose.id;
                opt.text = result.purpose.name;
                select.appendChild(opt);
                select.value = result.purpose.id;
                nameInput.value = '';
                alert('Purpose added successfully!');
            } else {
                alert('Error: ' + (result.message || 'Could not add purpose.'));
            }
        })
        .catch(err => alert('Network error.'));
    });
</script>
@endpush
@endsection