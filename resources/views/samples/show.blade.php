@extends('layouts.app')
@section('title', 'Work Order: ' . $workOrder->wo_number)
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span>Work Order: {{ $workOrder->wo_number }}</span>
        <div>
            @if($workOrder->invoice)
                <a href="{{ route('samples.show', $workOrder) }}?download_invoice=1" class="btn btn-sm btn-primary">Download Invoice</a>
            @endif
            @if($workOrder->report)
                <a href="{{ route('reports.download', $workOrder) }}" class="btn btn-sm btn-success">Download Report</a>
            @elseif($workOrder->status == 'approved')
                <a href="{{ route('reports.generate', $workOrder) }}" class="btn btn-sm btn-warning">Generate Report</a>
            @endif
            <a href="{{ route('samples.index') }}" class="btn btn-sm btn-secondary">Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Client Information</h5>
                <dl class="row"><dt class="col-sm-4">Name</dt><dd class="col-sm-8">{{ $workOrder->client_name }}</dd></dl>
                <dl class="row"><dt class="col-sm-4">Email</dt><dd class="col-sm-8">{{ $workOrder->client_email ?? 'N/A' }}</dd></dl>
                <dl class="row"><dt class="col-sm-4">Phone</dt><dd class="col-sm-8">{{ $workOrder->client_phone ?? 'N/A' }}</dd></dl>
                <dl class="row"><dt class="col-sm-4">Organization</dt><dd class="col-sm-8">{{ $workOrder->client_organization ?? 'N/A' }}</dd></dl>
            </div>
            <div class="col-md-6">
                <h5>Work Order Details</h5>
                <dl class="row"><dt class="col-sm-4">Order Date</dt><dd class="col-sm-8">{{ $workOrder->order_date->format('d/m/Y') }}</dd></dl>
                <dl class="row"><dt class="col-sm-4">Expected Completion</dt><dd class="col-sm-8">{{ $workOrder->expected_completion_date ? $workOrder->expected_completion_date->format('d/m/Y') : 'N/A' }}</dd></dl>
                <dl class="row"><dt class="col-sm-4">Priority</dt><dd class="col-sm-8"><span class="badge bg-{{ $workOrder->priority == 'high' ? 'danger' : ($workOrder->priority == 'medium' ? 'warning' : 'info') }}">{{ ucfirst($workOrder->priority) }}</span></dd></dl>
                <dl class="row"><dt class="col-sm-4">Status</dt><dd class="col-sm-8"><span class="badge bg-{{ $workOrder->status == 'completed' ? 'success' : ($workOrder->status == 'cancelled' ? 'danger' : 'primary') }}">{{ ucfirst($workOrder->status) }}</span></dd></dl>
                <dl class="row"><dt class="col-sm-4">Total</dt><dd class="col-sm-8"><strong>${{ number_format($workOrder->total_amount, 2) }}</strong></dd></dl>
                <dl class="row"><dt class="col-sm-4">Invoice</dt><dd class="col-sm-8">{{ $workOrder->invoice_number ?? 'Not generated' }}</dd></dl>
            </div>
        </div>
        <hr>
        <h5>Samples ({{ $workOrder->samples->count() }})</h5>
        @forelse($workOrder->samples as $sample)
        <div class="border p-3 mb-3">
            <h6>{{ $sample->sample_code }} - {{ $sample->sample_type }}</h6>
            <p>{{ $sample->sample_description }} ({{ $sample->sample_matrix }})</p>
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