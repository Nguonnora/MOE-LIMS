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
            'test_ids' => 'required|array|min:1',   // at least one test must be selected
            'test_ids.*' => 'exists:test_parameters,id',
        ]);

        // Create Sample
        $sample = $workOrder->samples()->create([
            'sample_code' => Sample::generateSampleCode($workOrder->id, $workOrder->samples()->count() + 1),
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

        // Create Tests from selected test parameters
        $totalTestsPrice = 0;
        foreach ($validated['test_ids'] as $testId) {
            $parameter = TestParameter::findOrFail($testId);
            $test = $sample->tests()->create([
                'test_code' => $parameter->code,
                'test_name' => $parameter->name,
                'test_category' => $parameter->category,
                'parameter' => $parameter->name, // use name as parameter
                'unit' => $parameter->unit,
                'method' => $parameter->method,
                'reference_method' => $parameter->reference_method,
                'detection_limit' => $parameter->detection_limit,
                'quantification_limit' => $parameter->quantification_limit,
                'accreditation' => $parameter->accreditation,
                'is_subcontracted' => $parameter->is_subcontracted,
                'subcontracted_lab' => $parameter->subcontracted_lab,
                'price' => $parameter->default_price,
            ]);
            $totalTestsPrice += $parameter->default_price ?? 0;
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