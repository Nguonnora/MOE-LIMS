<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\SampleTest;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestResultController extends Controller
{
    public function index(Sample $sample)
    {
        $this->checkPermission('canViewTestResults');
        $sample->load('tests.result');
        return view('test-results.index', compact('sample'));
    }

    public function store(Request $request, Sample $sample, SampleTest $sampleTest)
    {
        $this->checkPermission('canEnterResults');
        // ... store result
    }

    public function update(Request $request, Sample $sample, SampleTest $sampleTest)
    {
        $this->checkPermission('canEnterResults');
        // ... update result
    }
}