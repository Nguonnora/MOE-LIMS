@extends('layouts.app')
@section('title', 'New Work Order')
@section('content')
<div class="card">
    <div class="card-header">New Work Order</div>
    <div class="card-body">
        <form action="{{ route('work-orders.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="reception_date" class="form-label">Reception Date <span class="text-danger">*</span></label>
                        <input type="date" name="reception_date" id="reception_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="wo_number" class="form-label">Work Order ID</label>
                        <input type="text" class="form-control" value="Auto-generated" readonly style="background:#f0f0f0;">
                        <small class="text-muted">Format: YYMMDD-### (automatically assigned on save)</small>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Client Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="client_id" id="client_id" class="form-select" required>
                                <option value="">-- Select a client --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-success" type="button" id="addClientBtn" data-bs-toggle="modal" data-bs-target="#addClientModal">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="project_description" class="form-label">Project Description</label>
                        <textarea name="project_description" id="project_description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" id="contact_person" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone (Telegram)</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="purpose_id" class="form-label">Purpose of Analysis</label>
                        <div class="input-group">
                            <select name="purpose_id" id="purpose_id" class="form-select">
                                <option value="">-- Select a purpose --</option>
                                @foreach($purposes as $purpose)
                                    <option value="{{ $purpose->id }}">{{ $purpose->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="purpose_name" id="purpose_name" class="form-control" placeholder="Or type new" style="flex:0 0 180px;">
                            <button class="btn btn-outline-success" type="button" id="addPurposeBtn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <small class="text-muted">Select existing or type a new one.</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                        <select name="priority" id="priority" class="form-select" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="sample_matrix" class="form-label">Sample Matrix</label>
                        <select name="sample_matrix" id="sample_matrix" class="form-select">
                            <option value="">Select</option>
                            <option value="Water">Water</option>
                            <option value="Soil">Soil</option>
                            <option value="Air">Air</option>
                            <option value="Wastewater">Wastewater</option>
                            <option value="Sediment">Sediment</option>
                            <option value="Biological">Biological</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="amount_of_sample" class="form-label">Number of Samples <span class="text-danger">*</span></label>
                        <input type="number" name="amount_of_sample" id="amount_of_sample" class="form-control" min="1" value="1" required>
                        <small class="text-muted">How many samples will be collected?</small>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-success">Create Work Order</button>
                <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addClientForm">
                    @csrf
                    <div class="mb-3"><label>Name *</label><input type="text" id="new_client_name" class="form-control" required></div>
                    <div class="mb-3"><label>Email</label><input type="email" id="new_client_email" class="form-control"></div>
                    <div class="mb-3"><label>Phone</label><input type="text" id="new_client_phone" class="form-control"></div>
                    <div class="mb-3"><label>Organization</label><input type="text" id="new_client_organization" class="form-control"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="saveClientBtn">Save Client</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Quick Client Add (AJAX)
    document.getElementById('saveClientBtn').addEventListener('click', function() {
        const name = document.getElementById('new_client_name').value.trim();
        if (!name) { alert('Client name is required.'); return; }
        const data = {
            name: name,
            email: document.getElementById('new_client_email').value,
            phone: document.getElementById('new_client_phone').value,
            organization: document.getElementById('new_client_organization').value,
            is_active: 1,
            _token: '{{ csrf_token() }}'
        };
        fetch('{{ route("clients.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                const select = document.getElementById('client_id');
                const opt = document.createElement('option');
                opt.value = result.client.id;
                opt.text = result.client.name;
                select.appendChild(opt);
                select.value = result.client.id;
                const modal = bootstrap.Modal.getInstance(document.getElementById('addClientModal'));
                modal.hide();
                alert('Client added!');
            } else {
                alert('Error: ' + (result.message || 'Unknown error'));
            }
        })
        .catch(err => alert('Network error.'));
    });
    document.getElementById('addClientModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('addClientForm').reset();
    });

    // Quick Purpose Add (AJAX)
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
                alert('Purpose added!');
            } else {
                alert('Error: ' + (result.message || 'Unknown error'));
            }
        })
        .catch(err => alert('Network error.'));
    });
</script>
@endpush
@endsection