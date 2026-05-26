<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ClientWelcome;
use App\Mail\NewClientNotification;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

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
            'name'                       => 'required|string|min:2|max:255',
            'email'                      => ['required', 'email:rfc', 'max:254', Rule::unique('clients', 'email')->whereNull('deleted_at')],
            'website_url'                => 'nullable|url|max:500',
            'location'                   => 'nullable|string|max:500',
            'business_description'       => 'nullable|string|max:5000',
            'unique_value'               => 'nullable|string|max:5000',
            'target_audience'            => 'nullable|string|max:5000',
            'social_goals'               => 'nullable|array',
            'social_goals.*'             => 'string|max:100',
            'brand_tone'                 => 'nullable|array|max:3',
            'brand_tone.*'               => 'string|max:50',
            'content_types'              => 'nullable|array',
            'content_types.*'            => 'string|max:100',
            'content_to_avoid'           => 'nullable|string|max:5000',
            'preferred_cta'              => 'nullable|string|max:500',
            'share_third_party_content'  => 'nullable|boolean',
            'preferred_sources'          => 'nullable|string|max:2000',
            'keywords'                   => 'nullable|string|max:5000',
            'competitors'                => 'nullable|string|max:5000',
            'brand_assets_link'          => 'nullable|string|max:5000',
            'timezone'                   => ['required', 'string', 'in:' . implode(',', timezone_identifiers_list())],
            'posting_days'               => 'nullable|array',
            'posting_days.*'             => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'posting_times'              => 'nullable|array',
            'needs_approval'             => 'nullable|boolean',
            'approval_emails'            => 'nullable|string|max:1000',
            'additional_notes'           => 'nullable|string|max:5000',
            'plan_type'                  => 'required|in:starter,business,scale',
            'networks'                   => 'nullable|array',
            'networks.*'                 => 'string|in:Facebook,Instagram,LinkedIn,Twitter/X,TikTok,YouTube,Google Business',
            'network_links'              => 'nullable|array',
            'network_links.*'            => 'nullable|url|max:500',
        ]);

        // Set posts per month based on plan
        $postsPerMonth = [
            'starter' => 8,
            'business' => 12,
            'scale' => 16,
        ];
        $validated['posts_per_month'] = $postsPerMonth[$validated['plan_type']];

        // Remove empty network links
        if (!empty($validated['network_links'])) {
            $validated['network_links'] = array_filter($validated['network_links'], fn($v) => !empty($v));
        }

        // Set creator
        $validated['created_by'] = auth()->id();
        $validated['share_third_party_content'] = $request->input('share_third_party_content') === '1';
        $validated['needs_approval'] = $request->has('needs_approval');

        $client = Client::create($validated);

        // Send welcome email to client
        try {
            Mail::to($client->email)->send(new ClientWelcome($client));
        } catch (\Exception $e) {
            // Non-fatal: log but don't block the redirect
            \Log::error('Client welcome email failed: ' . $e->getMessage());
        }

        // Notify all admins
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new NewClientNotification($client, auth()->user()));
            }
        } catch (\Exception $e) {
            \Log::error('Admin new-client notification email failed: ' . $e->getMessage());
        }

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
            'name'                       => 'required|string|min:2|max:255',
            'email'                      => ['required', 'email:rfc', 'max:254', Rule::unique('clients', 'email')->ignore($client->id)->whereNull('deleted_at')],
            'website_url'                => 'nullable|url|max:500',
            'location'                   => 'nullable|string|max:500',
            'business_description'       => 'nullable|string|max:5000',
            'unique_value'               => 'nullable|string|max:5000',
            'target_audience'            => 'nullable|string|max:5000',
            'social_goals'               => 'nullable|array',
            'social_goals.*'             => 'string|max:100',
            'brand_tone'                 => 'nullable|array|max:3',
            'brand_tone.*'               => 'string|max:50',
            'content_types'              => 'nullable|array',
            'content_types.*'            => 'string|max:100',
            'content_to_avoid'           => 'nullable|string|max:5000',
            'preferred_cta'              => 'nullable|string|max:500',
            'share_third_party_content'  => 'nullable|boolean',
            'preferred_sources'          => 'nullable|string|max:2000',
            'keywords'                   => 'nullable|string|max:5000',
            'competitors'                => 'nullable|string|max:5000',
            'brand_assets_link'          => 'nullable|string|max:5000',
            'timezone'                   => ['required', 'string', 'in:' . implode(',', timezone_identifiers_list())],
            'posting_days'               => 'nullable|array',
            'posting_days.*'             => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'posting_times'              => 'nullable|array',
            'needs_approval'             => 'nullable|boolean',
            'approval_emails'            => 'nullable|string|max:1000',
            'additional_notes'           => 'nullable|string|max:5000',
            'plan_type'                  => 'required|in:starter,business,scale',
            'networks'                   => 'nullable|array',
            'networks.*'                 => 'string|in:Facebook,Instagram,LinkedIn,Twitter/X,TikTok,YouTube,Google Business',
            'network_links'              => 'nullable|array',
            'network_links.*'            => 'nullable|url|max:500',
            'status'                     => 'required|in:active,inactive,suspended',
        ]);

        // Update posts per month based on plan
        $postsPerMonth = [
            'starter' => 8,
            'business' => 12,
            'scale' => 16,
        ];
        $validated['posts_per_month'] = $postsPerMonth[$validated['plan_type']];

        // Remove empty network links
        if (!empty($validated['network_links'])) {
            $validated['network_links'] = array_filter($validated['network_links'], fn($v) => !empty($v));
        }

        $validated['share_third_party_content'] = $request->input('share_third_party_content') === '1';
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
     * Update content mix for a client (AJAX-friendly, returns back with flash).
     */
    public function updateContentMix(Request $request, Client $client)
    {
        $validated = $request->validate([
            'content_mix'              => 'required|array',
            'content_mix.static'       => 'nullable|integer|min:0',
            'content_mix.carousel'     => 'nullable|integer|min:0',
            'content_mix.video'        => 'nullable|integer|min:0',
            'content_mix.stories'      => 'nullable|integer|min:0',
        ]);

        $client->update(['content_mix' => $validated['content_mix']]);

        return back()->with('success', 'Content mix updated.');
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
