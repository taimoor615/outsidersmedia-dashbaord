@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')

@section('title', 'Client Feedback')
@section('page-title', 'Client Feedback')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Client Feedback</h1>
            <p class="mt-1 sm:mt-2 text-gray-600 text-sm sm:text-base">
                {{ auth()->user()->isAdmin() ? 'All feedback across every client and post' : 'Feedback on the posts you created' }}
            </p>
        </div>
    </div>

    <!-- Filters -->
    @php
        $activeType = request('type', 'client');
        $queryParams = array_filter(request()->only(['type', 'client_id', 'date_filter', 'sort_order']), fn ($v) => $v !== null && $v !== '');
        $queryParamsNoType = $queryParams; unset($queryParamsNoType['type']);
    @endphp
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <!-- Type pills -->
        <div class="bg-gradient-to-r from-slate-50 to-gray-50/80 px-5 py-4 border-b border-gray-200/60">
            <div class="flex items-center gap-3 overflow-x-auto pb-1 -mb-1">
                <span class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-500 flex-shrink-0">
                    <svg class="w-4 h-4 text-[#CD571B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Source
                </span>
                <a href="{{ route('feedback.index', array_merge($queryParams, ['type' => 'client'])) }}"
                   class="flex-shrink-0 px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200 whitespace-nowrap {{ $activeType == 'client' ? 'bg-[#CD571B] text-white shadow-md shadow-orange-200' : 'text-gray-600 hover:bg-white hover:shadow-sm border border-gray-200' }}">Client</a>
                <a href="{{ route('feedback.index', array_merge($queryParams, ['type' => 'team'])) }}"
                   class="flex-shrink-0 px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200 whitespace-nowrap {{ $activeType == 'team' ? 'bg-[#CD571B] text-white shadow-md shadow-orange-200' : 'text-gray-600 hover:bg-white hover:shadow-sm border border-gray-200' }}">Team</a>
                <a href="{{ route('feedback.index', array_merge($queryParams, ['type' => 'all'])) }}"
                   class="flex-shrink-0 px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200 whitespace-nowrap {{ $activeType == 'all' ? 'bg-[#CD571B] text-white shadow-md shadow-orange-200' : 'text-gray-600 hover:bg-white hover:shadow-sm border border-gray-200' }}">All</a>
            </div>
        </div>
        <!-- Dropdown filters -->
        <form id="feedback-filter-form" method="GET" action="{{ route('feedback.index') }}" class="p-5">
            <input type="hidden" name="type" value="{{ $activeType }}">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Client</label>
                    <select name="client_id" class="feedback-filter-select w-full rounded-xl border-gray-300 text-sm py-2.5 focus:ring-2 focus:ring-[#CD571B]/30 focus:border-[#CD571B] bg-gray-50/50">
                        <option value="">All clients</option>
                        @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Date</label>
                    <select name="date_filter" class="feedback-filter-select w-full rounded-xl border-gray-300 text-sm py-2.5 focus:ring-2 focus:ring-[#CD571B]/30 focus:border-[#CD571B] bg-gray-50/50">
                        <option value="all" {{ request('date_filter', 'all') == 'all' ? 'selected' : '' }}>All time</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This week</option>
                        <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This month</option>
                        <option value="last_7_days" {{ request('date_filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="last_30_days" {{ request('date_filter') == 'last_30_days' ? 'selected' : '' }}>Last 30 days</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Order</label>
                    <select name="sort_order" class="feedback-filter-select w-full rounded-xl border-gray-300 text-sm py-2.5 focus:ring-2 focus:ring-[#CD571B]/30 focus:border-[#CD571B] bg-gray-50/50">
                        <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Newest first</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Oldest first</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <script>
    (function() {
        var form = document.getElementById('feedback-filter-form');
        if (!form) return;
        form.querySelectorAll('.feedback-filter-select').forEach(function(el) {
            el.addEventListener('change', function() { form.submit(); });
        });
    })();
    </script>

    <!-- Feedback list -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($feedback as $fb)
            <div class="p-5 sm:p-6 hover:bg-gray-50/50 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0" style="background:linear-gradient(135deg,#CD571B,#EC921A);">
                        {{ strtoupper(substr($fb->post?->client?->name ?? 'NA', 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3 mb-1 flex-wrap">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $fb->post?->client?->name ?? ($fb->client_name ?? 'Unknown Client') }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $fb->author_name }} · {{ $fb->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if($fb->action === 'approve')
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Approved</span>
                                @elseif($fb->action === 'request_changes')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Needs Changes</span>
                                @elseif($fb->action === 'reject')
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Rejected</span>
                                @elseif($fb->action === 'suggest_date')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Date Suggested</span>
                                @endif
                                @if($fb->is_client_feedback)
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">Client</span>
                                @else
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Team</span>
                                @endif
                            </div>
                        </div>
                        <p class="text-gray-700 text-sm mt-2">{{ $fb->feedback }}</p>
                        @if($fb->post)
                        <a href="{{ route('posts.show', $fb->post) }}" class="text-sm text-[#CD571B] hover:text-[#b54c17] font-medium mt-3 inline-block">View Post →</a>
                        @else
                        <span class="text-xs text-gray-400 mt-3 inline-block">(post deleted)</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No feedback found</h3>
                <p class="text-gray-500">Try adjusting your filters.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($feedback->hasPages())
    <div class="mt-2">
        {{ $feedback->onEachSide(1)->links('vendor.pagination.custom') }}
    </div>
    @endif

</div>
@endsection
