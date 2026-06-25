@extends('layouts.app')
@section('title', 'Work Orders')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h2 class="mb-0">Work Orders</h2>
    @if(in_array(auth()->user()->role, ['admin', 'receptionist']))
        <a href="{{ route('samples.create') }}" class="btn btn-success">
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
                        <th>Order Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Samples</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($workOrders as $wo)
                    <tr>
                        <td><strong>{{ $wo->wo_number }}</strong></td>
                        <td>{{ $wo->client_name }}</td>
                        <td>{{ $wo->order_date->format('d/m/Y') }}</td>
                        <td><span class="badge bg-{{ $wo->priority == 'high' ? 'danger' : ($wo->priority == 'medium' ? 'warning' : 'info') }}">{{ ucfirst($wo->priority) }}</span></td>
                        <td><span class="badge bg-{{ $wo->status == 'completed' ? 'success' : ($wo->status == 'cancelled' ? 'danger' : 'primary') }}">{{ ucfirst($wo->status) }}</span></td>
                        <td>{{ $wo->samples->count() }}</td>
                        <td>${{ number_format($wo->total_amount, 2) }}</td>
                        <td>
                            @php
                                $canEdit = in_array(auth()->user()->role, ['admin', 'receptionist']);
                                $canDelete = auth()->user()->role == 'admin';
                            @endphp
                            <a href="{{ route('samples.show', $wo) }}" class="btn btn-sm btn-info">View</a>
                            @if($canEdit)
                                <a href="{{ route('samples.edit', $wo) }}" class="btn btn-sm btn-warning">Edit</a>
                            @endif
                            @if($canDelete)
                                <form action="{{ route('samples.destroy', $wo) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this work order?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
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