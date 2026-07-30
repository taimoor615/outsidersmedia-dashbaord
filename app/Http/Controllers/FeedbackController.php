<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\PostFeedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * List all feedback with filters.
     * Admins see every post's feedback; team members see only feedback on their own posts.
     */
    public function index(Request $request)
    {
        // Both admins and team members see all feedback (team can already
        // view every post, so feedback visibility matches).
        $query = PostFeedback::with(['post.client', 'user']);

        // Type filter: client (default) / team / all
        $type = $request->get('type', 'client');
        if ($type === 'client') {
            $query->where('is_client_feedback', true);
        } elseif ($type === 'team') {
            $query->where('is_client_feedback', false);
        }

        // Client filter
        if ($request->filled('client_id')) {
            $clientId = $request->client_id;
            $query->whereHas('post', function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }

        // Date range filter: this week, this month, last 7 / 30 days, today
        if ($request->filled('date_filter') && $request->date_filter !== 'all') {
            $now = now();
            match ($request->date_filter) {
                'today'        => $query->whereDate('created_at', $now->toDateString()),
                'this_week'    => $query->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]),
                'this_month'   => $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year),
                'last_7_days'  => $query->where('created_at', '>=', $now->copy()->subDays(7)),
                'last_30_days' => $query->where('created_at', '>=', $now->copy()->subDays(30)),
                default        => null,
            };
        }

        // Sort order (default: most recent first)
        $sortOrder = $request->get('sort_order', 'desc');
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }
        $query->orderBy('created_at', $sortOrder);

        $feedback = $query->paginate(20)->withQueryString();

        $clients = Client::where('status', 'active')->orderBy('name')->get();

        return view('feedback.index', compact('feedback', 'clients', 'type'));
    }
}
