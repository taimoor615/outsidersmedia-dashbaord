@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')

@section('title', 'Posts')
@section('page-title', 'Posts')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Posts</h1>
            <p class="mt-2 text-gray-600">Manage your social media content</p>
        </div>
        <a href="{{ route('posts.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Post
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-start gap-3">
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-start gap-3">
        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filters: real-time, no Apply button -->
    @php
        $queryParams = array_filter(request()->only(['status', 'client_id', 'date_filter', 'sort_by', 'sort_order', 'search']), fn ($v) => $v !== null && $v !== '');
        $queryParamsNoStatus = $queryParams; unset($queryParamsNoStatus['status']);
    @endphp
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-gray-50/80 px-5 py-4 border-b border-gray-200/60">
            <div class="flex flex-wrap items-center gap-3">
                <span class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-500">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Status
                </span>
                <a href="{{ route('posts.index', $queryParamsNoStatus) }}"
                   class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ !request('status') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white hover:shadow-sm border border-gray-200' }}">All</a>
                <a href="{{ route('posts.index', array_merge($queryParams, ['status' => 'draft'])) }}"
                   class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request('status') == 'draft' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white hover:shadow-sm border border-gray-200' }}">Drafts</a>
                <a href="{{ route('posts.index', array_merge($queryParams, ['status' => 'pending_approval'])) }}"
                   class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request('status') == 'pending_approval' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white hover:shadow-sm border border-gray-200' }}">Pending</a>
                <a href="{{ route('posts.index', array_merge($queryParams, ['status' => 'approved'])) }}"
                   class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request('status') == 'approved' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white hover:shadow-sm border border-gray-200' }}">Approved</a>
                <a href="{{ route('posts.index', array_merge($queryParams, ['status' => 'scheduled'])) }}"
                   class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request('status') == 'scheduled' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white hover:shadow-sm border border-gray-200' }}">Scheduled</a>
                <a href="{{ route('posts.index', array_merge($queryParams, ['status' => 'published'])) }}"
                   class="px-3.5 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ request('status') == 'published' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-gray-600 hover:bg-white hover:shadow-sm border border-gray-200' }}">Published</a>
            </div>
        </div>
        <form id="posts-filter-form" method="GET" action="{{ route('posts.index') }}" class="p-5">
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Client</label>
                    <select name="client_id" class="posts-filter-select w-full rounded-xl border-gray-300 text-sm py-2.5 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-gray-50/50">
                        <option value="">All clients</option>
                        @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Date</label>
                    <select name="date_filter" class="posts-filter-select w-full rounded-xl border-gray-300 text-sm py-2.5 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-gray-50/50">
                        <option value="all" {{ request('date_filter', 'all') == 'all' ? 'selected' : '' }}>All time</option>
                        <option value="last_5_days" {{ request('date_filter') == 'last_5_days' ? 'selected' : '' }}>Last 5 days</option>
                        <option value="last_week" {{ request('date_filter') == 'last_week' ? 'selected' : '' }}>Last week</option>
                        <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This month</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Sort by</label>
                    <select name="sort_by" class="posts-filter-select w-full rounded-xl border-gray-300 text-sm py-2.5 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-gray-50/50">
                        <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Date created</option>
                        <option value="scheduled_at" {{ request('sort_by') == 'scheduled_at' ? 'selected' : '' }}>Scheduled</option>
                        <option value="client" {{ request('sort_by') == 'client' ? 'selected' : '' }}>Client</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Order</label>
                    <select name="sort_order" class="posts-filter-select w-full rounded-xl border-gray-300 text-sm py-2.5 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-gray-50/50">
                        <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Newest first</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Oldest first</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Search</label>
                    <div class="relative">
                        <input type="text" name="search" id="posts-search-input" value="{{ request('search') }}" placeholder="Caption or message..."
                               class="w-full rounded-xl border-gray-300 text-sm py-2.5 pl-10 pr-4 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 bg-gray-50/50">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
    (function() {
        var form = document.getElementById('posts-filter-form');
        if (!form) return;
        form.querySelectorAll('.posts-filter-select').forEach(function(el) {
            el.addEventListener('change', function() { form.submit(); });
        });
        var searchInput = document.getElementById('posts-search-input');
        if (searchInput) {
            var debounce = null;
            searchInput.addEventListener('input', function() {
                clearTimeout(debounce);
                debounce = setTimeout(function() { form.submit(); }, 400);
            });
        }
    })();
    </script>

    <!-- Posts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all">

            <!-- Post Media Preview -->
            <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                @if($post->media->count() > 0)
                    @php($firstMedia = $post->media->first())
                    @if($firstMedia->isImage())
                    <img src="{{ $firstMedia->url }}" alt="Post media" class="w-full h-full object-cover">
                    @else
                    <video src="{{ $firstMedia->url }}" class="w-full h-full object-cover" muted playsinline></video>
                    @endif
                @else
                <div class="flex items-center justify-center h-full">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                @endif

                <!-- Post Type Badge -->
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-gray-900 text-xs font-semibold rounded-full capitalize">
                        {{ str_replace('_', ' ', $post->post_type) }}
                    </span>
                </div>

                <!-- Media Count for Carousel -->
                @if($post->post_type === 'carousel')
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 bg-purple-500 text-white text-xs font-semibold rounded-full">
                        {{ $post->media->count() }} Images
                    </span>
                </div>
                @endif
            </div>

            <!-- Post Content -->
            <div class="p-6">
                <!-- Client Name -->
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                        {{ substr($post->client->name, 0, 2) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $post->client->name }}</p>
                        <p class="text-xs text-gray-500">{{ $post->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Caption Preview -->
                @if($post->caption || $post->facebook_message)
                <p class="text-sm text-gray-700 mb-4 line-clamp-2">
                    {{ $post->caption ?: $post->facebook_message ?: $post->instagram_message }}
                </p>
                @endif

                <!-- Platforms -->
                @if($post->platforms && count($post->platforms) > 0)
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($post->platforms as $platform)
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg capitalize">
                        {{ $platform }}
                    </span>
                    @endforeach
                </div>
                @endif

                <!-- Status Badge -->
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $post->status_badge }}">
                        {{ $post->status_label }}
                    </span>

                    @if($post->scheduled_at)
                    <span class="text-xs text-gray-500 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $post->scheduled_at->format('M d, g:i A') }}
                    </span>
                    @endif
                </div>

                <!-- Feedback Count -->
                @if($post->feedback->count() > 0)
                <div class="mb-4 p-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-xs text-yellow-800">
                        <span class="font-semibold">{{ $post->feedback->count() }}</span> feedback message(s)
                    </p>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                    <a href="{{ route('posts.show', $post) }}" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                        View
                    </a>

                    @if($post->canEdit())
                    <a href="{{ route('posts.edit', $post) }}" class="flex-1 px-4 py-2 bg-indigo-100 text-indigo-700 text-sm font-medium rounded-lg hover:bg-indigo-200 transition-colors text-center">
                        Edit
                    </a>
                    @endif

                    @if(auth()->user()->isAdmin() && $post->canDelete())
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors">
                            Delete
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No posts yet</h3>
                <p class="text-gray-500 mb-6">Create your first post to get started</p>
                <a href="{{ route('posts.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Post
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="mt-8">
        {{ $posts->links() }}
    </div>
    @endif

</div>
@endsection
