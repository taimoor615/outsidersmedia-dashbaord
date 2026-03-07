@extends('layouts.admin')

@section('title', 'Analytics')
@section('page-title', 'Analytics')

@section('content')
<div class="space-y-8">

    <!-- Page header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Analytics</h1>
            <p class="mt-1 text-gray-600">Posts, clients, revisions, and performance at a glance</p>
        </div>
    </div>

    <!-- KPI cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Posts</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($totalPosts) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Published This Month</p>
                    <p class="mt-1 text-3xl font-bold text-green-600">{{ number_format($publishedThisMonth) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Revisions (Feedback)</p>
                    <p class="mt-1 text-3xl font-bold text-amber-600">{{ number_format($totalRevisions) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Active Clients</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ number_format($totalClients) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Posts per client -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50/80">
                <h2 class="text-lg font-bold text-gray-900">Posts per Client</h2>
                <p class="text-sm text-gray-500 mt-0.5">Total and published posts by client</p>
            </div>
            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Published</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan/mo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($postsPerClient as $row)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-3">
                                <a href="{{ route('clients.show', $row['client']) }}" class="font-medium text-indigo-600 hover:text-indigo-700">{{ $row['client']->name }}</a>
                            </td>
                            <td class="px-6 py-3 text-right font-semibold text-gray-900">{{ $row['total'] }}</td>
                            <td class="px-6 py-3 text-right text-green-600 font-medium">{{ $row['published'] }}</td>
                            <td class="px-6 py-3 text-right text-gray-500">{{ $row['plan_limit'] ?: '–' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No data yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Revisions & feedback breakdown -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50/80">
                <h2 class="text-lg font-bold text-gray-900">Revisions &amp; Feedback</h2>
                <p class="text-sm text-gray-500 mt-0.5">Feedback messages and posts needing changes</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                        <p class="text-sm font-medium text-amber-800">Total feedback messages</p>
                        <p class="text-2xl font-bold text-amber-700">{{ $totalRevisions }}</p>
                    </div>
                    <div class="p-4 bg-orange-50 rounded-xl border border-orange-100">
                        <p class="text-sm font-medium text-orange-800">Posts needing changes</p>
                        <p class="text-2xl font-bold text-orange-700">{{ $postsNeedingChanges }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs font-medium text-blue-700">From clients</p>
                        <p class="text-xl font-bold text-blue-800">{{ $clientFeedbackCount }}</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <p class="text-xs font-medium text-purple-700">From team</p>
                        <p class="text-xl font-bold text-purple-800">{{ $teamFeedbackCount }}</p>
                    </div>
                </div>
                @if(!empty($feedbackByAction))
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">By action</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($feedbackByAction as $action => $count)
                        <span class="px-3 py-1.5 bg-gray-100 rounded-lg text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $action ?? 'other')) }}: {{ $count }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Approval funnel & Post type & Platform usage -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50/80">
                <h2 class="text-lg font-bold text-gray-900">Approval Funnel</h2>
            </div>
            <div class="p-6 space-y-2">
                @foreach($funnel as $label => $count)
                <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                    <span class="font-bold text-gray-900">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50/80">
                <h2 class="text-lg font-bold text-gray-900">Post Type</h2>
            </div>
            <div class="p-6 space-y-2">
                @foreach($postsByType as $type => $count)
                <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                    <span class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $type) }}</span>
                    <span class="font-bold text-gray-900">{{ $count }}</span>
                </div>
                @endforeach
                @if(empty($postsByType))
                <p class="text-gray-500 text-sm py-4">No posts yet</p>
                @endif
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50/80">
                <h2 class="text-lg font-bold text-gray-900">Platform Usage</h2>
                <p class="text-sm text-gray-500 mt-0.5">Times selected across posts</p>
            </div>
            <div class="p-6 space-y-2 max-h-56 overflow-y-auto">
                @forelse($platformUsage as $p)
                <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                    <span class="text-sm font-medium text-gray-700">{{ $p['name'] }}</span>
                    <span class="font-bold text-indigo-600">{{ $p['count'] }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-sm py-4">No data yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Posts by month (created vs published) & Posts by team member -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50/80">
                <h2 class="text-lg font-bold text-gray-900">Posts by Month (Last 12)</h2>
                <p class="text-sm text-gray-500 mt-0.5">Created vs published</p>
            </div>
            <div class="overflow-x-auto max-h-80 overflow-y-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Month</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Published</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($last12Months as $row)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-3 font-medium text-gray-700">{{ $row['label'] }}</td>
                            <td class="px-6 py-3 text-right text-gray-900">{{ $row['created'] }}</td>
                            <td class="px-6 py-3 text-right text-green-600 font-medium">{{ $row['published'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50/80">
                <h2 class="text-lg font-bold text-gray-900">Posts by Team Member</h2>
                <p class="text-sm text-gray-500 mt-0.5">Who created the most posts</p>
            </div>
            <div class="p-6 space-y-2 max-h-80 overflow-y-auto">
                @forelse($postsByCreator as $row)
                <div class="flex justify-between items-center py-3 border-b border-gray-50 last:border-0">
                    <span class="font-medium text-gray-800">{{ $row['name'] }}</span>
                    <span class="font-bold text-indigo-600">{{ $row['total'] }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-sm py-4">No data yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Feedback over time (last 12 months) -->
    @if($feedbackByMonth->isNotEmpty())
    @php($maxFeedback = $feedbackByMonth->max('total') ?: 1)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50/80">
            <h2 class="text-lg font-bold text-gray-900">Feedback by Month</h2>
            <p class="text-sm text-gray-500 mt-0.5">Revisions/feedback volume over time</p>
        </div>
        <div class="p-6 overflow-x-auto">
            <div class="flex gap-3 items-end min-w-max" style="height: 140px;">
                @foreach($feedbackByMonth as $row)
                <div class="flex flex-col items-center gap-1">
                    <div class="w-10 flex flex-col justify-end rounded-t bg-amber-100" style="height: 100px;">
                        <div class="w-full bg-amber-500 rounded-t text-white text-xs font-bold flex items-center justify-center" style="height: {{ $row['total'] > 0 ? max(4, round(($row['total'] / $maxFeedback) * 100)) : 0 }}%;">{{ $row['total'] > 0 ? $row['total'] : '' }}</div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $row['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Recent feedback -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50/80 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Recent Feedback</h2>
                <p class="text-sm text-gray-500 mt-0.5">Latest 10 feedback messages</p>
            </div>
            <a href="{{ route('posts.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View all posts</a>
        </div>
        <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
            @forelse($recentFeedback as $fb)
            <div class="px-6 py-4 hover:bg-gray-50/50">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">{{ Str::limit($fb->feedback, 120) }}</p>
                        <p class="mt-1 text-xs text-gray-500">
                            <a href="{{ route('posts.show', $fb->post) }}" class="text-indigo-600 hover:text-indigo-700 font-medium">{{ $fb->post->client->name }}</a>
                            · {{ $fb->created_at->diffForHumans() }}
                            @if($fb->action)
                            · <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $fb->action)) }}</span>
                            @endif
                        </p>
                    </div>
                    @if($fb->is_client_feedback)
                    <span class="px-2 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full shrink-0">Client</span>
                    @else
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full shrink-0">Team</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-gray-500">No feedback yet</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
