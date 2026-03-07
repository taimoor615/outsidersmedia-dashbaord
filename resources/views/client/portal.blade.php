<!DOCTYPE html>
<html lang="en" x-data="{ selectedPost: null, feedbackModal: false, approveModal: false, rejectModal: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $client->name }} - Content Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($client->name, 0, 2) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h1>
                        <p class="text-sm text-gray-600">Content Approval Portal</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 bg-amber-100 text-amber-700 text-sm font-semibold rounded-lg">
                        {{ $posts->where('status', 'pending_client')->count() }} Pending Your Review
                    </span>
                    <span class="px-4 py-2 bg-orange-100 text-orange-700 text-sm font-semibold rounded-lg">
                        {{ $posts->where('status', 'changes_requested')->count() }} Changes Requested
                    </span>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Welcome Message -->
        <div class="mb-8 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 text-white">
            <h2 class="text-3xl font-bold mb-2">Welcome to Your Content Portal!</h2>
            <p class="text-indigo-100 text-lg">Review and approve your upcoming social media posts. Click on any post to view details and take action.</p>
        </div>

        <!-- Filter Tabs & Posts Grid: All posts shown; Pending = awaiting your action; Changes requested = team is working on it -->
        <div x-data="{ filterPosts: 'all' }">
        <div class="mb-6 flex gap-2 overflow-x-auto pb-2">
            <button @click="filterPosts = 'all'" :class="filterPosts === 'all' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-colors">
                All Posts ({{ $posts->count() }})
            </button>
            <button @click="filterPosts = 'pending'" :class="filterPosts === 'pending' ? 'bg-amber-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-colors">
                Pending ({{ $posts->where('status', 'pending_client')->count() }})
            </button>
            <button @click="filterPosts = 'changes'" :class="filterPosts === 'changes' ? 'bg-orange-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="px-4 py-2 rounded-lg font-medium whitespace-nowrap transition-colors">
                Changes Requested ({{ $posts->where('status', 'changes_requested')->count() }})
            </button>
        </div>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all cursor-pointer"
                @click="selectedPost = {{ $post->id }}"
                x-show="filterPosts === 'all' ||
                        (filterPosts === 'pending' && '{{ $post->status }}' === 'pending_client') ||
                        (filterPosts === 'changes' && '{{ $post->status }}' === 'changes_requested')"
                x-cloak
            >
                <!-- Media Preview -->
                <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                    @if($post->media->count() > 0)
                        @if($post->post_type === 'carousel')
                        <div class="swiper miniCarousel-{{ $post->id }} h-full">
                            <div class="swiper-wrapper">
                                @foreach($post->media as $media)
                                <div class="swiper-slide">
                                    <img src="{{ $media->url }}" alt="Post" class="w-full h-full object-cover">
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                        @elseif($post->media->first()->isImage())
                        <img src="{{ $post->media->first()->url }}" alt="Post" class="w-full h-full object-cover">
                        @else
                        <video src="{{ $post->media->first()->url }}" class="w-full h-full object-cover" muted playsinline></video>
                        @endif
                    @else
                    <div class="flex items-center justify-center h-full">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        @if($post->status === 'pending_client')
                        <span class="px-3 py-1 bg-amber-500 text-white text-xs font-bold rounded-full">Pending Your Review</span>
                        @elseif($post->status === 'changes_requested')
                        <span class="px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded-full">Changes Requested</span>
                        @elseif($post->status === 'pending_approval')
                        <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">With Admin</span>
                        @elseif($post->status === 'approved' || $post->status === 'scheduled')
                        <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">Approved</span>
                        @elseif($post->status === 'published')
                        <span class="px-3 py-1 bg-purple-500 text-white text-xs font-bold rounded-full">Published</span>
                        @elseif($post->status === 'draft')
                        <span class="px-3 py-1 bg-gray-500 text-white text-xs font-bold rounded-full">Draft</span>
                        @endif
                    </div>
                </div>

                <!-- Post Info -->
                <div class="p-6">
                    <div class="mb-3">
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded capitalize">
                            {{ str_replace('_', ' ', $post->post_type) }}
                        </span>
                    </div>

                    @if($post->facebook_message || $post->instagram_message)
                    <p class="text-sm text-gray-700 mb-4 line-clamp-3">
                        {{ $post->facebook_message ?: $post->instagram_message }}
                    </p>
                    @endif

                    @if($post->client->networks && count($post->client->networks) > 0)
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($post->client->networks as $network)
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded capitalize">
                            {{ $network }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    @if($post->scheduled_at)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $post->scheduled_at->format('M d, Y g:i A') }}</span>
                    </div>
                    @endif

                    @if($post->feedback->where('is_client_feedback', true)->count() > 0)
                    <div class="mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-xs text-yellow-800">
                            {{ $post->feedback->where('is_client_feedback', true)->count() }} feedback message(s)
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No posts to review</h3>
                <p class="text-gray-500">Your team will share content with you soon</p>
            </div>
            @endforelse
        </div>
        </div>
    </div>

    <!-- Post Detail Modal -->
    @foreach($posts as $post)
    <div
        x-show="selectedPost === {{ $post->id }}"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto"
        style="display: none;"
        @click.self="selectedPost = null"
    >
        <div class="min-h-screen px-4 py-8 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.stop>

                <!-- Modal Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between z-10">
                    <h2 class="text-2xl font-bold text-gray-900">Post Details</h2>
                    <button @click="selectedPost = null" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">

                    <!-- Media -->
                    @if($post->media->count() > 0)
                    <div>
                        @if($post->post_type === 'carousel')
                        <div class="swiper postCarousel-{{ $post->id }}">
                            <div class="swiper-wrapper">
                                @foreach($post->media as $media)
                                <div class="swiper-slide">
                                    <img src="{{ $media->url }}" alt="Post" class="w-full h-96 object-cover rounded-xl">
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                        @elseif($post->media->first()->isImage())
                        <img src="{{ $post->media->first()->url }}" alt="Post" class="w-full h-auto rounded-xl">
                        @else
                        <video controls class="w-full h-auto rounded-xl">
                            <source src="{{ $post->media->first()->url }}" type="{{ $post->media->first()->mime_type }}">
                        </video>
                        @endif
                    </div>
                    @endif

                    <!-- Post Content -->
                    <div class="space-y-4">
                        @if($post->facebook_message)
                        <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                            <p class="text-xs text-blue-600 font-semibold mb-2">FACEBOOK</p>
                            <p class="text-sm text-gray-900">{{ $post->facebook_message }}</p>
                        </div>
                        @endif

                        @if($post->instagram_message)
                        <div class="p-4 border border-pink-200 rounded-lg bg-pink-50">
                            <p class="text-xs text-pink-600 font-semibold mb-2">INSTAGRAM</p>
                            <p class="text-sm text-gray-900">{{ $post->instagram_message }}</p>
                        </div>
                        @endif

                        @if($post->tiktok_message)
                        <div class="p-4 border border-gray-300 rounded-lg bg-gray-50">
                            <p class="text-xs text-gray-700 font-semibold mb-2">TIKTOK</p>
                            <p class="text-sm text-gray-900">{{ $post->tiktok_message }}</p>
                        </div>
                        @endif

                        @if($post->youtube_message)
                        <div class="p-4 border border-red-200 rounded-lg bg-red-50">
                            <p class="text-xs text-red-600 font-semibold mb-2">YOUTUBE</p>
                            <p class="text-sm text-gray-900">{{ $post->youtube_message }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Schedule Info -->
                    @if($post->scheduled_at)
                    <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <p class="text-sm font-semibold text-indigo-900 mb-1">Scheduled for:</p>
                        <p class="text-lg font-bold text-indigo-700">{{ $post->scheduled_at->format('l, F j, Y \a\t g:i A') }}</p>
                    </div>
                    @endif

                    <!-- Previous Feedback -->
                    @if($post->feedback->where('is_client_feedback', true)->count() > 0)
                    <div class="border-t pt-6">
                        <h3 class="font-bold text-gray-900 mb-4">Your Previous Feedback</h3>
                        <div class="space-y-3">
                            @foreach($post->feedback->where('is_client_feedback', true) as $feedback)
                            <div class="border-l-4 border-yellow-500 pl-4 py-2 bg-yellow-50">
                                <p class="text-sm text-gray-900">{{ $feedback->feedback }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $feedback->created_at->diffForHumans() }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons: only when post is pending client review -->
                    @if($post->status === 'pending_client')
                    <div class="border-t pt-6 space-y-3">
                        <form action="{{ route('client.approve', [$client->share_token, $post]) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white py-4 rounded-xl font-bold hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approve Post
                            </button>
                        </form>

                        <button
                            @click="rejectModal = {{ $post->id }}"
                            class="w-full bg-orange-600 text-white py-4 rounded-xl font-bold hover:bg-orange-700 transition-colors flex items-center justify-center gap-2"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Request Changes
                        </button>

                        <button
                            @click="feedbackModal = {{ $post->id }}"
                            class="w-full bg-gray-200 text-gray-700 py-4 rounded-xl font-bold hover:bg-gray-300 transition-colors flex items-center justify-center gap-2"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Add Note/Feedback
                        </button>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div
        x-show="feedbackModal === {{ $post->id }}"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display: none;"
        @click.self="feedbackModal = false"
    >
        <div class="bg-white rounded-xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold mb-4">Add Feedback</h3>
            <form action="{{ route('client.feedback', $client->share_token) }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea
                    name="feedback"
                    rows="4"
                    required
                    placeholder="Share your thoughts about this post..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent mb-4"
                ></textarea>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700">
                        Submit Feedback
                    </button>
                    <button type="button" @click="feedbackModal = false" class="px-6 bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-300">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Request Changes Modal -->
    <div
        x-show="rejectModal === {{ $post->id }}"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display: none;"
        @click.self="rejectModal = false"
    >
        <div class="bg-white rounded-xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold mb-4">Request Changes</h3>
            <form action="{{ route('client.reject', [$client->share_token, $post]) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="request_changes">
                <textarea
                    name="feedback"
                    rows="4"
                    required
                    placeholder="What changes would you like to see?"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent mb-4"
                ></textarea>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-orange-600 text-white py-3 rounded-xl font-semibold hover:bg-orange-700">
                        Request Changes
                    </button>
                    <button type="button" @click="rejectModal = false" class="px-6 bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-300">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize mini carousels
        document.querySelectorAll('[class*="miniCarousel-"]').forEach(function(el) {
            new Swiper('.' + el.classList[1], {
                loop: true,
                pagination: { el: '.swiper-pagination', clickable: true },
                autoplay: { delay: 3000 },
            });
        });

        // Initialize modal carousels
        document.querySelectorAll('[class*="postCarousel-"]').forEach(function(el) {
            new Swiper('.' + el.classList[1], {
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: { el: '.swiper-pagination', clickable: true },
            });
        });
    });
    </script>

    <style>
    .swiper-button-next, .swiper-button-prev {
        color: white;
        background: rgba(0, 0, 0, 0.5);
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
    .swiper-button-next:after, .swiper-button-prev:after { font-size: 20px; }
    .swiper-pagination-bullet { background: white; opacity: 0.7; }
    .swiper-pagination-bullet-active { opacity: 1; }
    </style>

</body>
</html>
