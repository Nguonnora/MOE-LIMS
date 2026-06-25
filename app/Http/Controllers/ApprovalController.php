<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function pending()
    {
        $pendingResults = TestResult::with(['sampleTest.sample.workOrder', 'enteredBy'])
            ->where('status', 'entered')->orderBy('entered_at')->get();
        return view('approvals.pending', compact('pendingResults'));
    }

    public function approve(Request $request, TestResult $testResult)
    {
        if ($testResult->status !== 'entered') abort(422, 'Not ready for approval.');
        $testResult->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        $sample = $testResult->sampleTest->sample;
        if ($sample->tests->every(fn($t) => $t->result && $t->result->status == 'approved')) {
            $sample->update(['status' => 'approved']);
        }
        return redirect()->route('approvals.pending')->with('success', 'Result approved.');
    }

    public function reject(Request $request, TestResult $testResult)
    {
        if ($testResult->status !== 'entered') abort(422, 'Not ready for rejection.');
        $testResult->update([
            'status' => 'rejected',
            'remarks' => $request->input('rejection_reason') ?: 'Rejected by approver',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        return redirect()->route('approvals.pending')->with('success', 'Result rejected.');
    }
}