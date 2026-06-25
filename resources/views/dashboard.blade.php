@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Work Orders</h5>
                <p class="card-text display-4">{{ $totalWorkOrders }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Total Samples</h5>
                <p class="card-text display-4">{{ $totalSamples }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Pending Approvals</h5>
                <p class="card-text display-4">{{ $pendingApprovals }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">Revenue (All)</h5>
                <p class="card-text display-4">${{ number_format(\App\Models\WorkOrder::sum('total_amount'), 0) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Work Order Status Distribution</div>
            <div class="card-body">
                @if(count($statusCounts) > 0)
                    @php
                        $total = array_sum($statusCounts);
                        $colors = ['primary', 'success', 'warning', 'danger', 'info', 'secondary'];
                        $i = 0;
                    @endphp
                    @foreach($statusCounts as $status => $count)
                        @php
                            $percentage = ($total > 0) ? round(($count / $total) * 100) : 0;
                            $color = $colors[$i % count($colors)];
                            $i++;
                        @endphp
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                <span>{{ $count }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">{{ $percentage }}%</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No work orders yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Recent Work Orders</div>
            <div class="card-body">
                @if($recentWorkOrders->count() > 0)
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>WO #</th>
                                <th>Client</th>
                                <th>Status</th>
                                <th>Samples</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentWorkOrders as $wo)
                            <tr>
                                <td><a href="{{ route('samples.show', $wo) }}">{{ $wo->wo_number }}</a></td>
                                <td>{{ $wo->client_name }}</td>
                                <td><span class="badge bg-{{ $wo->status == 'completed' ? 'success' : ($wo->status == 'cancelled' ? 'danger' : 'primary') }}">{{ ucfirst($wo->status) }}</span></td>
                                <td>{{ $wo->samples->count() }}</td>
                                <td>${{ number_format($wo->total_amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('samples.index') }}" class="btn btn-sm btn-primary">View All</a>
                @else
                    <p class="text-muted">No work orders yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection