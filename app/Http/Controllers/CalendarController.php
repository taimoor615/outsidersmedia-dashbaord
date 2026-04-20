<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CalendarController extends Controller
{
    /**
     * Show the calendar view. Admin sees all posts; team sees only their own.
     */
    public function index()
    {
        $clients = Client::orderBy('name')->get(['id', 'name']);
        return view('calendar.index', compact('clients'));
    }

    /**
     * Return events for FullCalendar (JSON). Admin: all scheduled/published; Team: only own posts.
     */
    public function events(Request $request): JsonResponse
    {
        $start = $request->get('start') ? Carbon::parse($request->get('start')) : null;
        $end = $request->get('end') ? Carbon::parse($request->get('end')) : null;

        $query = Post::with(['client', 'creator', 'media'])
            ->whereIn('status', ['scheduled', 'published'])
            ->whereNotNull('scheduled_at');


        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($start) {
            $query->where('scheduled_at', '>=', $start);
        }
        if ($end) {
            $query->where('scheduled_at', '<=', $end);
        }

        $posts = $query->orderBy('scheduled_at')->get();

        $events = $posts->map(function (Post $post) {
            $scheduledAt = $post->scheduled_at;
            $end = $scheduledAt->copy()->addHour();
            $clientName = $post->client?->name ?? 'Unknown Client';
            $title = $clientName . ' – ' . ($post->caption ? \Str::limit($post->caption, 30) : 'Post #' . $post->id);
            return [
                'id' => $post->id,
                'title' => $title,
                'start' => $scheduledAt->toIso8601String(),
                'end' => $end->toIso8601String(),
                'url' => route('posts.show', $post),
                'backgroundColor' => $this->colorForStatus($post->status),
                'borderColor' => $this->colorForStatus($post->status),
                'extendedProps' => [
                    'status' => $post->status,
                    'client' => $clientName,
                    'post_type' => $post->post_type,
                ],
            ];
        });

        return response()->json($events->values());
    }

    private function colorForStatus(string $status): string
    {
        return match ($status) {
            'scheduled' => '#CD571B',
            'published' => '#EC921A',
            'approved' => '#059669',
            default => '#6b7280',
        };
    }
}
