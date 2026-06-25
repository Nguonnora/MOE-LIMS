@extends('layouts.app')
@section('title', 'Test Results')
@section('content')
<div class="card">
    <div class="card-header">Test Results for Sample: {{ $sample->sample_code }}</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Test Name</th><th>Parameter</th><th>Result</th><th>Remarks</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                @foreach($sample->tests as $test)
                <tr>
                    <td>{{ $test->test_name }}</td>
                    <td>{{ $test->parameter }}</td>
                    <td>{{ $test->result->result_value ?? 'N/A' }}</td>
                    <td>{{ $test->result->remarks ?? '' }}</td>
                    <td><span class="badge bg-{{ $test->result->status == 'approved' ? 'success' : ($test->result->status == 'entered' ? 'warning' : 'secondary') }}">{{ ucfirst($test->result->status ?? 'pending') }}</span></td>
                    <td>
                        @if(!$test->result || $test->result->status != 'approved')
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#resultModal{{ $test->id }}">Enter Result</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@foreach($sample->tests as $test)
<div class="modal fade" id="resultModal{{ $test->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ $test->result ? route('samples.tests.result.update', [$sample, $test]) : route('samples.tests.result.store', [$sample, $test]) }}" method="POST">
            @csrf @if($test->result) @method('PUT') @endif
            <div class="modal-content">
                <div class="modal-header"><h5>Result for {{ $test->test_name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label>Result Value *</label><input type="number" step="any" name="result_value" class="form-control" value="{{ $test->result->result_value ?? '' }}" required></div>
                    <div class="mb-3"><label>Remarks</label><textarea name="remarks" class="form-control">{{ $test->result->remarks ?? '' }}</textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success">Save</button></div>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection