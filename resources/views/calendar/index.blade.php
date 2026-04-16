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
                    All scheduled and published posts across all clients.
                @else
                    All scheduled and published posts. Click an event to view details.
                @endif
            </p>
        </div>
        <a href="{{ route('posts.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition text-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            View Posts
        </a>
    </div>

    <!-- Client Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-wrap items-center gap-3">
        <label class="text-sm font-semibold text-gray-700 flex-shrink-0">Filter by client:</label>
        <select id="clientFilter" class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:border-transparent" style="--tw-ring-color:#CD571B;">
            <option value="">All Clients</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}">{{ $client->name }}</option>
            @endforeach
        </select>
        <button id="applyFilter" class="px-4 py-2 text-sm font-semibold text-white rounded-xl transition" style="background:#CD571B;" onmouseover="this.style.background='#b54c17'" onmouseout="this.style.background='#CD571B'">
            Apply
        </button>
        <button id="clearFilter" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
            Clear
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <div id="calendar"></div>
    </div>

    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full" style="background:#CD571B;"></span> Scheduled
        </span>
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full" style="background:#EC921A;"></span> Published
        </span>
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500"></span> Approved
        </span>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
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
        events: function(fetchInfo, successCallback, failureCallback) {
            let url = eventsBase + '?start=' + fetchInfo.startStr + '&end=' + fetchInfo.endStr;
            if (selectedClient) url += '&client_id=' + selectedClient;
            fetch(url)
                .then(r => r.json())
                .then(data => successCallback(data))
                .catch(e => failureCallback(e));
        },
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

    document.getElementById('applyFilter').addEventListener('click', function() {
        selectedClient = document.getElementById('clientFilter').value;
        calendar.refetchEvents();
    });

    document.getElementById('clearFilter').addEventListener('click', function() {
        selectedClient = '';
        document.getElementById('clientFilter').value = '';
        calendar.refetchEvents();
    });
});
</script>
@endsection
