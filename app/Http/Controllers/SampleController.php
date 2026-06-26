<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Sample;
use App\Models\TestParameter;
use App\Models\Province;
use App\Models\District;
use App\Models\Commune;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SampleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // List work orders to add samples
    public function index()
    {
        $this->checkPermission('canCreateWorkOrder');
        $workOrders = WorkOrder::whereIn('status', ['draft', 'submitted'])
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('samples.index', compact('workOrders'));
    }

    // Show form to add a sample to a specific work order
    public function create(WorkOrder $workOrder)
    {
        $this->checkPermission('canCreateWorkOrder');
        $provinces = Province::orderBy('name')->get();
        $testParameters = TestParameter::orderBy('code')->get();
        return view('samples.create', compact('workOrder', 'provinces', 'testParameters'));
    }

    // Store sample and tests
    public function store(Request $request, WorkOrder $workOrder)
    {
        $this->checkPermission('canCreateWorkOrder');

        $validated = $request->validate([
            'sample_type' => 'required|string|max:255',
            'sample_description' => 'nullable|string',
            'sampling_date' => 'required|date',
            'province_id' => 'nullable|exists:provinces,id',
            'district_id' => 'nullable|exists:districts,id',
            'commune_id' => 'nullable|exists:communes,id',
            'village_id' => 'nullable|exists:villages,id',
            'coordinate_system' => 'required|in:DD,UTM,N/A',
            'coordinate_x' => 'nullable|string|max:50',
            'coordinate_y' => 'nullable|string|max:50',
            'tests' => 'required|array|min:1',
            'tests.*.test_name' => 'required|string',
            'tests.*.test_code' => 'nullable|string',
            'tests.*.test_category' => 'nullable|string',
            'tests.*.parameter' => 'nullable|string',
            'tests.*.unit' => 'nullable|string',
            'tests.*.method' => 'nullable|string',
            'tests.*.reference_method' => 'nullable|string',
            'tests.*.detection_limit' => 'nullable|numeric',
            'tests.*.quantification_limit' => 'nullable|numeric',
            'tests.*.price' => 'nullable|numeric|min:0',
        ]);

        // Determine sequence number for sample code
        $sampleCount = $workOrder->samples()->count() + 1;
        $sampleCode = Sample::generateSampleCode($workOrder->id, $sampleCount);

        $sample = $workOrder->samples()->create([
            'sample_code' => $sampleCode,
            'sample_type' => $validated['sample_type'],
            'sample_description' => $validated['sample_description'],
            'sampling_date' => $validated['sampling_date'],
            'province_id' => $validated['province_id'],
            'district_id' => $validated['district_id'],
            'commune_id' => $validated['commune_id'],
            'village_id' => $validated['village_id'],
            'coordinate_system' => $validated['coordinate_system'],
            'coordinate_x' => $validated['coordinate_x'],
            'coordinate_y' => $validated['coordinate_y'],
            'status' => 'received',
        ]);

        // Create tests
        $totalTestsPrice = 0;
        foreach ($validated['tests'] as $testData) {
            unset($testData['test_parameter_id']);
            $test = $sample->tests()->create($testData);
            $totalTestsPrice += $testData['price'] ?? 0;
            $test->result()->create(['status' => 'pending']);
        }

        // Update work order total and status
        $workOrder->update([
            'total_amount' => $workOrder->total_amount + $totalTestsPrice,
            'status' => 'submitted',
        ]);

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Sample and tests added successfully.');
    }

    // AJAX: get districts by province
    public function getDistricts($provinceId)
    {
        $districts = District::where('province_id', $provinceId)->orderBy('name')->get();
        return response()->json($districts);
    }

    // AJAX: get communes by district
    public function getCommunes($districtId)
    {
        $communes = Commune::where('district_id', $districtId)->orderBy('name')->get();
        return response()->json($communes);
    }

    // AJAX: get villages by commune
    public function getVillages($communeId)
    {
        $villages = Village::where('commune_id', $communeId)->orderBy('name')->get();
        return response()->json($villages);
    }
}