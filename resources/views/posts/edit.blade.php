@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')

@section('title', 'Edit Post')
@section('page-title', 'Edit Post')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="mb-8">
        <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Post Details
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Post</h1>
        <p class="mt-2 text-gray-600">Update your social media content</p>
    </div>

    @if(!$post->canEdit())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">
        This post cannot be edited in its current state ({{ $post->status_label }})
    </div>
    @else

    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" x-data="postEditForm()" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Client & Type (Read-only for editing) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Post Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Client</label>
                    <input
                        type="text"
                        value="{{ $post->client->name }}"
                        disabled
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50"
                    >
                    <input type="hidden" name="client_id" value="{{ $post->client_id }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Post Type</label>
                    <input
                        type="text"
                        value="{{ ucfirst($post->post_type) }}"
                        disabled
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 capitalize"
                    >
                    <input type="hidden" name="post_type" value="{{ $post->post_type }}">
                </div>
            </div>
        </div>

        <!-- Platform Selection (only platforms selected for this client) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Select Platforms *</h2>
            <p class="text-sm text-gray-500 mb-4">Only platforms chosen for {{ $post->client->name }} are shown.</p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($allowedPlatforms as $platform)
                <label class="relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all" :class="platforms.includes('{{ $platform }}') ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200'">
                    <input
                        type="checkbox"
                        name="platforms[]"
                        value="{{ $platform }}"
                        x-model="platforms"
                        {{ in_array($platform, $post->platforms ?? []) ? 'checked' : '' }}
                        class="sr-only"
                    >
                    <span class="text-sm font-semibold capitalize" :class="platforms.includes('{{ $platform }}') ? 'text-indigo-700' : 'text-gray-700'">{{ $platform }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Existing Media -->
        @if($post->media->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Current Media</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($post->media as $media)
                <div class="relative group">
                    @if($media->isImage())
                    <img src="{{ $media->url }}" alt="Post media" class="w-full h-32 object-cover rounded-lg">
                    @else
                    <video src="{{ $media->url }}" class="w-full h-32 object-cover rounded-lg" muted playsinline></video>
                    @endif

                    {{-- <form action="{{ route('post-media.delete', $media) }}" method="POST" class="absolute top-2 right-2" onsubmit="return confirm('Delete this media?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form> --}}
                    <button
                        type="button"
                        onclick="if(confirm('Delete this media?')) { document.getElementById('delete-media-{{ $media->id }}').submit(); }"
                        class="absolute top-2 right-2 p-2 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Add New Media -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Add New Media (Optional)</h2>

            @if($post->post_type === 'video')
            <div x-show="postType === 'video'">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Upload Video</label>
                <input
                    type="file"
                    name="media[]"
                    accept="video/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                >
                <p class="mt-2 text-sm text-gray-500">Supported: MP4, MOV. Max 100MB</p>
            </div>
            @else
            <div x-show="postType !== 'video'">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Upload Image(s)</label>
                <input
                    type="file"
                    name="media[]"
                    multiple
                    accept="image/*"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                >
                <p class="mt-2 text-sm text-gray-500">You can upload additional image files</p>
            </div>
            @endif
        </div>

        <!-- Platform Messages (only show selected platforms) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Platform Messages</h2>
            <p class="text-sm text-gray-500 mb-4">Edit messages for the platforms you selected above.</p>

            <div class="space-y-6">
                <div x-show="platforms.includes('facebook')">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Facebook Message</label>
                    <textarea
                        name="facebook_message"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >{{ old('facebook_message', $post->facebook_message) }}</textarea>
                </div>

                <div x-show="platforms.includes('instagram')">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Instagram Message</label>
                    <textarea
                        name="instagram_message"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >{{ old('instagram_message', $post->instagram_message) }}</textarea>
                </div>

                <div x-show="platforms.includes('tiktok')">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">TikTok Message</label>
                    <textarea
                        name="tiktok_message"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >{{ old('tiktok_message', $post->tiktok_message) }}</textarea>
                </div>

                <div x-show="platforms.includes('youtube')">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">YouTube Short Message</label>
                    <textarea
                        name="youtube_message"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >{{ old('youtube_message', $post->youtube_message) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Google Business Profile Section (only when Google is selected) -->
        <div x-show="platforms.includes('google')" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Google Business Profile
            </h2>

            <!-- Google Post Type Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Post Type *</label>
                <div class="grid grid-cols-3 gap-4">
                    <label class="relative p-4 border-2 rounded-xl cursor-pointer transition-all" :class="googlePostType === 'whats_new' ? 'border-green-600 bg-green-50' : 'border-gray-200 hover:border-green-300'">
                        <input type="radio" name="google_post_type" value="whats_new" x-model="googlePostType" class="sr-only">
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2" :class="googlePostType === 'whats_new' ? 'text-green-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm font-semibold" :class="googlePostType === 'whats_new' ? 'text-green-700' : 'text-gray-700'">What's New</p>
                        </div>
                    </label>
                    <label class="relative p-4 border-2 rounded-xl cursor-pointer transition-all" :class="googlePostType === 'offer' ? 'border-orange-600 bg-orange-50' : 'border-gray-200 hover:border-orange-300'">
                        <input type="radio" name="google_post_type" value="offer" x-model="googlePostType" class="sr-only">
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2" :class="googlePostType === 'offer' ? 'text-orange-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm font-semibold" :class="googlePostType === 'offer' ? 'text-orange-700' : 'text-gray-700'">Offer</p>
                        </div>
                    </label>
                    <label class="relative p-4 border-2 rounded-xl cursor-pointer transition-all" :class="googlePostType === 'event' ? 'border-purple-600 bg-purple-50' : 'border-gray-200 hover:border-purple-300'">
                        <input type="radio" name="google_post_type" value="event" x-model="googlePostType" class="sr-only">
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2" :class="googlePostType === 'event' ? 'text-purple-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm font-semibold" :class="googlePostType === 'event' ? 'text-purple-700' : 'text-gray-700'">Event</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Title (for Offer and Event) -->
            <div x-show="googlePostType !== 'whats_new'" class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2" x-text="googlePostType === 'offer' ? 'Offer Title *' : 'Event Title *'"></label>
                <input
                    type="text"
                    name="google_title"
                    value="{{ old('google_title', $post->google_title) }}"
                    placeholder="Enter title..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
            </div>

            <!-- Offer Details -->
            <div x-show="googlePostType === 'offer'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date *</label>
                        <input
                            type="date"
                            name="offer_start_date"
                            value="{{ old('offer_start_date', $post->offer_start_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">End Date *</label>
                        <input
                            type="date"
                            name="offer_end_date"
                            value="{{ old('offer_end_date', $post->offer_end_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" x-model="showOfferTime" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="text-sm font-medium text-gray-700">Add specific times</span>
                    </label>
                </div>
                <div x-show="showOfferTime" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                        <input type="time" name="offer_start_time" value="{{ old('offer_start_time', $post->offer_start_time?->format('H:i')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                        <input type="time" name="offer_end_time" value="{{ old('offer_end_time', $post->offer_end_time?->format('H:i')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" x-model="showOfferDetails" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="text-sm font-medium text-gray-700">Add more details (Optional)</span>
                    </label>
                </div>
                <div x-show="showOfferDetails" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Coupon Code</label>
                        <input type="text" name="offer_code" value="{{ old('offer_code', $post->offer_code) }}" placeholder="e.g., SAVE20" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Offer Link</label>
                        <input type="url" name="offer_link" value="{{ old('offer_link', $post->offer_link) }}" placeholder="https://..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Terms and Conditions</label>
                        <textarea name="offer_terms" rows="3" placeholder="Enter terms and conditions..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('offer_terms', $post->offer_terms) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <div x-show="googlePostType === 'event'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date *</label>
                        <input type="date" name="event_start_date" value="{{ old('event_start_date', $post->event_start_date?->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">End Date *</label>
                        <input type="date" name="event_end_date" value="{{ old('event_end_date', $post->event_end_date?->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time *</label>
                        <input type="time" name="event_start_time" value="{{ old('event_start_time', $post->event_start_time?->format('H:i')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">End Time *</label>
                        <input type="time" name="event_end_time" value="{{ old('event_end_time', $post->event_end_time?->format('H:i')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Button (for all Google post types) -->
            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Add Button (Optional)</label>
                    <select name="google_button" x-model="googleButton" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="none">None</option>
                        <option value="book">Book</option>
                        <option value="order">Order Online</option>
                        <option value="buy">Buy</option>
                        <option value="learn_more">Learn More</option>
                        <option value="sign_up">Sign Up</option>
                    </select>
                </div>
                <div x-show="googleButton && googleButton !== 'none'">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Button Link *</label>
                    <input type="url" name="google_button_link" value="{{ old('google_button_link', $post->google_button_link) }}" placeholder="https://..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Schedule -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Schedule</h2>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Schedule Date & Time</label>
                <input
                    type="datetime-local"
                    name="scheduled_at"
                    value="{{ old('scheduled_at', $post->scheduled_at?->format('Y-m-d\TH:i')) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <button
                type="submit"
                class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 px-6 rounded-xl font-semibold shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 transition-all"
            >
                Update Post
            </button>
            <a
                href="{{ route('posts.show', $post) }}"
                class="px-8 py-4 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all"
            >
                Cancel
            </a>
        </div>

    </form>
    @endif
    @foreach($post->media as $media)
    <form
        id="delete-media-{{ $media->id }}"
        action="{{ route('post-media.delete', $media) }}"
        method="POST"
        style="display: none;"
    >
        @csrf
        @method('DELETE')
    </form>
    @endforeach
</div>

<script>
function postEditForm() {
    return {
        postType: @json($post->post_type),
        platforms: @json($post->platforms ?? []),
        googlePostType: @json($post->google_post_type ?? 'whats_new'),
        googleButton: @json($post->google_button ?? 'none'),
        showOfferTime: {{ ($post->offer_start_time || $post->offer_end_time) ? 'true' : 'false' }},
        showOfferDetails: {{ ($post->offer_code || $post->offer_link || $post->offer_terms) ? 'true' : 'false' }},
    }
}
</script>
@endsection
