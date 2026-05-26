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

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $client->name }}</h1>
                    <p class="mt-1 sm:mt-2 text-gray-600 text-sm sm:text-base">{{ $client->email }}</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                    <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-3 sm:px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Client
                    </a>
                    <a href="{{ $client->share_url }}" target="_blank" class="inline-flex items-center px-3 sm:px-4 py-2 bg-[#CD571B] text-white rounded-lg hover:bg-[#b54c17] transition-colors text-sm">
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
                            <span class="text-sm font-medium {{ $client->needs_approval ? 'text-[#CD571B]' : 'text-gray-900' }}">
                                {{ $client->needs_approval ? 'Required' : 'Not Required' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Plan Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Current Plan</h3>
                    <p class="text-2xl font-bold capitalize mb-3" style="color:#CD571B;">{{ $client->plan_type }}</p>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Posts per month</span>
                            <span class="font-semibold text-gray-900">{{ $client->posts_per_month }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Networks</span>
                            <span class="font-semibold text-gray-900">{{ $client->networks ? count($client->networks) : 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Contact Information</h3>
                    <div class="space-y-3">
                        @if($client->website_url)
                        <a href="{{ $client->website_url }}" target="_blank" class="flex items-center gap-3 text-sm text-gray-700 hover:text-[#CD571B] transition-colors">
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

                <!-- Content Mix -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" x-data="{
                    open: false,
                    mix: {
                        static:   {{ $client->content_mix['static']   ?? 0 }},
                        carousel: {{ $client->content_mix['carousel'] ?? 0 }},
                        video:    {{ $client->content_mix['video']    ?? 0 }},
                        stories:  {{ $client->content_mix['stories']  ?? 0 }},
                    },
                    get total() { return this.mix.static + this.mix.carousel + this.mix.video + this.mix.stories; }
                }">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Content Mix</h3>
                        <button @click="open = true" class="text-xs font-medium text-[#CD571B] hover:underline">Edit</button>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Static Posts</span>
                            <span class="font-semibold text-gray-900" x-text="mix.static"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Carousels</span>
                            <span class="font-semibold text-gray-900" x-text="mix.carousel"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Video Posts</span>
                            <span class="font-semibold text-gray-900" x-text="mix.video"></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Stories</span>
                            <span class="font-semibold text-gray-900" x-text="mix.stories"></span>
                        </div>
                        <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-sm">
                            <span class="font-semibold text-gray-700">Total</span>
                            <span class="font-bold text-gray-900" x-text="total + ' / {{ $client->posts_per_month }}'"></span>
                        </div>
                    </div>

                    <!-- Edit Content Mix Modal -->
                    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
                        <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
                        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 z-10">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Edit Content Mix</h3>
                            <p class="text-xs text-gray-500 mb-5">Posts/month cap: <span class="font-semibold">{{ $client->posts_per_month }}</span></p>

                            <form action="{{ route('clients.content-mix.update', $client) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    @foreach([
                                        ['key' => 'static',   'label' => 'Static Posts'],
                                        ['key' => 'carousel', 'label' => 'Carousels'],
                                        ['key' => 'video',    'label' => 'Video Posts'],
                                        ['key' => 'stories',  'label' => 'Stories'],
                                    ] as $row)
                                    <div class="flex items-center justify-between gap-4">
                                        <label class="text-sm font-medium text-gray-700 flex-1">{{ $row['label'] }}</label>
                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                @click="if(mix.{{ $row['key'] }} > 0) mix.{{ $row['key'] }}--"
                                                class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 font-bold transition-colors">−</button>
                                            <input type="number" name="content_mix[{{ $row['key'] }}]"
                                                x-model.number="mix.{{ $row['key'] }}"
                                                min="0" max="99"
                                                class="w-14 text-center border border-gray-300 rounded-lg py-1.5 text-sm font-semibold focus:ring-2 focus:ring-[#CD571B] focus:border-transparent">
                                            <button type="button"
                                                @click="mix.{{ $row['key'] }}++"
                                                class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 font-bold transition-colors">+</button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-xs text-gray-500">
                                        Total: <span class="font-bold text-gray-900" x-text="total"></span>
                                        / {{ $client->posts_per_month }}
                                    </span>
                                    <div class="flex gap-2">
                                        <button type="button" @click="open = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-[#CD571B] text-white text-sm font-medium rounded-lg hover:bg-[#b54c17] transition-colors">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Social Networks -->
                @if($client->networks && count($client->networks) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Social Networks</h3>
                    <div class="space-y-3">
                        @foreach($client->networks as $network)
                        @php
                            $link = $client->network_links[$network] ?? null;
                            $netKey = strtolower($network);
                            $netColors  = ['facebook'=>'#1877F2','instagram'=>'#E1306C','tiktok'=>'#000','youtube'=>'#FF0000','google business'=>'#4285F4','linkedin'=>'#0A66C2','twitter/x'=>'#000'];
                            $netBg      = ['facebook'=>'#EBF3FD','instagram'=>'#FDE8F0','tiktok'=>'#F0F0F0','youtube'=>'#FFE8E8','google business'=>'#EAF1FD','linkedin'=>'#E7F0FA','twitter/x'=>'#F0F0F0'];
                            $netIcons = [
                                'facebook'       => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>',
                                'instagram'      => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>',
                                'tiktok'         => '<path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>',
                                'youtube'        => '<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>',
                                'linkedin'       => '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>',
                                'twitter/x'      => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.253 5.622 5.911-5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
                                'google business'=> '<path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/>',
                            ];
                            $iconPath  = $netIcons[$netKey] ?? null;
                            $iconColor = $netColors[$netKey] ?? '#6b7280';
                            $iconBg    = $netBg[$netKey]    ?? '#f3f4f6';
                        @endphp
                        <div class="flex items-center justify-between px-3 py-2.5 bg-gray-50 rounded-xl border border-gray-100 hover:border-gray-200 hover:bg-white transition-colors">
                            <div class="flex items-center gap-3">
                                @if($iconPath)
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:{{ $iconBg }};">
                                    <svg class="w-4 h-4" fill="{{ $iconColor }}" viewBox="0 0 24 24">{!! $iconPath !!}</svg>
                                </div>
                                @endif
                                <span class="text-sm font-semibold text-gray-800">{{ $network }}</span>
                            </div>
                            @if($link)
                            <a href="{{ $link }}" target="_blank" class="flex items-center gap-1 text-xs font-medium hover:underline truncate max-w-[160px]" style="color:#CD571B;" title="{{ $link }}">
                                {{ parse_url($link, PHP_URL_HOST) ?: $link }}
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            @else
                            <span class="text-xs text-gray-400 italic">No link added</span>
                            @endif
                        </div>
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

                @php
                /**
                 * Detect URLs in free-form text and wrap them in clickable <a> tags.
                 * Safe: e() escapes all HTML first, then we inject only our own <a> tags.
                 */
                function linkifyText(string $text): string {
                    $escaped = e($text);
                    return preg_replace(
                        '/(https?:\/\/[^\s<>"\']+)/i',
                        '<a href="$1" target="_blank" rel="noopener" class="text-[#CD571B] hover:underline break-all">$1</a>',
                        $escaped
                    );
                }
                @endphp

                <!-- Audience & Strategy -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Audience & Strategy</h3>

                    @if($client->target_audience)
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Target Audience</h4>
                        <p class="text-gray-700 whitespace-pre-wrap">{!! linkifyText($client->target_audience) !!}</p>
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
                            <span class="px-3 py-1 bg-orange-50 text-[#b54c17] text-sm rounded-lg border border-orange-200">{{ $type }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($client->preferred_cta)
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Preferred CTA</h4>
                        <p class="text-gray-700">{{ $client->preferred_cta }}</p>
                    </div>
                    @endif

                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Share Third-Party Sources</h4>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium {{ $client->share_third_party_content ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
                            @if($client->share_third_party_content)
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Yes
                            @else
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                No
                            @endif
                        </span>
                        @if($client->share_third_party_content && $client->preferred_sources)
                        <div class="mt-3">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Preferred Sources / Industry Blogs</h4>
                            <p class="text-gray-700 text-sm whitespace-pre-wrap">{!! linkifyText($client->preferred_sources) !!}</p>
                        </div>
                        @endif
                    </div>

                    @if($client->keywords)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Keywords & Hashtags</h4>
                        <p class="text-gray-700 whitespace-pre-wrap">{!! linkifyText($client->keywords) !!}</p>
                    </div>
                    @endif
                </div>

                <!-- Competitors -->
                @if($client->competitors)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Competitors</h3>
                    <div class="text-gray-700 text-sm whitespace-pre-wrap leading-relaxed">{!! linkifyText($client->competitors) !!}</div>
                </div>
                @endif

                <!-- Brand Assets -->
                @if($client->brand_assets_link)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Brand Assets</h3>
                    <div class="text-gray-700 text-sm whitespace-pre-wrap leading-relaxed">{!! linkifyText($client->brand_assets_link) !!}</div>
                </div>
                @endif

                <!-- Content to Avoid & Additional Notes -->
                @if($client->content_to_avoid || $client->additional_notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Guidance</h3>

                    @if($client->content_to_avoid)
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Content to Avoid</h4>
                        <p class="text-gray-700 whitespace-pre-wrap text-sm">{!! linkifyText($client->content_to_avoid) !!}</p>
                    </div>
                    @endif

                    @if($client->additional_notes)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Additional Notes</h4>
                        <p class="text-gray-700 whitespace-pre-wrap text-sm">{!! linkifyText($client->additional_notes) !!}</p>
                    </div>
                    @endif
                </div>
                @endif

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
                            class="px-4 py-2 bg-[#CD571B] text-white rounded-lg hover:bg-[#b54c17] transition-colors"
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
