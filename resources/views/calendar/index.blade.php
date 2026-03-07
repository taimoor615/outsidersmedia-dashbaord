@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')

@section('title', 'Calendar')
@section('page-title', 'Content Calendar')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Content Calendar</h1>
            <p class="mt-1 text-gray-600">
                @if(auth()->user()->isAdmin())
                    All scheduled and published posts. Click an event to view details. Only you can approve and schedule posts.
                @else
                    Your scheduled and published posts. Submit drafts for approval; only admin can approve and schedule.
                @endif
            </p>
        </div>
        <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
            View Posts
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div id="calendar"></div>
    </div>

    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-blue-500"></span> Scheduled
        </span>
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-purple-500"></span> Published
        </span>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: '{{ route("calendar.events") }}',
        eventClick: function(info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        },
        eventDidMount: function(info) {
            info.el.title = info.event.extendedProps.client + ' – ' + (info.event.extendedProps.status || '');
        }
    });
    calendar.render();
});
</script>
@endsection
