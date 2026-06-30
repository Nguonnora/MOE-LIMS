<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Sample;
use App\Models\TestParameter;
use App\Services\GeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SampleController extends Controller
{
    protected $geoService;

    public function __construct(GeoService $geoService)
    {
        $this->middleware('auth');
        $this->geoService = $geoService;
    }

    public function index()
    {
        $this->checkPermission('canCreateWorkOrder');
        $workOrders = WorkOrder::whereIn('status', ['draft', 'submitted'])
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('samples.index', compact('workOrders'));
    }

    public function create(WorkOrder $workOrder)
    {
        $this->checkPermission('canCreateWorkOrder');
        $provinces = $this->geoService->getProvinces();
        $testParameters = TestParameter::where(function ($query) use ($workOrder) {
            $matrix = $workOrder->sample_matrix;
            $query->where('matrix', $matrix)->orWhereNull('matrix');
        })->orderBy('code')->get();

        return view('samples.create', compact('workOrder', 'provinces', 'testParameters'));
    }

    public function store(Request $request, WorkOrder $workOrder)
    {
        $this->checkPermission('canCreateWorkOrder');

        $validated = $request->validate([
            'sample_type' => 'required|string|max:255',
            'sample_description' => 'nullable|string',
            'sampling_date' => 'required|date',
            'province_id' => 'nullable|string',
            'district_id' => 'nullable|string',
            'commune_id' => 'nullable|string',
            'village_id' => 'nullable|string',
            'coordinate_system' => 'required|in:DD,UTM,N/A',
            'coordinate_x' => 'nullable|string|max:50',
            'coordinate_y' => 'nullable|string|max:50',
            'test_ids' => 'required|array|min:1',
            'test_ids.*' => 'exists:test_parameters,id',
        ]);

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

        $totalTestsPrice = 0;
        foreach ($validated['test_ids'] as $testId) {
            $parameter = TestParameter::findOrFail($testId);
            $test = $sample->tests()->create([
                'test_code' => $parameter->code,
                'test_name' => $parameter->name,
                'test_category' => $parameter->category,
                'parameter' => $parameter->name,
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

        $workOrder->update([
            'total_amount' => $workOrder->total_amount + $totalTestsPrice,
            'status' => 'submitted',
        ]);

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Sample and tests added successfully.');
    }

    // ---- AJAX endpoints ----
    public function getDistricts($provinceId)
    {
        $districts = $this->geoService->getDistricts($provinceId);
        \Log::info("Districts for province $provinceId: " . count($districts) . " found.");
        return response()->json($districts);
    }

    public function getCommunes($districtId)
    {
        \Log::info("AJAX request for communes with districtId: $districtId");
        $communes = $this->geoService->getCommunes($districtId);
        return response()->json($communes);
    }

    public function getVillages($communeId)
    {
        $villages = $this->geoService->getVillages($communeId);
        return response()->json($villages);
    }
}