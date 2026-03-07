<?php

namespace App\Http\Controllers;

use App\Models\Client;
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

        return view('client.portal', compact('client', 'posts'));
    }

    /**
     * Submit general feedback
     */
    public function submitFeedback(Request $request, $token)
    {
        $client = Client::where('share_token', $token)->firstOrFail();

        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'feedback' => 'required|string',
        ]);

        $post = Post::findOrFail($validated['post_id']);

        // Verify post belongs to this client
        if ($post->client_id !== $client->id) {
            abort(403);
        }

        PostFeedback::create([
            'post_id' => $post->id,
            'client_name' => $client->name,
            'feedback' => $validated['feedback'],
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
            'feedback' => 'required|string',
            'action' => 'required|in:request_changes,reject',
        ]);

        if ($post->status !== 'pending_client') {
            return back()->with('error', 'Only posts awaiting your review can have changes requested.');
        }

        $post->update([
            'status' => 'changes_requested',
            'approval_status' => 'changes_requested',
        ]);

        PostFeedback::create([
            'post_id' => $post->id,
            'client_name' => $client->name,
            'feedback' => $validated['feedback'],
            'action' => $validated['action'],
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
}
