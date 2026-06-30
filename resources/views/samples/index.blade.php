@extends('layouts.app')
@section('title', 'Sample Registration')
@section('content')
<div class="card">
    <div class="card-header">Select a Work Order to Add Samples</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>WO #</th>
                        <th>Client</th>
                        <th>Reception Date</th>
                        <th>Registered Samples</th>
                        <th>Total Samples</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workOrders as $wo)
                    <tr>
                        <td><strong>{{ $wo->wo_number }}</strong></td>
                        <td>{{ $wo->client->name ?? 'N/A' }}</td>
                        <td>{{ $wo->reception_date ? $wo->reception_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $wo->samples->count() }}</td>
                        <td>{{ $wo->amount_of_sample }}</td>
                        <td><span class="badge bg-{{ $wo->status == 'completed' ? 'success' : ($wo->status == 'cancelled' ? 'danger' : 'primary') }}">{{ ucfirst($wo->status) }}</span></td>
                        <td>
                            @if($wo->samples->count() < $wo->amount_of_sample && in_array($wo->status, ['draft', 'submitted']))
                                <a href="{{ route('samples.create', $wo) }}" class="btn btn-sm btn-primary">Add Sample</a>
                            @else
                                <span class="text-muted">Complete</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7">No work orders available for sample registration. Please create a work order first.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection