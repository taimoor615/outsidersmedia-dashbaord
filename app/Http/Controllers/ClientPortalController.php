<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientNote;
use App\Models\Post;
use App\Models\PostFeedback;
use App\Models\User;
use App\Notifications\PostActivityNotification;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    /**
     * Show client portal with all posts (any status – show once created).
     */
    public function show($token)
    {
        $client = Client::where('share_token', $token)->firstOrFail();

        $posts = Post::where('client_id', $client->id)
            ->with(['media', 'feedback'])
            ->orderBy('created_at', 'desc')
            ->get();

        $notes = ClientNote::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.portal', compact('client', 'posts', 'notes'));
    }

    /**
     * Store a general note from the client
     */
    public function storeNote(Request $request, $token)
    {
        $client = Client::where('share_token', $token)->firstOrFail();

        $request->validate(['note' => 'required|string|min:1|max:2000']);

        ClientNote::create([
            'client_id'   => $client->id,
            'added_by'    => null,
            'author_name' => strip_tags($client->name),
            'note'        => $request->note,
        ]);

        return back()->with('success', 'Your note has been sent to the team!');
    }

    /**
     * Submit general feedback
     */
    public function submitFeedback(Request $request, $token)
    {
        $client = Client::where('share_token', $token)->firstOrFail();

        $validated = $request->validate([
            'post_id'       => 'required|integer|exists:posts,id',
            'feedback'      => 'required|string|max:5000',
            'feedback_name' => 'nullable|string|max:100',
        ]);

        $post = Post::where('id', $validated['post_id'])
            ->where('client_id', $client->id)
            ->firstOrFail();

        PostFeedback::create([
            'post_id'            => $post->id,
            'client_name'        => $validated['feedback_name'] ?? $client->name,
            'feedback'           => $validated['feedback'],
            'is_client_feedback' => true,
        ]);

        return back()->with('success', 'Your feedback has been submitted successfully');
    }

    /**
     * Client approves (finalizes) a post – sends to admin for approval and scheduling.
     */
    public function approvePost($token, Post $post)
    {
        $client = Client::where('share_token', $token)->firstOrFail();

        if ($post->client_id !== $client->id) {
            abort(403);
        }

        if ($post->status !== 'pending_client') {
            return back()->with('error', 'Only posts awaiting your review can be approved.');
        }

        $post->update([
            'status' => 'pending_approval',
            'approval_status' => 'approved',
        ]);

        PostFeedback::create([
            'post_id' => $post->id,
            'client_name' => $client->name,
            'feedback' => 'Post approved by client – sent to admin for scheduling',
            'action' => 'approve',
            'is_client_feedback' => true,
        ]);

        // Notify all admins
        User::where('role', 'admin')->get()->each(function (User $admin) use ($post, $client) {
            $admin->notify(new PostActivityNotification(
                $post,
                'client_approved',
                "{$client->name} approved a post. Ready for you to approve and schedule."
            ));
        });

        return back()->with('success', 'Post approved! It has been sent to admin for final approval and scheduling.');
    }

    /**
     * Client requests changes – goes to team member (not admin). Team implements changes.
     */
    public function rejectPost(Request $request, $token, Post $post)
    {
        $client = Client::where('share_token', $token)->firstOrFail();

        if ($post->client_id !== $client->id) {
            abort(403);
        }

        $validated = $request->validate([
            'feedback'      => 'required|string|max:5000',
            'action'        => 'required|in:request_changes,reject',
            'feedback_name' => 'nullable|string|max:100',
        ]);

        if ($post->status !== 'pending_client') {
            return back()->with('error', 'Only posts awaiting your review can have changes requested.');
        }

        $post->update([
            'status' => 'changes_requested',
            'approval_status' => 'changes_requested',
        ]);

        PostFeedback::create([
            'post_id'            => $post->id,
            'client_name'        => !empty($validated['feedback_name']) ? $validated['feedback_name'] : $client->name,
            'feedback'           => $validated['feedback'],
            'action'             => $validated['action'],
            'is_client_feedback' => true,
        ]);

        // Notify the team member who created the post
        if ($post->creator) {
            $post->creator->notify(new PostActivityNotification(
                $post,
                'client_requested_changes',
                "{$client->name} requested changes: " . \Str::limit($validated['feedback'], 60)
            ));
        }

        $message = $validated['action'] === 'reject'
            ? 'Post rejected. Your feedback has been sent to the team.'
            : 'Changes requested. Your feedback has been sent to the team member.';

        return back()->with('success', $message);
    }

    /**
     * Client suggests a preferred publish date for a post.
     */
    public function suggestDate(Request $request, $token, Post $post)
    {
        $client = Client::where('share_token', $token)->firstOrFail();

        if ($post->client_id !== $client->id) {
            abort(403);
        }

        $validated = $request->validate([
            'suggested_date' => 'required|date|after_or_equal:today',
            'suggested_time' => ['nullable', 'regex:/^\d{2}:\d{2}$/'],
        ]);

        $timeLabel = '';
        if (!empty($validated['suggested_time'])) {
            $timeLabel = ' at ' . date('g:i A', strtotime($validated['suggested_time']));
        }

        $dateLabel = \Carbon\Carbon::parse($validated['suggested_date'])->format('M d, Y') . $timeLabel;

        PostFeedback::create([
            'post_id'            => $post->id,
            'client_name'        => $client->name,
            'feedback'           => "Suggested publish date: {$dateLabel}",
            'action'             => 'suggest_date',
            'is_client_feedback' => true,
        ]);

        return back()->with('success', "Publish date suggestion sent to the team!");
    }

    /**
     * Client updates one of their own notes.
     */
    public function updateNote(Request $request, $token, ClientNote $note)
    {
        $client = Client::where('share_token', $token)->firstOrFail();

        if ($note->client_id !== $client->id || !is_null($note->added_by)) {
            abort(403);
        }

        $request->validate(['note' => 'required|string|max:2000']);
        $note->update(['note' => $request->note]);

        return back()->with('success', 'Note updated successfully.');
    }

    /**
     * Client edits post content (captions per platform).
     */
    public function updatePost(Request $request, $token, Post $post)
    {
        $client = Client::where('share_token', $token)->firstOrFail();

        if ($post->client_id !== $client->id) {
            abort(403);
        }

        $validated = $request->validate([
            'facebook_message'  => 'nullable|string|max:63206',
            'instagram_message' => 'nullable|string|max:2200',
            'tiktok_message'    => 'nullable|string|max:2200',
            'youtube_message'   => 'nullable|string|max:5000',
        ]);

        $post->update(array_filter($validated, fn($v) => $v !== null));

        PostFeedback::create([
            'post_id'            => $post->id,
            'client_name'        => $client->name,
            'feedback'           => 'Client updated post content.',
            'is_client_feedback' => true,
        ]);

        return back()->with('success', 'Post content updated successfully.');
    }
}
