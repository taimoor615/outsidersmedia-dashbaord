<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostFeedback;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Get statistics for team member
        $stats = [
            'draft_posts' => Post::where('created_by', $userId)
                ->where('status', 'draft')
                ->count(),

            'pending_approval' => Post::where('created_by', $userId)
                ->where('status', 'pending_approval')
                ->count(),

            'changes_requested' => Post::where('created_by', $userId)
                ->where('approval_status', 'changes_requested')
                ->count(),

            'scheduled_posts' => Post::where('created_by', $userId)
                ->where('status', 'scheduled')
                ->count(),

            'approved_this_week' => Post::where('created_by', $userId)
                ->where('approval_status', 'approved')
                ->whereBetween('approved_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),

            'total_posts_created' => Post::where('created_by', $userId)->count(),
        ];

        // dd($stats);

        // Recent posts needing action (changes requested)
        $postsNeedingAction = Post::with(['client', 'media', 'feedback'])
            ->where('created_by', $userId)
            ->where('approval_status', 'changes_requested')
            ->latest()
            ->take(5)
            ->get();

        // Recent feedback from clients
        $recentClientFeedback = PostFeedback::with(['post.client'])
            ->whereHas('post', function ($query) use ($userId) {
                $query->where('created_by', $userId);
            })
            ->where('is_client_feedback', true)
            ->latest()
            ->take(5)
            ->get();

        // Your upcoming scheduled posts
        $upcomingPosts = Post::with(['client', 'media'])
            ->where('created_by', $userId)
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->take(5)
            ->get();

        // Your recent posts
        $recentPosts = Post::with(['client', 'media'])
            ->where('created_by', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('team.dashboard', compact(
            'stats',
            'postsNeedingAction',
            'recentClientFeedback',
            'upcomingPosts',
            'recentPosts'
        ));
    }
}
