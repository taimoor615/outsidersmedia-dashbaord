<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Client;
use App\Models\PostMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['client', 'creator', 'media']);

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $posts = $query->latest()->paginate(12);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $clients = Client::active()->orderBy('name')->get();
        return view('posts.create', compact('clients'));
    }

    public function store(Request $request)
    {
        // --- DEBUGGING START ---
    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
            if (!$file->isValid()) {
                dd([
                    'File Error Code' => $file->getError(),
                    'Error Message' => $file->getErrorMessage(),
                    'post_max_size' => ini_get('post_max_size'),
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                ]);
            }
        }
    }
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
        // Team members can only edit, not delete
        if (!$post->canEdit()) {
            return redirect()
                ->route('posts.show', $post)
                ->with('error', 'This post cannot be edited in its current state');
        }

        $clients = Client::active()->orderBy('name')->get();
        return view('posts.edit', compact('post', 'clients'));
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

    public function submitForApproval(Post $post)
    {
        if ($post->status !== 'draft') {
            return back()->with('error', 'Only draft posts can be submitted for approval');
        }

        $post->update([
            'status' => 'pending_approval',
            'approval_status' => 'pending',
        ]);

        // TODO: Send notification to client

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Post submitted for client approval');
    }

    public function approve(Post $post)
    {
        $post->update([
            'status' => 'approved',
            'approval_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Post approved successfully');
    }

    public function reject(Request $request, Post $post)
    {
        $request->validate([
            'feedback' => 'required|string',
        ]);

        $post->update([
            'status' => 'pending_approval',
            'approval_status' => 'changes_requested',
        ]);

        $post->feedback()->create([
            'user_id' => auth()->id(),
            'feedback' => $request->feedback,
            'action' => 'request_changes',
        ]);

        return back()->with('success', 'Feedback sent to team');
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
