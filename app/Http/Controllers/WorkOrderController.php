<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Client;
use App\Models\Purpose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    public function index()
    {
        $this->checkPermission('canViewWorkOrders');
        $workOrders = WorkOrder::with('client', 'samples', 'purpose')->orderBy('created_at', 'desc')->get();
        return view('work-orders.index', compact('workOrders'));
    }

    public function create()
    {
        $this->checkPermission('canCreateWorkOrder');
        $clients = Client::where('is_active', true)->orderBy('name')->get();
        $purposes = Purpose::orderBy('name')->get();
        return view('work-orders.create', compact('clients', 'purposes'));
    }

    public function store(Request $request)
    {
        $this->checkPermission('canCreateWorkOrder');

        $validated = $request->validate([
            'reception_date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'project_description' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'purpose_id' => 'nullable|exists:purposes,id',
            'purpose_name' => 'nullable|string|max:255|required_without:purpose_id',
            'priority' => 'required|in:low,medium,high',
            'sample_matrix' => 'nullable|string|max:255',
            'amount_of_sample' => 'required|integer|min:1',
        ]);

        // Handle purpose
        $purposeId = $validated['purpose_id'];
        if (empty($purposeId) && !empty($validated['purpose_name'])) {
            $purpose = Purpose::create(['name' => $validated['purpose_name']]);
            $purposeId = $purpose->id;
        }

        $workOrder = WorkOrder::create([
            'wo_number' => WorkOrder::generateWONumber(),
            'reception_date' => $validated['reception_date'],
            'client_id' => $validated['client_id'],
            'project_description' => $validated['project_description'],
            'contact_person' => $validated['contact_person'],
            'phone' => $validated['phone'],
            'purpose_id' => $purposeId,
            'priority' => $validated['priority'],
            'sample_matrix' => $validated['sample_matrix'],
            'amount_of_sample' => $validated['amount_of_sample'],
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work Order created. Now you can register samples.');
    }

    public function show(WorkOrder $workOrder)
    {
        $this->checkPermission('canViewWorkOrders');
        $workOrder->load('client', 'purpose', 'samples.province', 'samples.district', 'samples.commune', 'samples.village', 'samples.tests');
        return view('work-orders.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        $this->checkPermission('canEditWorkOrder');
        $clients = Client::where('is_active', true)->orderBy('name')->get();
        $purposes = Purpose::orderBy('name')->get();
        return view('work-orders.edit', compact('workOrder', 'clients', 'purposes'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $this->checkPermission('canEditWorkOrder');
        $validated = $request->validate([
            'reception_date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'project_description' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'purpose_id' => 'nullable|exists:purposes,id',
            'purpose_name' => 'nullable|string|max:255|required_without:purpose_id',
            'priority' => 'required|in:low,medium,high',
            'sample_matrix' => 'nullable|string|max:255',
            'amount_of_sample' => 'required|integer|min:1',
            'status' => 'required|in:draft,submitted,in_progress,completed,cancelled',
        ]);

        $purposeId = $validated['purpose_id'];
        if (empty($purposeId) && !empty($validated['purpose_name'])) {
            $purpose = Purpose::create(['name' => $validated['purpose_name']]);
            $purposeId = $purpose->id;
        }

        $workOrder->update([
            'reception_date' => $validated['reception_date'],
            'client_id' => $validated['client_id'],
            'project_description' => $validated['project_description'],
            'contact_person' => $validated['contact_person'],
            'phone' => $validated['phone'],
            'purpose_id' => $purposeId,
            'priority' => $validated['priority'],
            'sample_matrix' => $validated['sample_matrix'],
            'amount_of_sample' => $validated['amount_of_sample'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work Order updated.');
    }

    public function destroy(WorkOrder $workOrder)
    {
        $this->checkPermission('canDeleteWorkOrder');
        $workOrder->delete();
        return redirect()->route('work-orders.index')->with('success', 'Work Order deleted.');
    }

    // AJAX: create a new purpose
    public function storePurpose(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:purposes']);
        $purpose = Purpose::create(['name' => $request->name]);
        return response()->json(['success' => true, 'purpose' => $purpose]);
    }
}