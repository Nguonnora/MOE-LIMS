@extends('layouts.app')
@section('title', 'Pending Approvals')
@section('content')
<div class="card">
    <div class="card-header">Pending Approvals</div>
    <div class="card-body">
        <table class="table table-striped">
            <thead><tr><th>WO</th><th>Sample</th><th>Test</th><th>Result</th><th>Entered By</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($pendingResults as $result)
                <tr>
                    <td>{{ $result->sampleTest->sample->workOrder->wo_number }}</td>
                    <td>{{ $result->sampleTest->sample->sample_code }}</td>
                    <td>{{ $result->sampleTest->test_name }}</td>
                    <td>{{ $result->result_value }}</td>
                    <td>{{ $result->enteredBy->name ?? 'N/A' }}</td>
                    <td>
                        <form action="{{ route('approvals.approve', $result) }}" method="POST" style="display:inline-block;">
                            @csrf <button class="btn btn-sm btn-success">Approve</button>
                        </form>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $result->id }}">Reject</button>
                    </td>
                </tr>
                <div class="modal fade" id="rejectModal{{ $result->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('approvals.reject', $result) }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header"><h5>Reject Result</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                <div class="modal-body"><textarea name="rejection_reason" class="form-control" placeholder="Reason for rejection"></textarea></div>
                                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Reject</button></div>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr><td colspan="6">No pending approvals.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection