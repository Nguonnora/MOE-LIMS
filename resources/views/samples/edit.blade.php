@extends('layouts.app')
@section('title', 'Edit Work Order')
@section('content')
<div class="card">
    <div class="card-header">Edit Work Order: {{ $workOrder->wo_number }}</div>
    <div class="card-body">
        <form action="{{ route('samples.update', $workOrder) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3"><label>Client Name *</label><input type="text" name="client_name" class="form-control" value="{{ old('client_name', $workOrder->client_name) }}" required></div>
                    <div class="mb-3"><label>Email</label><input type="email" name="client_email" class="form-control" value="{{ old('client_email', $workOrder->client_email) }}"></div>
                    <div class="mb-3"><label>Phone</label><input type="text" name="client_phone" class="form-control" value="{{ old('client_phone', $workOrder->client_phone) }}"></div>
                    <div class="mb-3"><label>Organization</label><input type="text" name="client_organization" class="form-control" value="{{ old('client_organization', $workOrder->client_organization) }}"></div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3"><label>Order Date *</label><input type="date" name="order_date" class="form-control" value="{{ old('order_date', $workOrder->order_date->format('Y-m-d')) }}" required></div>
                    <div class="mb-3"><label>Expected Completion</label><input type="date" name="expected_completion_date" class="form-control" value="{{ old('expected_completion_date', $workOrder->expected_completion_date ? $workOrder->expected_completion_date->format('Y-m-d') : '') }}"></div>
                    <div class="mb-3"><label>Priority</label>
                        <select name="priority" class="form-control">
                            <option value="low" {{ $workOrder->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $workOrder->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $workOrder->priority == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                    <div class="mb-3"><label>Status</label>
                        <select name="status" class="form-control">
                            <option value="draft" {{ $workOrder->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ $workOrder->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="in_progress" {{ $workOrder->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $workOrder->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $workOrder->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3"><label>Project Description</label><textarea name="project_description" class="form-control" rows="2">{{ old('project_description', $workOrder->project_description) }}</textarea></div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('samples.show', $workOrder) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection