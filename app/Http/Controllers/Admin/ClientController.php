<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of clients (with search and filter by team member)
     */
    public function index(Request $request)
    {
        $query = Client::with('creator');

        // Search: name or email
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        // Filter by team member (created_by)
        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        // Sort: default by created_at desc
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        if ($sortBy === 'name') {
            $query->orderBy('name', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        $clients = $query->paginate(12)->withQueryString();
        $teamMembers = User::whereIn('role', ['admin', 'team'])->orderBy('name')->get(['id', 'name']);

        return view('admin.clients.index', compact('clients', 'teamMembers'));
    }

    /**
     * Show the form for creating a new client
     */
    public function create()
    {
        $timezones = timezone_identifiers_list();
        return view('admin.clients.create', compact('timezones'));
    }

    /**
     * Store a newly created client
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'website_url' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'business_description' => 'nullable|string',
            'unique_value' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'social_goals' => 'nullable|array',
            'brand_tone' => 'nullable|array|max:9',
            'content_types' => 'nullable|array',
            'content_to_avoid' => 'nullable|string',
            'preferred_cta' => 'nullable|string',
            'share_third_party_content' => 'nullable|boolean',
            'keywords' => 'nullable|string',
            'competitors' => 'nullable|string',
            'brand_assets_link' => 'nullable|url',
            'timezone' => 'required|string',
            'posting_days' => 'nullable|array',
            'needs_approval' => 'nullable|boolean',
            'approval_emails' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'plan_type' => 'required|in:starter,business,scale',
            'networks' => 'nullable|array',
        ]);

        // Set posts per month based on plan
        $postsPerMonth = [
            'starter' => 8,
            'business' => 12,
            'scale' => 16,
        ];
        $validated['posts_per_month'] = $postsPerMonth[$validated['plan_type']];

        // Set creator
        $validated['created_by'] = auth()->id();
        $validated['share_third_party_content'] = $request->has('share_third_party_content');
        $validated['needs_approval'] = $request->has('needs_approval');

        $client = Client::create($validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', "Client {$client->name} has been created successfully");
    }

    /**
     * Display the specified client
     */
    public function show(Client $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client
     */
    public function edit(Client $client)
    {
        $timezones = timezone_identifiers_list();
        return view('admin.clients.edit', compact('client', 'timezones'));
    }

    /**
     * Update the specified client
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'website_url' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'business_description' => 'nullable|string',
            'unique_value' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'social_goals' => 'nullable|array',
            'brand_tone' => 'nullable|array|max:9',
            'content_types' => 'nullable|array',
            'content_to_avoid' => 'nullable|string',
            'preferred_cta' => 'nullable|string',
            'share_third_party_content' => 'nullable|boolean',
            'keywords' => 'nullable|string',
            'competitors' => 'nullable|string',
            'brand_assets_link' => 'nullable|url',
            'timezone' => 'required|string',
            'posting_days' => 'nullable|array',
            'needs_approval' => 'nullable|boolean',
            'approval_emails' => 'nullable|string',
            'additional_notes' => 'nullable|string',
            'plan_type' => 'required|in:starter,business,scale',
            'networks' => 'nullable|array',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        // Update posts per month based on plan
        $postsPerMonth = [
            'starter' => 8,
            'business' => 12,
            'scale' => 16,
        ];
        $validated['posts_per_month'] = $postsPerMonth[$validated['plan_type']];

        $validated['share_third_party_content'] = $request->has('share_third_party_content');
        $validated['needs_approval'] = $request->has('needs_approval');

        $client->update($validated);

        return redirect()
            ->route('clients.show', $client)
            ->with('success', "Client {$client->name} has been updated successfully");
    }

    /**
     * Remove the specified client
     */
    public function destroy(Client $client)
    {
        // Authorization check - only admins can delete
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized. Only administrators can delete clients.');
        }
        $name = $client->name;
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', "Client {$name} has been deleted successfully");
    }

    /**
     * Toggle client status
     */
    public function toggleStatus(Client $client)
    {
        $newStatus = $client->status === 'active' ? 'inactive' : 'active';
        $client->update(['status' => $newStatus]);

        return redirect()
            ->route('clients.index')
            ->with('success', "Client {$client->name} is now {$newStatus}");
    }

}
