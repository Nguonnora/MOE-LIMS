@extends('layouts.app')
@section('title', 'Work Order: ' . $workOrder->wo_number)
@section('content')
<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span>Work Order: {{ $workOrder->wo_number }}</span>
        <div class="d-flex flex-wrap gap-2">
            @if($workOrder->invoice)
                <a href="{{ route('work-orders.show', $workOrder) }}?download_invoice=1" class="btn btn-sm btn-primary">Download Invoice</a>
            @endif
            @if($workOrder->report)
                <a href="{{ route('reports.download', $workOrder) }}" class="btn btn-sm btn-success">Download Report</a>
            @elseif($workOrder->status == 'approved' && in_array(auth()->user()->role, ['admin', 'approver']))
                <a href="{{ route('reports.generate', $workOrder) }}" class="btn btn-sm btn-warning">Generate Report</a>
            @endif
            @if(in_array(auth()->user()->role, ['admin', 'receptionist']))
                <a href="{{ route('work-orders.edit', $workOrder) }}" class="btn btn-sm btn-warning">Edit</a>
            @endif
            @if(auth()->user()->role == 'admin')
                <form action="{{ route('work-orders.destroy', $workOrder) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this work order?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            @endif
            @if(in_array($workOrder->status, ['draft', 'submitted']) && $workOrder->samples->count() < $workOrder->amount_of_sample && in_array(auth()->user()->role, ['admin', 'receptionist']))
                <a href="{{ route('samples.create', $workOrder) }}" class="btn btn-sm btn-primary">Add Sample</a>
            @endif
            <a href="{{ route('work-orders.index') }}" class="btn btn-sm btn-secondary">Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Client Information</h5>
                <dl class="row">
                    <dt class="col-sm-4">Name</dt><dd class="col-sm-8">{{ $workOrder->client->name ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Contact Person</dt><dd class="col-sm-8">{{ $workOrder->contact_person ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Phone</dt><dd class="col-sm-8">{{ $workOrder->phone ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Purpose</dt><dd class="col-sm-8">{{ $workOrder->purpose->name ?? 'N/A' }}</dd>
                </dl>
            </div>
            <div class="col-md-6">
                <h5>Work Order Details</h5>
                <dl class="row">
                    <dt class="col-sm-4">Reception Date</dt><dd class="col-sm-8">{{ $workOrder->reception_date ? $workOrder->reception_date->format('d/m/Y') : 'N/A' }}</dd>
                    <dt class="col-sm-4">Priority</dt><dd class="col-sm-8"><span class="badge bg-{{ $workOrder->priority == 'high' ? 'danger' : ($workOrder->priority == 'medium' ? 'warning' : 'info') }}">{{ ucfirst($workOrder->priority) }}</span></dd>
                    <dt class="col-sm-4">Status</dt><dd class="col-sm-8"><span class="badge bg-{{ $workOrder->status == 'completed' ? 'success' : ($workOrder->status == 'cancelled' ? 'danger' : 'primary') }}">{{ ucfirst($workOrder->status) }}</span></dd>
                    <dt class="col-sm-4">Sample Matrix</dt><dd class="col-sm-8">{{ $workOrder->sample_matrix ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Sample Quantity</dt><dd class="col-sm-8">{{ $workOrder->amount_of_sample }}</dd>
                    <dt class="col-sm-4">Registered Samples</dt><dd class="col-sm-8">{{ $workOrder->samples->count() }}</dd>
                    <dt class="col-sm-4">Invoice</dt><dd class="col-sm-8">{{ $workOrder->invoice_number ?? 'Not generated' }}</dd>
                </dl>
            </div>
        </div>
        @if($workOrder->project_description)
        <div class="mt-3">
            <h5>Project Description</h5>
            <p>{{ $workOrder->project_description }}</p>
        </div>
        @endif
        <hr>
        <h5>Samples ({{ $workOrder->samples->count() }})</h5>
        @forelse($workOrder->samples as $sample)
        <div class="border p-3 mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                <h6>{{ $sample->sample_code }} - {{ $sample->sample_type }}</h6>
                @if(in_array(auth()->user()->role, ['admin', 'technician']))
                    <a href="{{ route('samples.tests.index', $sample) }}" class="btn btn-sm btn-primary">Enter Results</a>
                @endif
            </div>
            <p><strong>Sampling Date:</strong> {{ $sample->sampling_date->format('d/m/Y') }}</p>
            <p>{{ $sample->sample_description }}</p>
            <p>
                <strong>Location:</strong>
                @if($sample->village) {{ $sample->village->name }},
                @endif
                @if($sample->commune) {{ $sample->commune->name }},
                @endif
                @if($sample->district) {{ $sample->district->name }},
                @endif
                @if($sample->province) {{ $sample->province->name }}
                @endif
            </p>
            <p><strong>Coordinate System:</strong> {{ $sample->coordinate_system }}</p>
            @if($sample->coordinate_system != 'N/A')
                <p><strong>X:</strong> {{ $sample->coordinate_x }} | <strong>Y:</strong> {{ $sample->coordinate_y }}</p>
            @endif
            @if($sample->tests->count())
            <table class="table table-sm table-bordered">
                <thead><tr><th>Test</th><th>Parameter</th><th>Result</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($sample->tests as $test)
                    <tr>
                        <td>{{ $test->test_name }}</td>
                        <td>{{ $test->parameter ?? 'N/A' }}</td>
                        <td>{{ $test->result->result_value ?? 'Pending' }}</td>
                        <td><span class="badge bg-{{ $test->result->status == 'approved' ? 'success' : ($test->result->status == 'entered' ? 'warning' : 'secondary') }}">{{ ucfirst($test->result->status ?? 'pending') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        @empty
        <p>No samples.</p>
        @endforelse
    </div>
</div>
@endsection