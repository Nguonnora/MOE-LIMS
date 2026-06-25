<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function pending()
    {
        $this->checkPermission('canApproveResults');
        $pendingResults = TestResult::with(['sampleTest.sample.workOrder', 'enteredBy'])
            ->where('status', 'entered')->orderBy('entered_at')->get();
        return view('approvals.pending', compact('pendingResults'));
    }

    public function approve(Request $request, TestResult $testResult)
    {
        $this->checkPermission('canApproveResults');
        // ... approve logic
    }

    public function reject(Request $request, TestResult $testResult)
    {
        $this->checkPermission('canApproveResults');
        // ... reject logic
    }
}