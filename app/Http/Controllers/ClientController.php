<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        
        $clients = Client::with(['agent', 'creator'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('customer_number', 'like', "%{$search}%")
                      ->orWhere('company_name', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        $statuses = Client::getStatuses();
        
        return view('clients.index', compact('clients', 'search', 'status', 'statuses'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        $user = Auth::user();
        $statuses = Client::getStatuses();
        
        // Get agents list based on user role
        if ($user->isAgent()) {
            // Agents can only assign to themselves
            $agents = collect([$user]);
        } else {
            // Admin and Manager can assign to any agent
            $agents = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_AGENT])->get();
        }
        
        return view('clients.create', compact('statuses', 'agents', 'user'));
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:' . implode(',', array_keys(Client::getStatuses()))],
        ]);

        // Check for duplicate phone or email
        $existingClient = Client::where('phone', $request->phone)
            ->orWhere(function($q) use ($request) {
                $q->whereNotNull('email')->where('email', $request->email);
            })
            ->first();

        if ($existingClient) {
            return back()->with('error', 'A client with this phone or email already exists.')->withInput();
        }

        // Generate unique customer number
        $customerNumber = 'CLT-' . strtoupper(uniqid());

        // Determine agent_id - always default to logged in user
        $agentId = $request->agent_id;
        if (!$agentId || empty($agentId)) {
            // Default to current logged in user
            $agentId = $user->id;
        }

        Client::create([
            'customer_number' => $customerNumber,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'tags' => $request->tags,
            'status' => $request->status,
            'agent_id' => $agentId,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        $client->load(['agent', 'creator', 'updater']);
        
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        $user = Auth::user();
        $statuses = Client::getStatuses();
        
        // Get agents list based on user role
        if ($user->isAgent()) {
            // Agents can only assign to themselves
            $agents = collect([$user]);
        } else {
            // Admin and Manager can assign to any agent
            $agents = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_MANAGER, User::ROLE_AGENT])->get();
        }
        
        return view('clients.edit', compact('client', 'statuses', 'agents'));
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:' . implode(',', array_keys(Client::getStatuses()))],
        ]);

        // Check for duplicate phone or email (excluding current client)
        $existingClient = Client::where('phone', $request->phone)
            ->where('id', '!=', $client->id)
            ->first();

        if ($existingClient) {
            return back()->with('error', 'A client with this phone already exists.')->withInput();
        }

        // Determine agent_id - always default to current user if not provided
        $agentId = $request->agent_id;
        if (!$agentId || empty($agentId)) {
            $agentId = Auth::id();
        }

        $client->update([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'tags' => $request->tags,
            'status' => $request->status,
            'agent_id' => $agentId,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified client.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

    /**
     * AJAX Search for clients
     */
    public function search(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        
        $clients = Client::with(['agent', 'creator'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('customer_number', 'like', "%{$search}%")
                      ->orWhere('company_name', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'clients' => $clients,
            'count' => $clients->count()
        ]);
    }
}
