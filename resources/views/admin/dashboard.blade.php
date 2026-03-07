@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-2xl shadow-xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="text-indigo-100 text-lg">Here's what's happening with your social media management today.</p>
            </div>
            <div class="hidden lg:block">
                <div class="w-24 h-24 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Total Clients -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                @if($stats['clients_growth'] > 0)
                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">+{{ $stats['clients_growth'] }}%</span>
                @elseif($stats['clients_growth'] < 0)
                <span class="text-xs font-semibold text-red-600 bg-red-100 px-2 py-1 rounded-full">{{ $stats['clients_growth'] }}%</span>
                @endif
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['total_clients'] }}</h3>
            <p class="text-sm text-gray-600">Total Clients</p>
            <p class="text-xs text-gray-500 mt-1">{{ $stats['active_clients'] }} active</p>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($stats['pending_approvals'] > 0)
                <span class="text-xs font-semibold text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">Urgent</span>
                @endif
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['pending_approvals'] }}</h3>
            <p class="text-sm text-gray-600">Pending Approvals</p>
            @if($stats['pending_approvals'] > 0)
            <a href="{{ route('posts.index', ['status' => 'pending_approval']) }}" class="text-xs text-yellow-600 hover:text-yellow-700 mt-1 inline-block">View all →</a>
            @endif
        </div>

        <!-- Scheduled Posts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-purple-600 bg-purple-100 px-2 py-1 rounded-full">This Week</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['scheduled_posts'] }}</h3>
            <p class="text-sm text-gray-600">Scheduled Posts</p>
            <a href="{{ route('calendar.index') }}" class="text-xs text-purple-600 hover:text-purple-700 mt-1 inline-block">View calendar →</a>
        </div>

        <!-- Total Posts Published -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($stats['posts_growth'] > 0)
                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">+{{ $stats['posts_growth'] }}%</span>
                @endif
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['published_posts'] }}</h3>
            <p class="text-sm text-gray-600">Posts Published</p>
            <p class="text-xs text-gray-500 mt-1">{{ $stats['total_posts_this_month'] }} this month</p>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">Analytics</h2>
            <a href="{{ route('admin.analytics.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View full analytics →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Posts by Client</h3>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @forelse($postsPerClient as $row)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-900 font-medium truncate mr-2">{{ $row['client_name'] }}</span>
                        <span class="text-indigo-600 font-bold">{{ $row['total'] }}</span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">No posts yet</p>
                    @endforelse
                </div>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Revisions &amp; Feedback</h3>
                <div class="space-y-2">
                    <div class="flex justify-between items-center p-3 bg-amber-50 rounded-lg">
                        <span class="text-gray-700">Total feedback messages</span>
                        <span class="font-bold text-amber-700">{{ $totalRevisions }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                        <span class="text-gray-700">Posts needing changes</span>
                        <span class="font-bold text-orange-700">{{ $postsWithChangesRequested }}</span>
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Posts by Month</h3>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @forelse($monthlyPosts as $row)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-700">{{ $row['label'] }}</span>
                        <span class="font-bold text-green-600">{{ $row['total'] }}</span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">No data yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column - Wider -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Recent Client Feedback -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Recent Client Feedback</h2>
                        <a href="{{ route('posts.index', ['status' => 'pending_approval']) }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All</a>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">

                    @forelse($recentFeedback as $feedback)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                {{ substr($feedback->post->client->name, 0, 2) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $feedback->post->client->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $feedback->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if($feedback->action === 'approve')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Approved</span>
                                    @elseif($feedback->action === 'request_changes')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Needs Changes</span>
                                    @elseif($feedback->action === 'reject')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Rejected</span>
                                    @endif
                                </div>
                                <p class="text-gray-700 text-sm">{{ Str::limit($feedback->feedback, 150) }}</p>
                                <a href="{{ route('posts.show', $feedback->post) }}" class="text-sm text-indigo-600 hover:text-indigo-700 mt-2 inline-block">View Post →</a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <p class="text-gray-500 text-sm">No client feedback yet</p>
                    </div>
                    @endforelse

                </div>
            </div>

            <!-- Upcoming Scheduled Posts -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Upcoming Scheduled Posts</h2>
                        <a href="{{ route('posts.index', ['status' => 'scheduled']) }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View Calendar</a>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">

                    @forelse($upcomingPosts as $post)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-4">
                            @if($post->media->count() > 0)
                            @if($post->media->first()->isImage())
                            <img src="{{ $post->media->first()->url }}" alt="Post preview" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                            @else
                            <video src="{{ $post->media->first()->url }}" class="w-16 h-16 rounded-lg object-cover flex-shrink-0" muted playsinline></video>
                            @endif
                            @else
                            <div class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ Str::limit($post->facebook_message ?: $post->instagram_message, 60) }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $post->client->name }} •
                                    @foreach($post->platforms as $platform)
                                        <span class="capitalize">{{ $platform }}{{ !$loop->last ? ', ' : '' }}</span>
                                    @endforeach
                                </p>
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="flex items-center text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $post->scheduled_at->format('M d, g:i A') }}
                                    </span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Approved</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500 text-sm">No upcoming scheduled posts</p>
                    </div>
                    @endforelse

                </div>
            </div>
        </div>

        <!-- Right Column - Narrower -->
        <div class="space-y-6">

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('clients.create') }}" class="flex items-center gap-3 p-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="font-medium">Add New Client</span>
                    </a>
                    <a href="{{ route('posts.create') }}" class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <span class="font-medium">Create Post</span>
                    </a>
                    <a href="{{ route('admin.team.create') }}" class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span class="font-medium">Invite Team Member</span>
                    </a>
                </div>
            </div>

            <!-- Recent Client Notes -->
            @if($recentNotes->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Recent Notes</h2>
                <div class="space-y-4">
                    @foreach($recentNotes->take(3) as $note)
                    <div class="border-l-4 border-indigo-500 pl-4 py-2">
                        <p class="text-sm text-gray-700 mb-1">{{ Str::limit($note->feedback, 80) }}</p>
                        <p class="text-xs text-gray-500">{{ $note->post->client->name }} • {{ $note->created_at->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Team Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Team Activity</h2>
                <div class="space-y-4">
                    @foreach($teamActivity->take(5) as $activity)
                    <div class="flex items-start gap-3">
                        @if($activity['user'])
                        <img src="{{ $activity['user']->profile_image_url }}" alt="{{ $activity['user']->name }}" class="w-8 h-8 rounded-full">
                        @else
                        <div class="w-8 h-8 bg-gray-200 rounded-full"></div>
                        @endif
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">
                                <span class="font-semibold">{{ $activity['user']?->name ?? 'Unknown' }}</span> {{ $activity['action'] }}
                                @if($activity['details'])
                                <span class="text-gray-600">{{ $activity['details'] }}</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500">{{ $activity['time']->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
