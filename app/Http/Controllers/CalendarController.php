<?php

namespace App\Http\Controllers;

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
        return view('calendar.index');
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

        if (!auth()->user()->isAdmin()) {
            $query->where('created_by', auth()->id());
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
            $title = $post->client->name . ' – ' . ($post->caption ? \Str::limit($post->caption, 30) : 'Post #' . $post->id);
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
                    'client' => $post->client->name,
                    'post_type' => $post->post_type,
                ],
            ];
        });

        return response()->json($events->values());
    }

    private function colorForStatus(string $status): string
    {
        return match ($status) {
            'scheduled' => '#2563eb',
            'published' => '#7c3aed',
            'approved' => '#059669',
            default => '#6b7280',
        };
    }
}
