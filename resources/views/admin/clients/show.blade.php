@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')

@section('title', 'View Client')
@section('page-title', 'View Client')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('clients.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Clients
            </a>

            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $client->name }}</h1>
                    <p class="mt-2 text-gray-600">{{ $client->email }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Client
                    </a>
                    <a href="{{ $client->share_url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Client View
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column - Quick Info -->
            <div class="space-y-6">

                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Account</span>
                            @if($client->status === 'active')
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                            @elseif($client->status === 'inactive')
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Inactive</span>
                            @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Suspended</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Approval</span>
                            <span class="text-sm font-medium {{ $client->needs_approval ? 'text-indigo-600' : 'text-gray-900' }}">
                                {{ $client->needs_approval ? 'Required' : 'Not Required' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Plan Card -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <h3 class="text-sm font-semibold uppercase tracking-wide mb-4 opacity-90">Current Plan</h3>
                    <p class="text-3xl font-bold mb-2 capitalize">{{ $client->plan_type }}</p>
                    <p class="text-lg opacity-90 mb-4">${{ $client->plan_details['price'] }}/month</p>
                    <div class="space-y-2 pt-4 border-t border-white/20">
                        <div class="flex items-center justify-between text-sm">
                            <span class="opacity-90">Posts per month</span>
                            <span class="font-semibold">{{ $client->posts_per_month }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="opacity-90">Networks</span>
                            <span class="font-semibold">{{ $client->networks ? count($client->networks) : 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Contact Information</h3>
                    <div class="space-y-3">
                        @if($client->website_url)
                        <a href="{{ $client->website_url }}" target="_blank" class="flex items-center gap-3 text-sm text-gray-700 hover:text-indigo-600 transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                            <span class="truncate">{{ parse_url($client->website_url, PHP_URL_HOST) }}</span>
                        </a>
                        @endif

                        @if($client->location)
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $client->location }}</span>
                        </div>
                        @endif

                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $client->timezone }}</span>
                        </div>
                    </div>
                </div>

                <!-- Social Networks -->
                @if($client->networks && count($client->networks) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Social Networks</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($client->networks as $network)
                        <span class="px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-medium rounded-lg border border-blue-200">
                            {{ $network }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column - Detailed Info -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Business Info -->
                @if($client->business_description || $client->unique_value)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Business Overview</h3>

                    @if($client->business_description)
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">What They Do</h4>
                        <p class="text-gray-700">{{ $client->business_description }}</p>
                    </div>
                    @endif

                    @if($client->unique_value)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Unique Value</h4>
                        <p class="text-gray-700">{{ $client->unique_value }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Audience & Strategy -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Audience & Strategy</h3>

                    @if($client->target_audience)
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Target Audience</h4>
                        <p class="text-gray-700">{{ $client->target_audience }}</p>
                    </div>
                    @endif

                    @if($client->social_goals && count($client->social_goals) > 0)
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Social Media Goals</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($client->social_goals as $goal)
                            <span class="px-3 py-1 bg-green-50 text-green-700 text-sm rounded-lg border border-green-200">{{ $goal }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Brand Identity -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Brand Identity</h3>

                    @if($client->brand_tone && count($client->brand_tone) > 0)
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Brand Tone</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($client->brand_tone as $tone)
                            <span class="px-3 py-1 bg-purple-50 text-purple-700 text-sm rounded-lg border border-purple-200">{{ $tone }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($client->content_types && count($client->content_types) > 0)
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Content Priorities</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($client->content_types as $type)
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-sm rounded-lg border border-indigo-200">{{ $type }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($client->preferred_cta)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Preferred CTA</h4>
                        <p class="text-gray-700">{{ $client->preferred_cta }}</p>
                    </div>
                    @endif
                </div>

                <!-- Posting Schedule -->
                @if($client->posting_days && count($client->posting_days) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Posting Schedule</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($client->posting_days as $day)
                        <span class="px-4 py-2 bg-gray-100 text-gray-900 font-medium rounded-lg">{{ $day }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Share Link -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Client Portal Link</h3>
                    <p class="text-sm text-gray-600 mb-4">Share this link with the client to view and approve content</p>
                    <div class="flex gap-2">
                        <input
                            type="text"
                            value="{{ $client->share_url }}"
                            readonly
                            class="flex-1 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm"
                            onclick="this.select()"
                        >
                        <button
                            onclick="copyToClipboard('{{ $client->share_url }}')"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Link copied to clipboard!');
            });
        }
    </script>
@endsection
