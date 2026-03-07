<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Post;
use App\Models\PostFeedback;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $now = now();

        // ─── Overview KPIs ─────────────────────────────────────────
        $totalPosts = Post::count();
        $totalClients = Client::count();
        $publishedThisMonth = Post::where('status', 'published')
            ->whereMonth('published_at', $now->month)
            ->whereYear('published_at', $now->year)
            ->count();
        $createdThisMonth = Post::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
        $totalRevisions = PostFeedback::count();
        $postsNeedingChanges = Post::where('status', 'changes_requested')->count();
        $pendingApproval = Post::where('status', 'pending_approval')->count();
        $scheduledCount = Post::where('status', 'scheduled')->count();

        // ─── Posts per client (with client link and plan info) ─────
        $postsPerClient = Client::query()
            ->withCount('posts')
            ->withCount(['posts as published_count' => function ($q) {
                $q->where('status', 'published');
            }])
            ->orderByDesc('posts_count')
            ->get()
            ->map(fn ($c) => [
                'client' => $c,
                'total' => $c->posts_count,
                'published' => $c->published_count,
                'plan_limit' => $c->posts_per_month ?? 0,
            ]);

        // ─── Revisions / feedback breakdown ─────────────────────────
        $feedbackByAction = PostFeedback::select('action', DB::raw('count(*) as count'))
            ->groupBy('action')
            ->pluck('count', 'action')
            ->toArray();
        $clientFeedbackCount = PostFeedback::where('is_client_feedback', true)->count();
        $teamFeedbackCount = PostFeedback::where('is_client_feedback', false)->count();
        $feedbackByMonth = PostFeedback::query()
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get()
            ->map(fn ($row) => [
                'label' => Carbon::createFromDate($row->year, $row->month, 1)->format('M Y'),
                'total' => $row->total,
            ]);

        // ─── Posts by status ───────────────────────────────────────
        $postsByStatus = Post::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        $statusLabels = [
            'draft' => 'Draft',
            'pending_client' => 'Pending Client',
            'changes_requested' => 'Changes Requested',
            'pending_approval' => 'Pending Approval',
            'approved' => 'Approved',
            'scheduled' => 'Scheduled',
            'published' => 'Published',
            'failed' => 'Failed',
        ];

        // ─── Posts by month (created & published) ───────────────────
        $monthlyCreated = Post::query()
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get()
            ->keyBy(fn ($r) => $r->year . '-' . str_pad($r->month, 2, '0', STR_PAD_LEFT));
        $monthlyPublished = Post::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->select(DB::raw('YEAR(published_at) as year'), DB::raw('MONTH(published_at) as month'), DB::raw('count(*) as total'))
            ->where('published_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get()
            ->keyBy(fn ($r) => $r->year . '-' . str_pad($r->month, 2, '0', STR_PAD_LEFT));
        $last12Months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $d = $now->copy()->subMonths($i);
            $key = $d->format('Y-m');
            $last12Months->push([
                'label' => $d->format('M Y'),
                'created' => $monthlyCreated->get($key)?->total ?? 0,
                'published' => $monthlyPublished->get($key)?->total ?? 0,
            ]);
        }

        // ─── Posts by team member (creator) ─────────────────────────
        $postsByCreator = Post::query()
            ->select('created_by', DB::raw('count(*) as total'))
            ->whereNotNull('created_by')
            ->groupBy('created_by')
            ->with('creator:id,name')
            ->get()
            ->map(fn ($row) => [
                'name' => $row->creator?->name ?? 'Unknown',
                'total' => $row->total,
            ])
            ->sortByDesc('total')
            ->values();

        // ─── Platform usage (from posts.platforms JSON) ───────────────
        $platformCounts = [];
        Post::whereNotNull('platforms')->get()->each(function (Post $post) use (&$platformCounts) {
            foreach ((array) $post->platforms as $p) {
                $platformCounts[$p] = ($platformCounts[$p] ?? 0) + 1;
            }
        });
        arsort($platformCounts);
        $platformUsage = collect($platformCounts)->map(fn ($count, $name) => ['name' => ucfirst($name), 'count' => $count])->values();

        // ─── Post type distribution ─────────────────────────────────
        $postsByType = Post::select('post_type', DB::raw('count(*) as count'))
            ->groupBy('post_type')
            ->pluck('count', 'post_type')
            ->toArray();

        // ─── Approval funnel (simplified) ───────────────────────────
        $funnel = [
            'Draft' => Post::where('status', 'draft')->count(),
            'Pending Client' => Post::where('status', 'pending_client')->count(),
            'Changes Requested' => Post::where('status', 'changes_requested')->count(),
            'Pending Approval' => Post::where('status', 'pending_approval')->count(),
            'Approved' => Post::where('status', 'approved')->count(),
            'Scheduled' => Post::where('status', 'scheduled')->count(),
            'Published' => Post::where('status', 'published')->count(),
        ];

        // ─── Recent feedback (last 10) ───────────────────────────────
        $recentFeedback = PostFeedback::with(['post.client', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.analytics.index', compact(
            'totalPosts',
            'totalClients',
            'publishedThisMonth',
            'createdThisMonth',
            'totalRevisions',
            'postsNeedingChanges',
            'pendingApproval',
            'scheduledCount',
            'postsPerClient',
            'feedbackByAction',
            'clientFeedbackCount',
            'teamFeedbackCount',
            'feedbackByMonth',
            'postsByStatus',
            'statusLabels',
            'last12Months',
            'postsByCreator',
            'platformUsage',
            'postsByType',
            'funnel',
            'recentFeedback'
        ));
    }
}
