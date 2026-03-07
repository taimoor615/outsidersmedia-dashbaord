@extends('layouts.team')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-500 rounded-2xl shadow-xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-blue-100 text-lg">Ready to create amazing content today?</p>
            </div>
            <div class="hidden lg:block">
                <div class="w-24 h-24 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['draft_posts'] }}</h3>
            <p class="text-sm text-gray-600">Draft Posts</p>
            @if($stats['draft_posts'] > 0)
            <a href="{{ route('posts.index', ['status' => 'draft']) }}" class="text-xs text-blue-600 hover:text-blue-700 mt-1 inline-block">Continue editing →</a>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['pending_approval'] }}</h3>
            <p class="text-sm text-gray-600">Pending Approval</p>
            @if($stats['pending_approval'] > 0)
            <a href="{{ route('posts.index', ['status' => 'pending_approval']) }}" class="text-xs text-yellow-600 hover:text-yellow-700 mt-1 inline-block">View posts →</a>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                @if($stats['changes_requested'] > 0)
                <span class="text-xs font-semibold text-orange-600 bg-orange-100 px-2 py-1 rounded-full">Action Needed</span>
                @endif
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['changes_requested'] }}</h3>
            <p class="text-sm text-gray-600">Needs Changes</p>
            @if($stats['changes_requested'] > 0)
            <a href="{{ route('posts.index', ['status' => 'changes_requested']) }}" class="text-xs text-orange-600 hover:text-orange-700 mt-1 inline-block">Fix now →</a>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['scheduled_posts'] }}</h3>
            <p class="text-sm text-gray-600">Scheduled</p>
            <p class="text-xs text-gray-500 mt-1">{{ $stats['approved_this_week'] }} approved this week</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('posts.create') }}" class="flex items-center gap-4 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-xl hover:shadow-md transition-all">
                <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Create Post</p>
                    <p class="text-xs text-gray-600">New social content</p>
                </div>
            </a>

            <a href="{{ route('clients.index') }}" class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-xl hover:shadow-md transition-all">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">View Clients</p>
                    <p class="text-xs text-gray-600">Manage client info</p>
                </div>
            </a>

            <a href="{{ route('posts.index') }}" class="flex items-center gap-4 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl hover:shadow-md transition-all">
                <div class="w-12 h-12 bg-yellow-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">All Posts</p>
                    <p class="text-xs text-gray-600">{{ $stats['total_posts_created'] }} total posts</p>
                </div>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Posts Needing Action -->
        @if($postsNeedingAction->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Posts Needing Changes</h2>
                <p class="text-sm text-gray-600">Client requested changes</p>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($postsNeedingAction as $post)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start gap-4">
                        @if($post->media->count() > 0)
                        @if($post->media->first()->isImage())
                        <img src="{{ $post->media->first()->url }}" alt="Post" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                        @else
                        <video src="{{ $post->media->first()->url }}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0" muted playsinline></video>
                        @endif
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 mb-1">{{ $post->client->name }}</h3>
                            <p class="text-sm text-gray-600 mb-2 line-clamp-2">{{ Str::limit($post->facebook_message ?: $post->instagram_message, 80) }}</p>
                            @if($post->feedback->where('is_client_feedback', true)->last())
                            <div class="p-2 bg-orange-50 border border-orange-200 rounded mb-2">
                                <p class="text-xs text-orange-800">
                                    <strong>Feedback:</strong> {{ Str::limit($post->feedback->where('is_client_feedback', true)->last()->feedback, 100) }}
                                </p>
                            </div>
                            @endif
                            <a href="{{ route('posts.edit', $post) }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">Edit Post →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Client Feedback -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Recent Client Feedback</h2>
                <p class="text-sm text-gray-600">From your posts</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentClientFeedback->take(5) as $feedback)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ substr($feedback->post->client->name, 0, 2) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-1">
                                <p class="font-semibold text-gray-900 text-sm">{{ $feedback->post->client->name }}</p>
                                @if($feedback->action === 'approve')
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Approved</span>
                                @elseif($feedback->action === 'request_changes')
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">Changes</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-600 mb-2">{{ $feedback->created_at->diffForHumans() }}</p>
                            <p class="text-sm text-gray-700">{{ Str::limit($feedback->feedback, 100) }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    <p class="text-gray-500 text-sm">No feedback yet</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Your Upcoming Posts -->
        @if($upcomingPosts->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 lg:col-span-2">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Your Upcoming Scheduled Posts</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($upcomingPosts as $post)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start gap-4">
                        @if($post->media->count() > 0)
                        @if($post->media->first()->isImage())
                        <img src="{{ $post->media->first()->url }}" alt="Post" class="w-20 h-20 rounded-lg object-cover flex-shrink-0">
                        @else
                        <video src="{{ $post->media->first()->url }}" class="w-20 h-20 rounded-lg object-cover flex-shrink-0" muted playsinline></video>
                        @endif
                        @else
                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $post->client->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ Str::limit($post->facebook_message ?: $post->instagram_message, 120) }}</p>
                                </div>
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Approved</span>
                            </div>
                            <div class="flex items-center gap-4 text-sm">
                                <span class="flex items-center text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $post->scheduled_at->format('M d, Y g:i A') }}
                                </span>
                                <div class="flex gap-2">
                                    @foreach($post->platforms as $platform)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded capitalize">{{ $platform }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Your Responsibilities Note -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex gap-4">
            <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="font-bold text-blue-900 mb-2">Your Responsibilities</h3>
                <ul class="space-y-1 text-sm text-blue-800">
                    <li>✓ Create and edit posts for clients</li>
                    <li>✓ Implement client feedback and requested changes</li>
                    <li>✓ Submit posts for client approval</li>
                    <li>✓ View client information (editing restricted to admins)</li>
                    <li>✗ Cannot delete posts (admin-only permission)</li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
