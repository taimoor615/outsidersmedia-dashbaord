@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')

@section('title', 'Post Details')
@section('page-title', 'Post Details')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <a href="{{ route('posts.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Posts
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Post Details</h1>
            <p class="mt-2 text-gray-600">{{ $post->client->name }} • {{ $post->created_at->format('M d, Y g:i A') }}</p>
        </div>

        <div class="flex items-center gap-3">
            @if($post->canEdit())
            <a href="{{ route('posts.edit', $post) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Post
            </a>
            @endif

            @if(auth()->user()->isAdmin() && $post->canDelete())
            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Media Preview -->
            @if($post->media->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Media</h2>

                @if($post->post_type === 'carousel')
                <div class="grid grid-cols-2 gap-4">
                    @foreach($post->media as $media)
                    @if($media->isImage())
                    <img src="{{ $media->url }}" alt="Post media" class="w-full h-48 object-cover rounded-lg">
                    @else
                    <video src="{{ $media->url }}" class="w-full h-48 object-cover rounded-lg" muted playsinline></video>
                    @endif
                    @endforeach
                </div>
                @else
                <div>
                    @php($firstMedia = $post->media->first())
                    @if($firstMedia->isImage())
                    <img src="{{ $firstMedia->url }}" alt="Post media" class="w-full h-auto rounded-lg">
                    @else
                    <video controls class="w-full h-auto rounded-lg">
                        <source src="{{ $firstMedia->url }}" type="{{ $firstMedia->mime_type }}">
                    </video>
                    @endif
                </div>
                @endif
            </div>
            @endif

            <!-- Platform Messages -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Platform Content</h2>

                <div class="space-y-4">
                    @if($post->facebook_message)
                    <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            <span class="font-semibold text-blue-900">Facebook</span>
                        </div>
                        <p class="text-sm text-blue-900">{{ $post->facebook_message }}</p>
                    </div>
                    @endif

                    @if($post->instagram_message)
                    <div class="p-4 border border-pink-200 rounded-lg bg-pink-50">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            <span class="font-semibold text-pink-900">Instagram</span>
                        </div>
                        <p class="text-sm text-pink-900">{{ $post->instagram_message }}</p>
                    </div>
                    @endif

                    @if($post->tiktok_message)
                    <div class="p-4 border border-gray-300 rounded-lg bg-gray-50">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="font-semibold text-gray-900">TikTok</span>
                        </div>
                        <p class="text-sm text-gray-900">{{ $post->tiktok_message }}</p>
                    </div>
                    @endif

                    @if($post->youtube_message)
                    <div class="p-4 border border-red-200 rounded-lg bg-red-50">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            <span class="font-semibold text-red-900">YouTube</span>
                        </div>
                        <p class="text-sm text-red-900">{{ $post->youtube_message }}</p>
                    </div>
                    @endif
                </div>

                @if($post->webpage_url)
                <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Webpage URL:</p>
                    <a href="{{ $post->webpage_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 text-sm break-all">
                        {{ $post->webpage_url }}
                    </a>
                </div>
                @endif
            </div>

            <!-- Client Feedback -->
            @if($post->feedback->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Feedback History</h2>

                <div class="space-y-4">
                    @foreach($post->feedback as $feedback)
                    <div class="border-l-4 pl-4 py-2 {{ $feedback->is_client_feedback ? 'border-yellow-500 bg-yellow-50' : 'border-blue-500 bg-blue-50' }}">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $feedback->author_name }}</p>
                                <p class="text-xs text-gray-500">{{ $feedback->created_at->diffForHumans() }}</p>
                            </div>
                            @if($feedback->action)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $feedback->action === 'approve' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $feedback->action === 'request_changes' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $feedback->action === 'reject' ? 'bg-red-100 text-red-800' : '' }}
                            ">
                                {{ ucwords(str_replace('_', ' ', $feedback->action)) }}
                            </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-700">{{ $feedback->feedback }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">

            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Status</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Current Status</p>
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full {{ $post->status_badge }}">
                            {{ $post->status_label }}
                        </span>
                    </div>

                    @if($post->scheduled_at)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Scheduled For</p>
                        <p class="text-sm font-medium text-gray-900">{{ $post->scheduled_at->format('M d, Y g:i A') }}</p>
                    </div>
                    @endif

                    @if($post->published_at)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Published At</p>
                        <p class="text-sm font-medium text-gray-900">{{ $post->published_at->format('M d, Y g:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Post Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Post Information</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Type</p>
                        <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $post->post_type) }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 mb-1">Created By</p>
                        <p class="font-medium text-gray-900">{{ $post->creator->name }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 mb-1">Client</p>
                        <a href="{{ route('clients.show', $post->client) }}" class="font-medium text-indigo-600 hover:text-indigo-700">
                            {{ $post->client->name }}
                        </a>
                    </div>

                    @if($post->platforms && count($post->platforms) > 0)
                    <div>
                        <p class="text-gray-500 mb-2">Platforms</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($post->platforms as $platform)
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg capitalize">
                                {{ $platform }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if($post->status === 'draft')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Actions</h3>
                <form action="{{ route('posts.submit-approval', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                        Send to Client for Review
                    </button>
                </form>
                <p class="mt-2 text-xs text-gray-500">Client will approve or request changes. Admin approves and schedules only after client finalizes.</p>
            </div>
            @endif

            @if($post->status === 'pending_client')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Status</h3>
                <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
                    Waiting for client to approve or request changes. No admin action needed yet.
                </p>
            </div>
            @endif

            @if($post->status === 'changes_requested')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Actions</h3>
                <p class="text-sm text-orange-700 bg-orange-50 border border-orange-200 rounded-lg px-4 py-3 mb-3">
                    Client requested changes. Implement the feedback above, then resubmit to client.
                </p>
                <form action="{{ route('posts.resubmit-to-client', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                        Resubmit to Client
                    </button>
                </form>
            </div>
            @endif

            @if($post->status === 'pending_approval')
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Admin Actions</h3>
                @if(auth()->user()->isAdmin())
                    <div class="space-y-3">
                        <form action="{{ route('posts.approve', $post) }}" method="POST" class="inline-block w-full">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-colors">
                                Approve Post
                            </button>
                        </form>
                        <form action="{{ route('posts.return-to-client', $post) }}" method="POST" class="space-y-2">
                            @csrf
                            <textarea name="feedback" rows="2" placeholder="Optional note for team/client..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></textarea>
                            <button type="submit" class="w-full px-4 py-2 bg-amber-600 text-white font-semibold rounded-xl hover:bg-amber-700 transition-colors">
                                Return to Client for Review
                            </button>
                        </form>
                    </div>
                @else
                    <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
                        Client has approved. Pending admin approval and scheduling.
                    </p>
                @endif
            </div>
            @endif

            @if($post->status === 'approved' && auth()->user()->isAdmin())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Schedule for Publish</h3>
                <form action="{{ route('posts.schedule', $post) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Date &amp; time</label>
                        <input type="datetime-local" name="scheduled_at" required
                            min="{{ now()->addMinute()->format('Y-m-d\TH:i') }}"
                            value="{{ old('scheduled_at', $post->scheduled_at?->format('Y-m-d\TH:i')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        @error('scheduled_at')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                        Schedule Post
                    </button>
                </form>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
