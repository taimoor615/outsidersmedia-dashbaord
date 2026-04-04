<!DOCTYPE html>
<html lang="en" x-data="{
    view: 'feed',
    filterPosts: 'all',
    feedbackModal: false,
    approveModal: false,
    rejectModal: false,
    noteModal: false,
    activePostId: null,
    calMonth: new Date().getMonth(),
    calYear: new Date().getFullYear()
}" x-effect="if(view==='calendar'){ $nextTick(function(){ window.renderCalendar(calMonth, calYear); }) }"
x-init="$watch('calMonth', function(v){ if(view==='calendar') $nextTick(function(){ window.renderCalendar(v, calYear); }) }); $watch('calYear', function(v){ if(view==='calendar') $nextTick(function(){ window.renderCalendar(calMonth, v); }) })">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $client->name }} - Content Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <style>
        [x-cloak] { display: none !important; }
        .cal-day-has-post { background: #EC921A; color: #fff; border-radius: 9999px; }
        .cal-day-today { font-weight: 900; text-decoration: underline; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- ───── HEADER ───── -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between gap-4">
                <!-- Logo -->
                <div class="logo-wrapper" style="max-width:180px;">
                    <img src="{{ asset('images/logo-img.png') }}" alt="Outsidersmedia" class="img-fluid">
                </div>
                <!-- Status counts -->
                <div class="flex items-center gap-2 flex-wrap justify-end">
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                        {{ $posts->where('status', 'pending_client')->count() }} Pending
                    </span>
                    <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">
                        {{ $posts->where('status', 'changes_requested')->count() }} Changes
                    </span>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- ───── WELCOME BANNER ───── -->
        <div class="mb-6 rounded-2xl shadow-lg p-6 text-white" style="background: linear-gradient(135deg, #CD571B 0%, #EC921A 100%);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold">{{ $client->name }}'s Content</h2>
                    <p class="text-orange-100 text-sm mt-1">Review and approve your upcoming social media posts.</p>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <button
                        @click="view = (view === 'calendar' ? 'feed' : 'calendar')"
                        style="display:flex;align-items:center;gap:8px;padding:8px 16px;background:rgba(255,255,255,0.25);border:1px solid rgba(255,255,255,0.5);color:#fff;font-size:14px;font-weight:600;border-radius:8px;cursor:pointer;transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.35)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.25)'"
                    >
                        <svg style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span x-text="view === 'calendar' ? 'Feed View' : 'Calendar View'">Calendar View</span>
                    </button>
                    <button
                        @click="noteModal = true"
                        style="display:flex;align-items:center;gap:8px;padding:8px 16px;background:rgba(255,255,255,0.25);border:1px solid rgba(255,255,255,0.5);color:#fff;font-size:14px;font-weight:600;border-radius:8px;cursor:pointer;transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.35)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.25)'"
                    >
                        <svg style="width:16px;height:16px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        Add Note
                    </button>
                </div>
            </div>
        </div>

        <!-- ───── FEED VIEW ───── -->
        <div x-show="view === 'feed'" x-cloak>

            <!-- Filter Tabs -->
            <div class="mb-5 flex gap-2 overflow-x-auto pb-1">
                <button @click="filterPosts = 'all'" :class="filterPosts === 'all' ? 'text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-colors" :style="filterPosts === 'all' ? 'background:#CD571B' : ''">
                    All ({{ $posts->count() }})
                </button>
                <button @click="filterPosts = 'pending'" :class="filterPosts === 'pending' ? 'bg-amber-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-colors">
                    Pending ({{ $posts->where('status', 'pending_client')->count() }})
                </button>
                <button @click="filterPosts = 'changes'" :class="filterPosts === 'changes' ? 'bg-orange-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-colors">
                    Changes ({{ $posts->where('status', 'changes_requested')->count() }})
                </button>
            </div>

            <!-- Feed (single column) -->
            <div class="space-y-6">
                @forelse($posts as $post)
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
                    x-show="filterPosts === 'all' ||
                            (filterPosts === 'pending' && '{{ $post->status }}' === 'pending_client') ||
                            (filterPosts === 'changes' && '{{ $post->status }}' === 'changes_requested')"
                    x-cloak
                >
                    <!-- ── Image ── -->
                    @if($post->media->count() > 0)
                    <div class="w-full bg-gray-100" style="max-height:480px;overflow:hidden;">
                        @if($post->post_type === 'carousel')
                        <div class="swiper miniCarousel-{{ $post->id }}" style="max-height:480px;">
                            <div class="swiper-wrapper">
                                @foreach($post->media as $media)
                                <div class="swiper-slide">
                                    <img src="{{ $media->url }}" alt="Post" class="w-full object-contain" style="max-height:480px;">
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        @elseif($post->media->first()->isImage())
                        <img src="{{ $post->media->first()->url }}" alt="Post" class="w-full object-contain" style="max-height:480px;">
                        @else
                        <video controls class="w-full" style="max-height:480px;">
                            <source src="{{ $post->media->first()->url }}" type="{{ $post->media->first()->mime_type }}">
                        </video>
                        @endif
                    </div>
                    @else
                    <div class="w-full flex items-center justify-center bg-gray-100" style="height:200px;">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif

                    <!-- ── Post Details (below image) ── -->
                    <div class="p-5">

                        <!-- Status badge + Post type + Networks -->
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            @if($post->status === 'pending_client')
                            <span class="px-3 py-1 text-xs font-bold rounded-full text-white" style="background:#CD571B;">Pending Your Review</span>
                            @elseif($post->status === 'changes_requested')
                            <span class="px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded-full">Changes Requested</span>
                            @elseif($post->status === 'pending_approval')
                            <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">With Admin</span>
                            @elseif(in_array($post->status, ['approved', 'scheduled']))
                            <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">Approved</span>
                            @elseif($post->status === 'published')
                            <span class="px-3 py-1 bg-purple-500 text-white text-xs font-bold rounded-full">Published</span>
                            @else
                            <span class="px-3 py-1 bg-gray-400 text-white text-xs font-bold rounded-full">Draft</span>
                            @endif

                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded capitalize">
                                {{ str_replace('_', ' ', $post->post_type) }}
                            </span>

                            @if($post->client->networks && count($post->client->networks) > 0)
                                @foreach($post->client->networks as $network)
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded capitalize">{{ $network }}</span>
                                @endforeach
                            @endif
                        </div>

                        <!-- Caption -->
                        @if($post->facebook_message || $post->instagram_message)
                        <p class="text-sm text-gray-800 mb-3 leading-relaxed">
                            {{ $post->facebook_message ?: $post->instagram_message }}
                        </p>
                        @endif

                        @if($post->tiktok_message)
                        <p class="text-xs text-gray-500 mb-1 font-semibold uppercase tracking-wide">TikTok</p>
                        <p class="text-sm text-gray-800 mb-3 leading-relaxed">{{ $post->tiktok_message }}</p>
                        @endif

                        @if($post->youtube_message)
                        <p class="text-xs text-gray-500 mb-1 font-semibold uppercase tracking-wide">YouTube</p>
                        <p class="text-sm text-gray-800 mb-3 leading-relaxed">{{ $post->youtube_message }}</p>
                        @endif

                        <!-- Scheduled date -->
                        @if($post->scheduled_at)
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ $post->scheduled_at->format('M d, Y \a\t g:i A') }}</span>
                        </div>
                        @endif

                        <!-- Previous feedback count -->
                        @if($post->feedback->where('is_client_feedback', true)->count() > 0)
                        <div class="mb-3 p-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-800 font-medium">
                                {{ $post->feedback->where('is_client_feedback', true)->count() }} feedback message(s) sent
                            </p>
                        </div>
                        @endif

                        <!-- ── Action Buttons ── -->
                        <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-100">

                            @if($post->status === 'pending_client')
                            <!-- Approve -->
                            <form action="{{ route('client.approve', [$client->share_token, $post]) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-1.5 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Approve
                                </button>
                            </form>

                            <!-- Request Changes -->
                            <button
                                @click="rejectModal = {{ $post->id }}"
                                class="flex items-center gap-1.5 px-4 py-2 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Request Changes
                            </button>
                            @endif

                            <!-- Add Feedback — always visible -->
                            <button
                                @click="feedbackModal = {{ $post->id }}"
                                class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-lg border transition"
                                style="border-color:#CD571B; color:#CD571B;"
                                onmouseover="this.style.background='#CD571B';this.style.color='#fff'"
                                onmouseout="this.style.background='';this.style.color='#CD571B'"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                Add Feedback
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No posts yet</h3>
                    <p class="text-gray-500">Your team will share content with you soon.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- ───── CALENDAR VIEW ───── -->
        <div x-show="view === 'calendar'" x-cloak>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

                <!-- Calendar header -->
                <div class="mb-5 pb-4 border-b border-gray-100">
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#CD571B;">Content Calendar</p>
                    <h3 class="text-xl font-bold text-gray-900">{{ $client->name }}</h3>
                </div>

                <!-- Month navigation -->
                <div class="flex items-center justify-between mb-6">
                    <button @click="if(calMonth===0){calMonth=11;calYear--}else{calMonth--}" class="p-2 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <h3 class="text-lg font-bold text-gray-900" x-text="new Date(calYear, calMonth).toLocaleDateString('en-US', {month:'long', year:'numeric'})"></h3>
                    <button @click="if(calMonth===11){calMonth=0;calYear++}else{calMonth++}" class="p-2 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <!-- Day headers -->
                <div class="grid grid-cols-7 mb-2">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                    <div class="text-center text-xs font-semibold text-gray-500 py-2">{{ $day }}</div>
                    @endforeach
                </div>

                <!-- Calendar grid (JS-rendered) -->
                <div class="grid grid-cols-7 gap-1" id="calGrid"></div>

                <!-- Posts for selected day -->
                <div id="calDayPosts" class="mt-6 space-y-3"></div>
            </div>
        </div>

    </div><!-- /container -->

    <!-- ───── MODALS ───── -->

    <!-- Feedback Modal (per post) -->
    @foreach($posts as $post)
    <div
        x-show="feedbackModal === {{ $post->id }}"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display:none;"
        @click.self="feedbackModal = false"
    >
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <h3 class="text-xl font-bold mb-1">Add Feedback</h3>
            <p class="text-sm text-gray-500 mb-4">Leave your thoughts on this post.</p>
            <form action="{{ route('client.feedback', $client->share_token) }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea name="feedback" rows="4" required placeholder="Share your thoughts..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent mb-4" style="--tw-ring-color:#CD571B;"></textarea>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 text-white py-3 rounded-xl font-semibold transition" style="background:#CD571B;" onmouseover="this.style.background='#b54c17'" onmouseout="this.style.background='#CD571B'">Submit Feedback</button>
                    <button type="button" @click="feedbackModal = false" class="px-6 bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Request Changes Modal -->
    <div
        x-show="rejectModal === {{ $post->id }}"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display:none;"
        @click.self="rejectModal = false"
    >
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <h3 class="text-xl font-bold mb-1">Request Changes</h3>
            <p class="text-sm text-gray-500 mb-4">Tell the team what you'd like changed.</p>
            <form action="{{ route('client.reject', [$client->share_token, $post]) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="request_changes">
                <textarea name="feedback" rows="4" required placeholder="What changes would you like to see?" class="w-full px-4 py-3 border border-orange-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent mb-4"></textarea>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-orange-600 text-white py-3 rounded-xl font-semibold hover:bg-orange-700 transition">Request Changes</button>
                    <button type="button" @click="rejectModal = false" class="px-6 bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Add Note Modal (general message) -->
    <div
        x-show="noteModal"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display:none;"
        @click.self="noteModal = false"
    >
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <h3 class="text-xl font-bold mb-1">Add a Note</h3>
            <p class="text-sm text-gray-500 mb-4">Leave a general message for your account manager.</p>
            <form action="{{ route('client.note', $client->share_token) }}" method="POST">
                @csrf
                <textarea name="note" rows="4" required placeholder="Type your message here..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent mb-4"></textarea>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 text-white py-3 rounded-xl font-semibold transition" style="background:#CD571B;" onmouseover="this.style.background='#b54c17'" onmouseout="this.style.background='#CD571B'">Send Note</button>
                    <button type="button" @click="noteModal = false" class="px-6 bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ───── SCRIPTS ───── -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // Swiper carousels
        document.querySelectorAll('[class*="miniCarousel-"]').forEach(function(el) {
            new Swiper('.' + el.classList[1], {
                loop: true,
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                pagination: { el: '.swiper-pagination', clickable: true },
            });
        });

        // Calendar
        const postDates = @json($posts->whereNotNull('scheduled_at')->map(function($p){ return ['date' => $p->scheduled_at->format('Y-m-d'), 'title' => $p->facebook_message ?: $p->instagram_message ?: 'Post', 'status' => $p->status]; })->values());

        window.renderCalendar = function renderCalendar(month, year) {
            const grid = document.getElementById('calGrid');
            if (!grid) return;
            grid.innerHTML = '';
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const today = new Date();

            for (let i = 0; i < firstDay; i++) {
                grid.innerHTML += '<div></div>';
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                const hasPosts = postDates.some(p => p.date === dateStr);
                const isToday = today.getDate()===d && today.getMonth()===month && today.getFullYear()===year;

                const btn = document.createElement('button');
                btn.textContent = d;
                btn.className = 'w-full text-center py-2 text-sm rounded-lg transition hover:bg-orange-50';
                if (hasPosts) {
                    btn.style.background = '#EC921A';
                    btn.style.color = '#fff';
                    btn.style.fontWeight = '700';
                }
                if (isToday) {
                    btn.style.outline = '2px solid #CD571B';
                }
                btn.addEventListener('click', () => showDayPosts(dateStr, d));
                grid.appendChild(btn);
            }
        }

        function showDayPosts(dateStr, day) {
            const container = document.getElementById('calDayPosts');
            const dayPosts = postDates.filter(p => p.date === dateStr);
            if (!dayPosts.length) {
                container.innerHTML = `<p class="text-sm text-gray-400 text-center">No posts scheduled on this day.</p>`;
                return;
            }
            container.innerHTML = `<h4 class="font-semibold text-gray-700 mb-2">Posts on ${dateStr}</h4>` +
                dayPosts.map(p => `
                    <div class="p-3 bg-orange-50 border border-orange-200 rounded-xl text-sm text-gray-800">
                        <span class="font-medium">${p.title.substring(0,80)}${p.title.length>80?'...':''}</span>
                        <span class="ml-2 text-xs text-orange-600 capitalize">${p.status.replace(/_/g,' ')}</span>
                    </div>
                `).join('');
        }

    });
    </script>

    <style>
    .swiper-button-next, .swiper-button-prev {
        color: white;
        background: rgba(0,0,0,0.4);
        width: 36px; height: 36px;
        border-radius: 50%;
    }
    .swiper-button-next:after, .swiper-button-prev:after { font-size: 16px; }
    .swiper-pagination-bullet { background: #CD571B; opacity: 0.6; }
    .swiper-pagination-bullet-active { opacity: 1; }
    </style>

</body>
</html>
