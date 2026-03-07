<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Post;
use App\Models\PostFeedback;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_clients' => Client::count(),
            'active_clients' => Client::where('status', 'active')->count(),
            'pending_approvals' => Post::where('status', 'pending_approval')->count(),
            'scheduled_posts' => Post::where('status', 'scheduled')->count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'total_posts_this_month' => Post::whereMonth('created_at', now()->month)->count(),
        ];

        // Calculate growth percentages (compared to last month)
        $lastMonthClients = Client::whereMonth('created_at', now()->subMonth()->month)->count();
        $stats['clients_growth'] = $lastMonthClients > 0
            ? round((($stats['total_clients'] - $lastMonthClients) / $lastMonthClients) * 100)
            : 0;

        $lastMonthPosts = Post::whereMonth('created_at', now()->subMonth()->month)
            ->where('status', 'published')
            ->count();
        $stats['posts_growth'] = $lastMonthPosts > 0
            ? round((($stats['published_posts'] - $lastMonthPosts) / $lastMonthPosts) * 100)
            : 0;

        // Recent client feedback (last 5)
        $recentFeedback = PostFeedback::with(['post.client'])
            ->where('is_client_feedback', true)
            ->latest()
            ->take(5)
            ->get();

        // Upcoming scheduled posts (next 5)
        $upcomingPosts = Post::with(['client', 'media'])
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->take(5)
            ->get();

        // Recent client notes (last 5)
        $recentNotes = PostFeedback::with(['post.client'])
            ->where('is_client_feedback', false)
            ->latest()
            ->take(5)
            ->get();

        // Team activity (last 10 actions)
        $teamActivity = $this->getTeamActivity();

        // Posts by status for this week
        $weeklyPostsData = Post::select('status', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Analytics: posts per client, revisions, monthly posts
        $postsPerClient = Client::query()
            ->withCount('posts')
            ->orderByDesc('posts_count')
            ->get()
            ->map(fn ($c) => ['client_name' => $c->name, 'total' => $c->posts_count]);
        $totalRevisions = PostFeedback::count();
        $postsWithChangesRequested = Post::where('status', 'changes_requested')->count();
        $monthlyPosts = Post::query()
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(fn ($row) => [
                'label' => \Carbon\Carbon::createFromDate($row->year, $row->month, 1)->format('M Y'),
                'total' => $row->total,
            ]);

        return view('admin.dashboard', compact(
            'stats',
            'recentFeedback',
            'upcomingPosts',
            'recentNotes',
            'teamActivity',
            'weeklyPostsData',
            'postsPerClient',
            'totalRevisions',
            'postsWithChangesRequested',
            'monthlyPosts'
        ));
    }

    private function getTeamActivity()
    {
        $activities = collect();

        // Recent posts created
        $recentPosts = Post::with('creator')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($post) {
                return [
                    'user' => $post->creator,
                    'action' => 'created a new post',
                    'details' => "for {$post->client->name}",
                    'time' => $post->created_at,
                ];
            });

        // Recent feedback given
        $recentTeamFeedback = PostFeedback::with(['user', 'post'])
            ->where('is_client_feedback', false)
            ->whereNotNull('user_id')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($feedback) {
                return [
                    'user' => $feedback->user,
                    'action' => $feedback->action === 'approve' ? 'approved a post' : 'provided feedback',
                    'details' => '',
                    'time' => $feedback->created_at,
                ];
            });

        // Recent clients added
        $recentClients = Client::with('creator')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($client) {
                return [
                    'user' => $client->creator,
                    'action' => 'added a new client',
                    'details' => $client->name,
                    'time' => $client->created_at,
                ];
            });

        return $activities
            ->merge($recentPosts)
            ->merge($recentTeamFeedback)
            ->merge($recentClients)
            ->sortByDesc('time')
            ->take(10);
    }
}
