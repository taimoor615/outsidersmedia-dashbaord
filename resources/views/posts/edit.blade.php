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

        <!-- Platform Selection -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Select Platforms *</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                $platformIcons = [
                    'facebook' => ['color' => 'blue', 'svg' => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>'],
                    'instagram' => ['color' => 'pink', 'svg' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>'],
                ];
                @endphp

                @foreach(['facebook', 'instagram', 'linkedin', 'twitter', 'tiktok', 'youtube', 'google'] as $platform)
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
                    <img src="{{ asset('storage/' . $post->media[0]->file_path) }}" alt="Post media" class="w-full h-32 object-cover rounded-lg">
                    @else
                    <video src="{{ asset('storage/' . $post->media->first()->file_path) }}" class="w-full h-32 object-cover rounded-lg"></video>
                    @endif

                    <form action="{{ route('post-media.delete', $media) }}" method="POST" class="absolute top-2 right-2" onsubmit="return confirm('Delete this media?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Add New Media -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Add New Media (Optional)</h2>

            <input
                type="file"
                name="media[]"
                multiple
                accept="image/*,video/*"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
            >
            <p class="mt-2 text-sm text-gray-500">You can upload additional media files</p>
        </div>

        <!-- Platform Messages -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Platform Messages</h2>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Facebook Message</label>
                    <textarea
                        name="facebook_message"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >{{ old('facebook_message', $post->facebook_message) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Instagram Message</label>
                    <textarea
                        name="instagram_message"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >{{ old('instagram_message', $post->instagram_message) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">TikTok Message</label>
                    <textarea
                        name="tiktok_message"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >{{ old('tiktok_message', $post->tiktok_message) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">YouTube Short Message</label>
                    <textarea
                        name="youtube_message"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >{{ old('youtube_message', $post->youtube_message) }}</textarea>
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
</div>

<script>
function postEditForm() {
    return {
        platforms: @json($post->platforms ?? [])
    }
}
</script>
@endsection
