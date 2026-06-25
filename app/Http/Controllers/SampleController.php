<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Sample;
use App\Models\SampleTest;
use App\Models\TestParameter;
use App\Models\Client;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SampleController extends Controller
{
    public function index()
    {
        $this->checkPermission('canViewWorkOrders');
        $workOrders = WorkOrder::with('samples', 'creator', 'client')->orderBy('created_at', 'desc')->get();
        return view('samples.index', compact('workOrders'));
    }

    public function create()
    {
        $this->checkPermission('canCreateWorkOrder');
        $clients = Client::where('is_active', true)->orderBy('name')->get();
        $testParameters = TestParameter::orderBy('code')->get();
        return view('samples.create', compact('clients', 'testParameters'));
    }

    public function store(Request $request)
    {
        $this->checkPermission('canCreateWorkOrder');

        $validated = $request->validate([
            // Work Order
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string|max:20',
            'client_organization' => 'nullable|string|max:255',
            'project_description' => 'nullable|string',
            'order_date' => 'required|date',
            'expected_completion_date' => 'nullable|date|after:order_date',
            'priority' => 'required|in:low,medium,high',
            // Sample
            'sample_type' => 'required|string|max:255',
            'sample_matrix' => 'nullable|string',
            'sampling_location' => 'nullable|string',
            'sampling_date' => 'required|date',
            'sampling_time' => 'nullable',
            'sampling_method' => 'nullable|string',
            'container_type' => 'nullable|string',
            'preservation_method' => 'nullable|string',
            'received_date' => 'required|date',
            'received_time' => 'nullable',
            'received_by' => 'nullable|string',
            'sample_description' => 'nullable|string',
            'sample_condition' => 'nullable|string',
            'sample_quantity' => 'nullable|numeric',
            'quantity_unit' => 'nullable|string',
            // Tests
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

        // If client_id is provided, use client data from the Client model
        $client = null;
        if ($validated['client_id']) {
            $client = Client::find($validated['client_id']);
        }

        // Create Work Order
        $workOrder = WorkOrder::create([
            'wo_number' => WorkOrder::generateWONumber(),
            'client_id' => $validated['client_id'],
            'client_name' => $client ? $client->name : $validated['client_name'],
            'client_email' => $client ? $client->email : $validated['client_email'],
            'client_phone' => $client ? $client->phone : $validated['client_phone'],
            'client_organization' => $client ? $client->organization : $validated['client_organization'],
            'project_description' => $validated['project_description'],
            'order_date' => $validated['order_date'],
            'expected_completion_date' => $validated['expected_completion_date'],
            'priority' => $validated['priority'],
            'status' => 'submitted',
            'created_by' => Auth::id(),
        ]);

        // Create Sample
        $sample = $workOrder->samples()->create([
            'sample_code' => Sample::generateSampleCode($workOrder->id),
            'sample_type' => $validated['sample_type'],
            'sample_matrix' => $validated['sample_matrix'],
            'sampling_location' => $validated['sampling_location'],
            'sampling_date' => $validated['sampling_date'],
            'sampling_time' => $validated['sampling_time'],
            'sampling_method' => $validated['sampling_method'],
            'container_type' => $validated['container_type'],
            'preservation_method' => $validated['preservation_method'],
            'received_date' => $validated['received_date'],
            'received_time' => $validated['received_time'],
            'received_by' => $validated['received_by'],
            'sample_description' => $validated['sample_description'],
            'sample_condition' => $validated['sample_condition'],
            'sample_quantity' => $validated['sample_quantity'],
            'quantity_unit' => $validated['quantity_unit'],
            'status' => 'received',
        ]);

        // Create Tests
        $total = 0;
        foreach ($validated['tests'] as $testData) {
            unset($testData['test_parameter_id']);
            $test = $sample->tests()->create($testData);
            $total += $testData['price'] ?? 0;
            $test->result()->create(['status' => 'pending']);
        }
        $workOrder->update(['total_amount' => $total]);

        // Generate Invoice
        $invoiceService = new InvoiceService();
        $invoiceService->generateInvoice($workOrder);

        return redirect()->route('samples.index')->with('success', 'Work Order & Sample created.');
    }

    public function show(WorkOrder $workOrder)
    {
        $this->checkPermission('canViewWorkOrders');
        $workOrder->load('samples.tests.result', 'invoice', 'report', 'client');
        return view('samples.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        $this->checkPermission('canEditWorkOrder');
        $workOrder->load('samples.tests');
        $clients = Client::where('is_active', true)->orderBy('name')->get();
        return view('samples.edit', compact('workOrder', 'clients'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $this->checkPermission('canEditWorkOrder');
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string|max:20',
            'client_organization' => 'nullable|string|max:255',
            'project_description' => 'nullable|string',
            'order_date' => 'required|date',
            'expected_completion_date' => 'nullable|date|after:order_date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:draft,submitted,in_progress,completed,cancelled',
        ]);

        // If client_id is provided, use client data from the Client model
        $client = null;
        if ($validated['client_id']) {
            $client = Client::find($validated['client_id']);
        }

        $workOrder->update([
            'client_id' => $validated['client_id'],
            'client_name' => $client ? $client->name : $validated['client_name'],
            'client_email' => $client ? $client->email : $validated['client_email'],
            'client_phone' => $client ? $client->phone : $validated['client_phone'],
            'client_organization' => $client ? $client->organization : $validated['client_organization'],
            'project_description' => $validated['project_description'],
            'order_date' => $validated['order_date'],
            'expected_completion_date' => $validated['expected_completion_date'],
            'priority' => $validated['priority'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('samples.show', $workOrder)->with('success', 'Work Order updated.');
    }

    public function destroy(WorkOrder $workOrder)
    {
        $this->checkPermission('canDeleteWorkOrder');
        $workOrder->delete();
        return redirect()->route('samples.index')->with('success', 'Work Order deleted.');
    }
}