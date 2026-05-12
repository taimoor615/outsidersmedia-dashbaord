@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')

@section('title', 'Calendar')
@section('page-title', 'Content Calendar')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Content Calendar</h1>
            <p class="mt-1 text-sm text-gray-500">
                @if(auth()->user()->isAdmin())
                    All posts with a scheduled date across all clients.
                @else
                    All posts with a scheduled date. Click an event to view details.
                @endif
            </p>
        </div>
        <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition text-sm font-medium cursor-pointer">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            View Posts
        </a>
    </div>

    <!-- Client Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-wrap items-center gap-3">
        <label class="text-sm font-semibold text-gray-700 flex-shrink-0">Filter by client:</label>
        <select id="clientFilter" class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:border-transparent cursor-pointer" style="--tw-ring-color:#CD571B;">
            <option value="">All Clients</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}">{{ $client->name }}</option>
            @endforeach
        </select>
        <button id="applyFilter" class="px-4 py-2 text-sm font-semibold text-white rounded-xl transition cursor-pointer" style="background:#CD571B;" onmouseover="this.style.background='#b54c17'" onmouseout="this.style.background='#CD571B'">
            Apply
        </button>
        <button id="clearFilter" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition cursor-pointer">
            Clear
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div id="calendar"></div>
    </div>

    <!-- Legend -->
    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full" style="background:#CD571B;"></span> Scheduled</span>
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full" style="background:#EC921A;"></span> Published</span>
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Approved</span>
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Pending Approval</span>
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-purple-500"></span> Pending Client</span>
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-orange-500"></span> Changes Requested</span>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<style>
/* Calendar title: bigger and bolder */
.fc-toolbar-title { font-size: 1.4rem !important; font-weight: 800 !important; color: #111827; }
/* All FullCalendar buttons: pointer cursor */
.fc-button, .fc-button-primary, .fc-today-button { cursor: pointer !important; }
/* Today button highlight */
.fc-today-button:not(:disabled) { background: #CD571B !important; border-color: #CD571B !important; }
.fc-today-button:not(:disabled):hover { background: #b54c17 !important; border-color: #b54c17 !important; }
/* View buttons */
.fc-button-group .fc-button { cursor: pointer !important; }
.fc-button-primary:not(.fc-button-active):hover { background: #f3f4f6 !important; color: #374151 !important; border-color: #d1d5db !important; }
/* Active view button */
.fc-button-active { background: #1f2937 !important; border-color: #1f2937 !important; }
/* Day number cells */
.fc-daygrid-day-number, .fc-col-header-cell-cushion { cursor: default; }
/* Event hover */
.fc-event { cursor: pointer !important; }
/* Month/year emphasis in title */
.fc-toolbar-title { letter-spacing: -0.01em; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventsBase = '{{ route("calendar.events") }}';
    let selectedClient = '';

    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today:   'Today',
            month:   'Month',
            week:    'Week',
            list:    'List',
            day:     'Day'
        },
        height: 'auto',
        events: function(fetchInfo, successCallback, failureCallback) {
            let url = eventsBase + '?start=' + encodeURIComponent(fetchInfo.startStr) + '&end=' + encodeURIComponent(fetchInfo.endStr);
            if (selectedClient) url += '&client_id=' + selectedClient;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) {
                    if (!r.ok) throw new Error('Network error ' + r.status);
                    return r.json();
                })
                .then(function(data) { successCallback(data); })
                .catch(function(e) { failureCallback(e); });
        },
        eventClick: function(info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        },
        eventDidMount: function(info) {
            info.el.setAttribute('title', (info.event.extendedProps.client || '') + ' – ' + (info.event.extendedProps.status || ''));
            info.el.style.cursor = 'pointer';
        }
    });
    calendar.render();

    document.getElementById('applyFilter').addEventListener('click', function() {
        selectedClient = document.getElementById('clientFilter').value;
        calendar.removeAllEvents();
        calendar.refetchEvents();
    });

    document.getElementById('clearFilter').addEventListener('click', function() {
        selectedClient = '';
        document.getElementById('clientFilter').value = '';
        calendar.removeAllEvents();
        calendar.refetchEvents();
    });
});
</script>
@endsection
