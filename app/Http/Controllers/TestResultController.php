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
        $sample->load('tests.result');
        return view('test-results.index', compact('sample'));
    }

    public function store(Request $request, Sample $sample, SampleTest $sampleTest)
    {
        $request->validate([
            'result_value' => 'required|numeric',
            'remarks' => 'nullable|string',
        ]);

        $result = $sampleTest->result ?: new TestResult(['sample_test_id' => $sampleTest->id]);
        $result->fill([
            'result_value' => $request->result_value,
            'remarks' => $request->remarks,
            'status' => 'entered',
            'entered_by' => Auth::id(),
            'entered_at' => now(),
        ]);
        $result->save();

        $sample->load('tests.result');
        if ($sample->tests->every(fn($t) => $t->result && $t->result->status == 'entered')) {
            $sample->update(['status' => 'results_entered']);
        }

        return redirect()->route('samples.tests.index', $sample)->with('success', 'Result saved.');
    }

    public function update(Request $request, Sample $sample, SampleTest $sampleTest)
    {
        $request->validate([
            'result_value' => 'required|numeric',
            'remarks' => 'nullable|string',
        ]);
        $result = $sampleTest->result;
        if (!$result) abort(404);
        $result->update([
            'result_value' => $request->result_value,
            'remarks' => $request->remarks,
            'status' => 'entered',
            'entered_by' => Auth::id(),
            'entered_at' => now(),
        ]);
        return redirect()->route('samples.tests.index', $sample)->with('success', 'Result updated.');
    }
}