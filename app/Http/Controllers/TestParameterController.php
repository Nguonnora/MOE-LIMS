<?php

namespace App\Http\Controllers;

use App\Models\TestParameter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TestParameterController extends Controller
{
    public function index()
    {
        $parameters = TestParameter::orderBy('code')->get();
        return view('test-parameters.index', compact('parameters'));
    }

    public function create()
    {
        return view('test-parameters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'matrix' => 'nullable|string|in:Liquid,Solid,Gas',
            'unit' => 'nullable|string|max:50',
            'method' => 'nullable|string|max:255',
            'reference_method' => 'nullable|string|max:255',
            'detection_limit' => 'nullable|numeric',
            'quantification_limit' => 'nullable|numeric',
            'accreditation' => 'nullable|string|max:100',
            'is_subcontracted' => 'boolean',
            'default_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // Auto-generate code based on matrix
        $validated['code'] = TestParameter::generateCode($validated['matrix'] ?? null);
        $validated['is_subcontracted'] = $request->has('is_subcontracted');

        TestParameter::create($validated);

        return redirect()->route('test-parameters.index')
            ->with('success', 'Test parameter created successfully.');
    }

    public function edit(TestParameter $testParameter)
    {
        return view('test-parameters.edit', compact('testParameter'));
    }

    public function update(Request $request, TestParameter $testParameter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'matrix' => 'nullable|string|in:Liquid,Solid,Gas',
            'unit' => 'nullable|string|max:50',
            'method' => 'nullable|string|max:255',
            'reference_method' => 'nullable|string|max:255',
            'detection_limit' => 'nullable|numeric',
            'quantification_limit' => 'nullable|numeric',
            'accreditation' => 'nullable|string|max:100',
            'is_subcontracted' => 'boolean',
            'default_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['is_subcontracted'] = $request->has('is_subcontracted');

        // If matrix changed, regenerate code
        if ($testParameter->matrix != $validated['matrix']) {
            $validated['code'] = TestParameter::generateCode($validated['matrix'] ?? null);
        }

        $testParameter->update($validated);

        return redirect()->route('test-parameters.index')
            ->with('success', 'Test parameter updated successfully.');
    }

    public function destroy(TestParameter $testParameter)
    {
        $testParameter->delete();
        return redirect()->route('test-parameters.index')
            ->with('success', 'Test parameter deleted successfully.');
    }
}