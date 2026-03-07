<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Client;
use App\Models\PostMedia;
use App\Notifications\PostActivityNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['client', 'creator', 'media', 'feedback']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Date filter: last 5 days, last week, this month
        if ($request->filled('date_filter') && $request->date_filter !== 'all') {
            $now = now();
            match ($request->date_filter) {
                'last_5_days' => $query->where('created_at', '>=', $now->copy()->subDays(5)),
                'last_week' => $query->where('created_at', '>=', $now->copy()->subWeek()),
                'this_month' => $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year),
                default => null,
            };
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('facebook_message', 'like', "%{$search}%")
                    ->orWhere('instagram_message', 'like', "%{$search}%")
                    ->orWhere('caption', 'like', "%{$search}%");
            });
        }

        // Sort: by client name, created_at, scheduled_at; order asc/desc
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        if ($sortBy === 'client') {
            $query->join('clients', 'posts.client_id', '=', 'clients.id')
                ->whereNull('clients.deleted_at')
                ->orderBy('clients.name', $sortOrder)
                ->select('posts.*');
        } else {
            $sortColumn = in_array($sortBy, ['created_at', 'scheduled_at']) ? $sortBy : 'created_at';
            $query->orderBy($sortColumn, $sortOrder);
        }

        $posts = $query->paginate(20)->withQueryString();
        $clients = Client::where('status', 'active')->orderBy('name')->get();

        return view('posts.index', compact('posts', 'clients'));
    }

    public function create()
    {
        $clients = Client::active()->orderBy('name')->get();
        $networkLabelToValue = [
            'Facebook' => 'facebook',
            'Instagram' => 'instagram',
            'LinkedIn' => 'linkedin',
            'Twitter/X' => 'twitter',
            'TikTok' => 'tiktok',
            'YouTube' => 'youtube',
            'Google Business' => 'google',
        ];
        $clientNetworks = $clients->keyBy('id')->map(fn ($c) => array_values(array_filter(
            array_map(fn ($n) => $networkLabelToValue[$n] ?? null, $c->networks ?? [])
        )));
        return view('posts.create', compact('clients', 'clientNetworks'));
    }

    public function store(Request $request)
    {
        // --- DEBUGGING START ---
    // if ($request->hasFile('media')) {
    //     foreach ($request->file('media') as $file) {
    //         if (!$file->isValid()) {
    //             dd([
    //                 'File Error Code' => $file->getError(),
    //                 'Error Message' => $file->getErrorMessage(),
    //                 'post_max_size' => ini_get('post_max_size'),
    //                 'upload_max_filesize' => ini_get('upload_max_filesize'),
    //             ]);
    //         }
    //     }
    // }
    // --- DEBUGGING END ---
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'post_type' => 'required|in:standard,carousel,video',
            'caption' => 'nullable|string',
            'webpage_url' => 'nullable|url',
            'facebook_message' => 'nullable|string',
            'instagram_message' => 'nullable|string',
            'linkedin_message' => 'nullable|string',
            'twitter_message' => 'nullable|string',
            'tiktok_message' => 'nullable|string',
            'youtube_message' => 'nullable|string',
            'google_post_type' => 'nullable|in:whats_new,offer,event',
            'google_title' => 'nullable|string',
            'google_button' => 'nullable|in:none,book,order,buy,learn_more,sign_up',
            'google_button_link' => 'nullable|url',
            'platforms' => 'required|array',
            'scheduled_at' => 'nullable|date',
            'media.*' => 'bail|required|file|mimes:jpg,jpeg,png,gif,mp4,mov|max:102400', // 100MB max
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'draft';

        // dd($validated);
        // exit;

        $post = Post::create($validated);

        // Handle media uploads
        if ($request->hasFile('media')) {
            $this->handleMediaUploads($post, $request->file('media'));
        }


        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Post created successfully');
    }

    public function show(Post $post)
    {
        $post->load(['client', 'creator', 'media', 'feedback.user']);
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if (!$post->canEdit()) {
            return redirect()
                ->route('posts.show', $post)
                ->with('error', 'This post cannot be edited in its current state');
        }

        $clients = Client::active()->orderBy('name')->get();
        $networkLabelToValue = [
            'Facebook' => 'facebook', 'Instagram' => 'instagram', 'LinkedIn' => 'linkedin',
            'Twitter/X' => 'twitter', 'TikTok' => 'tiktok', 'YouTube' => 'youtube', 'Google Business' => 'google',
        ];
        $allowedPlatforms = array_values(array_filter(
            array_map(fn ($n) => $networkLabelToValue[$n] ?? null, $post->client->networks ?? [])
        ));
        if (empty($allowedPlatforms)) {
            $allowedPlatforms = ['facebook', 'instagram', 'tiktok', 'youtube', 'google'];
        }
        return view('posts.edit', compact('post', 'clients', 'allowedPlatforms'));
    }

    public function update(Request $request, Post $post)
    {
        if (!$post->canEdit()) {
            return redirect()
                ->route('posts.show', $post)
                ->with('error', 'This post cannot be edited');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'post_type' => 'required|in:standard,carousel,video',
            'caption' => 'nullable|string',
            'webpage_url' => 'nullable|url',
            'facebook_message' => 'nullable|string',
            'instagram_message' => 'nullable|string',
            'linkedin_message' => 'nullable|string',
            'twitter_message' => 'nullable|string',
            'tiktok_message' => 'nullable|string',
            'youtube_message' => 'nullable|string',
            'platforms' => 'required|array',
            'scheduled_at' => 'nullable|date',
            // Google Business Profile fields
            'google_post_type' => 'nullable|in:whats_new,offer,event',
            'google_title' => 'nullable|string',
            'google_button' => 'nullable|in:none,book,order,buy,learn_more,sign_up',
            'google_button_link' => 'nullable|url',
            'offer_code' => 'nullable|string',
            'offer_link' => 'nullable|url',
            'offer_terms' => 'nullable|string',
            'offer_start_date' => 'nullable|date',
            'offer_end_date' => 'nullable|date',
            'offer_start_time' => 'nullable|string',
            'offer_end_time' => 'nullable|string',
            'event_start_date' => 'nullable|date',
            'event_end_date' => 'nullable|date',
            'event_start_time' => 'nullable|string',
            'event_end_time' => 'nullable|string',
            'media.*' => 'bail|nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov|max:102400',
        ]);

        $post->update($validated);

        // Handle new media uploads
        if ($request->hasFile('media')) {
            $this->handleMediaUploads($post, $request->file('media'));
        }

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        // Only admins can delete
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can delete posts');
        }

        if (!$post->canDelete()) {
            return redirect()
                ->route('posts.index')
                ->with('error', 'This post cannot be deleted in its current state');
        }

        // Delete media files
        foreach ($post->media as $media) {
            if (file_exists(public_path($media->file_path))) {
                unlink(public_path($media->file_path));
            }
        }

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully');
    }

    /**
     * Team submits post to client for review (not admin). Client will approve or request changes.
     */
    public function submitForApproval(Post $post)
    {
        if ($post->status !== 'draft') {
            return back()->with('error', 'Only draft posts can be submitted to client.');
        }

        $post->update([
            'status' => 'pending_client',
            'approval_status' => 'pending',
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Post sent to client for review. Client can approve or request changes.');
    }

    /**
     * Team resubmits post to client after implementing changes. Admin is not involved.
     */
    public function resubmitToClient(Post $post)
    {
        if ($post->status !== 'changes_requested') {
            return back()->with('error', 'Only posts with changes requested can be resubmitted.');
        }

        $post->update([
            'status' => 'pending_client',
            'approval_status' => 'pending',
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Post resubmitted to client for review.');
    }

    /**
     * Approve post – admin only. Sets status to approved so admin can then schedule.
     */
    public function approve(Post $post)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can approve posts.');
        }

        if ($post->status !== 'pending_approval') {
            return back()->with('error', 'Only posts pending approval can be approved.');
        }

        $post->update([
            'status' => 'approved',
            'approval_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        if ($post->creator) {
            $post->creator->notify(new PostActivityNotification(
                $post,
                'admin_approved',
                "Admin approved your post for {$post->client->name}. You can schedule it from the post page."
            ));
        }

        return back()->with('success', 'Post approved. You can now schedule it for publishing.');
    }

    /**
     * Schedule an approved post for publishing at a specific date/time – admin only.
     */
    public function schedule(Request $request, Post $post)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can schedule posts.');
        }

        if ($post->status !== 'approved') {
            return back()->with('error', 'Only approved posts can be scheduled.');
        }

        $validated = $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ], [
            'scheduled_at.after' => 'Scheduled time must be in the future.',
        ]);

        $post->update([
            'scheduled_at' => $validated['scheduled_at'],
            'status' => 'scheduled',
        ]);

        if ($post->creator) {
            $post->creator->notify(new PostActivityNotification(
                $post,
                'admin_scheduled',
                "Post for {$post->client->name} is scheduled for " . \Carbon\Carbon::parse($validated['scheduled_at'])->format('M d, Y g:i A')
            ));
        }

        return back()->with('success', 'Post scheduled for ' . \Carbon\Carbon::parse($validated['scheduled_at'])->format('M d, Y g:i A'));
    }

    /**
     * Admin can return a post to client for re-review (e.g. needs client sign-off again).
     */
    public function returnToClient(Request $request, Post $post)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only administrators can return posts to client.');
        }

        $request->validate([
            'feedback' => 'nullable|string',
        ]);

        if ($post->status !== 'pending_approval') {
            return back()->with('error', 'Only posts pending admin approval can be returned to client.');
        }

        $post->update([
            'status' => 'pending_client',
            'approval_status' => 'pending',
        ]);

        if ($request->filled('feedback')) {
            $post->feedback()->create([
                'user_id' => auth()->id(),
                'feedback' => $request->feedback,
                'action' => 'request_changes',
            ]);
        }

        return back()->with('success', 'Post returned to client for review.');
    }

    protected function handleMediaUploads(Post $post, array $files)
    {
        foreach ($files as $index => $file) {

            // 1. SAFETY CHECK: Stop if file failed to upload
            if (!$file->isValid()) {
                continue;
            }

            // 2. Define path (e.g., public/uploads/posts/15)
            $folder = 'uploads/posts/' . $post->id;

            // 3. Store file using Laravel Storage (auto-generates unique name)
            // ensure you ran: php artisan storage:link
            $path = $file->store($folder, 'public');

            // 4. Create Database Record
            $post->media()->create([
                'type'      => str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image',
                'file_path' => $path, // Saves as "uploads/posts/1/filename.jpg"
                'file_name' => basename($path),
                'file_size' => round($file->getSize() / 1024), // KB
                'mime_type' => $file->getMimeType(),
                'order'     => $index,
            ]);
        }
    }

    public function deleteMedia(PostMedia $media)
    {
        $post = $media->post;

        if (!$post->canEdit()) {
            return back()->with('error', 'Cannot delete media from this post');
        }

        if (file_exists(public_path($media->file_path))) {
            unlink(public_path($media->file_path));
        }

        $media->delete();

        return back()->with('success', 'Media deleted successfully');
    }
}
