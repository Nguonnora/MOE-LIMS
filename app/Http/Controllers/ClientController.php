<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except(['index', 'create', 'store']);
    }

    public function index()
    {
        $this->checkPermission('canViewWorkOrders');
        $clients = Client::orderBy('name')->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $this->checkPermission('canCreateWorkOrder');
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $this->checkPermission('canCreateWorkOrder');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'organization' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully.');
    }

    public function edit(Client $client)
    {
        $this->checkPermission('canEditWorkOrder');
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $this->checkPermission('canEditWorkOrder');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'organization' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $this->checkPermission('canDeleteWorkOrder');

        if ($client->workOrders()->count() > 0) {
            return redirect()->route('clients.index')
                ->with('error', 'Cannot delete client with existing work orders.');
        }

        $client->delete();
        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $clients = Client::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('organization', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json($clients);
    }
}