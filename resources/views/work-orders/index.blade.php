@extends('layouts.app')
@section('title', 'Work Orders')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h2 class="mb-0">Work Orders</h2>
    @if(in_array(auth()->user()->role, ['admin', 'receptionist']))
        <a href="{{ route('work-orders.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> New Work Order
        </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>WO #</th>
                        <th>Client</th>
                        <th>Reception Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Samples</th>
                        <th>Sample Qty</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workOrders as $wo)
                    <tr>
                        <td><strong>{{ $wo->wo_number }}</strong></td>
                        <td>{{ $wo->client->name ?? 'N/A' }}</td>
                        <td>{{ $wo->reception_date ? $wo->reception_date->format('d/m/Y') : '-' }}</td>
                        <td><span class="badge bg-{{ $wo->priority == 'high' ? 'danger' : ($wo->priority == 'medium' ? 'warning' : 'info') }}">{{ ucfirst($wo->priority) }}</span></td>
                        <td><span class="badge bg-{{ $wo->status == 'completed' ? 'success' : ($wo->status == 'cancelled' ? 'danger' : 'primary') }}">{{ ucfirst($wo->status) }}</span></td>
                        <td>{{ $wo->samples->count() }}</td>
                        <td>{{ $wo->amount_of_sample }}</td>
                        <td>
                            <a href="{{ route('work-orders.show', $wo) }}" class="btn btn-sm btn-info">View</a>
                            @if(in_array(auth()->user()->role, ['admin', 'receptionist']))
                                <a href="{{ route('work-orders.edit', $wo) }}" class="btn btn-sm btn-warning">Edit</a>
                            @endif
                            @if(auth()->user()->role == 'admin')
                                <form action="{{ route('work-orders.destroy', $wo) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this work order?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @endif
                            @if(in_array($wo->status, ['draft', 'submitted']) && $wo->samples->count() < $wo->amount_of_sample && in_array(auth()->user()->role, ['admin', 'receptionist']))
                                {{-- Use the correct route: samples.create with workOrder parameter --}}
                                <a href="{{ route('samples.create', $wo) }}" class="btn btn-sm btn-primary">Add Sample</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">No work orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection