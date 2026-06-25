@extends('layouts.app')
@section('title', 'Test Parameters')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h2 class="mb-0">Test Parameters</h2>
    <a href="{{ route('test-parameters.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Add Parameter
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Method</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parameters as $param)
                    <tr>
                        <td><strong>{{ $param->code }}</strong></td>
                        <td>{{ $param->name }}</td>
                        <td>{{ $param->category ?? 'N/A' }}</td>
                        <td>{{ $param->unit ?? 'N/A' }}</td>
                        <td>{{ $param->method ?? 'N/A' }}</td>
                        <td>${{ number_format($param->default_price, 2) }}</td>
                        <td>
                            <a href="{{ route('test-parameters.edit', $param) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('test-parameters.destroy', $param) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this parameter?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">No test parameters found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection