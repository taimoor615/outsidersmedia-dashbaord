<!DOCTYPE html>
<html lang="en" x-data="{
    view: 'feed',
    filterPosts: 'all',
    feedbackModal: false,
    noteModal: false,
    editModal: false,
    dateModal: false,
    lightboxSrc: null,
    viewFeedbackId: null,
    calMonth: new Date().getMonth(),
    calYear: new Date().getFullYear(),
    notesEditId: null, notesEditText: ''
}" x-effect="if(view==='calendar'){ $nextTick(function(){ window.renderCalendar(calMonth, calYear); }) }"
x-init="$watch('calMonth', function(v){ if(view==='calendar') $nextTick(function(){ window.renderCalendar(v, calYear); }) }); $watch('calYear', function(v){ if(view==='calendar') $nextTick(function(){ window.renderCalendar(calMonth, v); }) })">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg" />
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png" />
    <link rel="manifest" href="/images/site.webmanifest" />
    <title>{{ $client->name }} - Content Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- ───── HEADER ───── -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-[600px] mx-auto px-4 py-2 flex items-center justify-between">
            <!-- Logo -->
            <img src="{{ asset('images/logo-img.png') }}" alt="Outsidersmedia" style="max-height:70px;">
            <!-- Client social networks -->
            @if($client->networks && count($client->networks) > 0)
            <div class="flex items-center gap-2 flex-wrap">
                @foreach($client->networks as $network)
                @php
                    $netLower = strtolower($network);
                    $icons = [
                        'facebook'       => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>',
                        'instagram'      => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>',
                        'tiktok'         => '<path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>',
                        'youtube'        => '<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>',
                        'google business'=> '<path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/>',
                    ];
                    $colors = ['facebook'=>'#1877F2','instagram'=>'#E1306C','tiktok'=>'#000000','youtube'=>'#FF0000','google business'=>'#4285F4'];
                    $icon = $icons[$netLower] ?? null;
                    $color = $colors[$netLower] ?? '#6b7280';
                @endphp
                @if($icon)
                @php $netLink = $client->network_links[$network] ?? null; @endphp
                @if($netLink)
                <a href="{{ $netLink }}" target="_blank" title="{{ $network }}" style="color:{{ $color }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                </a>
                @else
                <span title="{{ $network }}" style="color:{{ $color }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                </span>
                @endif
                @else
                <span class="text-xs font-semibold text-gray-500 capitalize">{{ $network }}</span>
                @endif
                @endforeach
            </div>
            @endif
        </div>
    </header>

    <div class="max-w-[600px] mx-auto px-4 pt-8 pb-5">

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center gap-2 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- ───── TOP BAR (replaces old gradient banner) ───── -->
        <div class="mb-5 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $client->name }}</h1>
                <p class="text-xs text-gray-500 mt-0.5">Review and approve your content</p>
            </div>
            <div class="flex gap-1.5">
                <!-- Feed -->
                <button
                    @click="view = 'feed'; notesEditId = null"
                    class="cursor-pointer flex items-center gap-1 px-3 py-2 text-sm font-semibold rounded-xl border transition"
                    :style="view==='feed' ? 'background:#CD571B;color:#fff;border-color:#CD571B' : 'background:#fff;color:#374151;border-color:#e5e7eb'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Feed
                </button>
                <!-- Calendar -->
                <button
                    @click="view = 'calendar'; notesEditId = null"
                    class="cursor-pointer flex items-center gap-1 px-3 py-2 text-sm font-semibold rounded-xl border transition"
                    :style="view==='calendar' ? 'background:#CD571B;color:#fff;border-color:#CD571B' : 'background:#fff;color:#374151;border-color:#e5e7eb'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Calendar
                </button>
                <!-- Notes -->
                <button
                    @click="view = 'notes'; notesEditId = null"
                    class="cursor-pointer flex items-center gap-1 px-3 py-2 text-sm font-semibold rounded-xl border transition"
                    :style="view==='notes' ? 'background:#3b82f6;color:#fff;border-color:#3b82f6' : 'background:#fff;color:#374151;border-color:#e5e7eb'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    Notes
                    @if($notes->count() > 0)
                    <span class="ml-0.5 px-1.5 py-0.5 text-xs font-bold rounded-full" :style="view==='notes' ? 'background:rgba(255,255,255,0.25);color:#fff' : 'background:#e5e7eb;color:#374151'">{{ $notes->count() }}</span>
                    @endif
                </button>
            </div>
        </div>

        <!-- ───── FEED VIEW ───── -->
        <div x-show="view === 'feed'" x-cloak>

            <!-- Filter tabs -->
            <div class="mb-4 flex gap-2 overflow-x-auto pb-1">
                <button @click="filterPosts = 'all'"
                    class="cursor-pointer px-3 py-1.5 rounded-lg text-sm font-semibold whitespace-nowrap border transition"
                    :style="filterPosts==='all' ? 'background:#CD571B;color:#fff;border-color:#CD571B' : 'background:#fff;color:#374151;border-color:#e5e7eb'">
                    All ({{ $posts->count() }})
                </button>
                <button @click="filterPosts = 'pending'"
                    class="cursor-pointer px-3 py-1.5 rounded-lg text-sm font-semibold whitespace-nowrap border transition"
                    :style="filterPosts==='pending' ? 'background:#D97706;color:#fff;border-color:#D97706' : 'background:#fff;color:#374151;border-color:#e5e7eb'">
                    Pending ({{ $posts->where('status', 'pending_client')->count() }})
                </button>
                <button @click="filterPosts = 'approved'"
                    class="cursor-pointer px-3 py-1.5 rounded-lg text-sm font-semibold whitespace-nowrap border transition"
                    :style="filterPosts==='approved' ? 'background:#059669;color:#fff;border-color:#059669' : 'background:#fff;color:#374151;border-color:#e5e7eb'">
                    Approved ({{ $posts->whereIn('status', ['approved','scheduled'])->count() }})
                </button>
            </div>

            <!-- Posts Feed -->
            <div class="space-y-5">
                @forelse($posts as $post)
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden"
                    x-show="filterPosts === 'all' ||
                            (filterPosts === 'pending' && '{{ $post->status }}' === 'pending_client') ||
                            (filterPosts === 'approved' && ('{{ $post->status }}' === 'approved' || '{{ $post->status }}' === 'scheduled'))"
                    x-cloak
                >
                    <!-- Image -->
                    @if($post->media->count() > 0)
                    <div class="w-full bg-gray-100 overflow-hidden">
                        @if($post->post_type === 'carousel')
                        {{-- Carousel: 4:5 portrait (1080×1350) --}}
                        <div class="swiper miniCarousel-{{ $post->id }}" style="aspect-ratio:4/5;">
                            <div class="swiper-wrapper h-full">
                                @foreach($post->media as $media)
                                <div class="swiper-slide" style="height:100%;">
                                    <img src="{{ $media->url }}" alt="Post" class="w-full h-full object-cover block cursor-zoom-in" @click="lightboxSrc = '{{ $media->url }}'">
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        @elseif($post->post_type === 'video')
                        {{-- Video: 9:16 portrait (1080×1920) --}}
                        <div class="mx-auto" style="aspect-ratio:9/16;max-width:320px;">
                            <video controls class="w-full h-full object-contain bg-black block">
                                <source src="{{ $post->media->first()->url }}" type="{{ $post->media->first()->mime_type }}">
                            </video>
                        </div>
                        @elseif($post->media->first()->isImage())
                        {{-- Standard image: 1:1 square (1080×1080) --}}
                        <div style="aspect-ratio:1/1;">
                            <img src="{{ $post->media->first()->url }}" alt="Post" class="w-full h-full object-cover block cursor-zoom-in" @click="lightboxSrc = '{{ $post->media->first()->url }}'">
                        </div>
                        @else
                        {{-- Fallback video --}}
                        <div class="mx-auto" style="aspect-ratio:9/16;max-width:320px;">
                            <video controls class="w-full h-full object-contain bg-black block">
                                <source src="{{ $post->media->first()->url }}" type="{{ $post->media->first()->mime_type }}">
                            </video>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Card body: two columns -->
                    <div class="p-4 flex gap-4">

                        <!-- LEFT: content -->
                        <div class="flex-1 min-w-0">
                            <!-- Status + type -->
                            <div class="flex flex-wrap gap-1.5 mb-2">
                                @if($post->status === 'pending_client')
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full text-white" style="background:#CD571B;">Pending Review</span>
                                @elseif($post->status === 'changes_requested')
                                <span class="px-2 py-0.5 bg-orange-500 text-white text-xs font-bold rounded-full">Changes Requested</span>
                                @elseif(in_array($post->status, ['approved','scheduled']))
                                <span class="px-2 py-0.5 bg-green-500 text-white text-xs font-bold rounded-full">Approved</span>
                                @elseif($post->status === 'published')
                                <span class="px-2 py-0.5 bg-purple-500 text-white text-xs font-bold rounded-full">Published</span>
                                @else
                                <span class="px-2 py-0.5 bg-gray-400 text-white text-xs font-bold rounded-full">Draft</span>
                                @endif
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded capitalize">{{ str_replace('_',' ',$post->post_type) }}</span>
                            </div>

                            <!-- Caption: first platform shown, others behind toggle -->
                            @php
                                $pNetIcons = [
                                    'facebook'  => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>',
                                    'instagram' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>',
                                    'tiktok'    => '<path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>',
                                    'youtube'   => '<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>',
                                ];
                                $pNetColors = ['facebook'=>'#1877F2','instagram'=>'#E1306C','tiktok'=>'#000000','youtube'=>'#FF0000'];
                                $platforms = collect([
                                    ['key' => 'facebook',  'msg' => $post->facebook_message],
                                    ['key' => 'instagram', 'msg' => $post->instagram_message],
                                    ['key' => 'tiktok',    'msg' => $post->tiktok_message],
                                    ['key' => 'youtube',   'msg' => $post->youtube_message],
                                ])->filter(fn($p) => !empty($p['msg']))->values();
                            @endphp
                            @if($platforms->count() > 0)
                            <div class="mb-2">
                                <span class="inline-flex items-center gap-1 mb-0.5">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" style="color:{{ $pNetColors[$platforms[0]['key']] ?? '#6b7280' }}">{!! $pNetIcons[$platforms[0]['key']] ?? '' !!}</svg>
                                    <span class="text-xs font-semibold capitalize" style="color:{{ $pNetColors[$platforms[0]['key']] ?? '#6b7280' }}">{{ ucfirst($platforms[0]['key']) }}</span>
                                </span>
                                <p class="text-sm text-gray-800 leading-relaxed">{{ Str::limit($platforms[0]['msg'], 160) }}</p>
                                @if($platforms->count() > 1)
                                <div x-data="{ more: false }">
                                    <button @click="more = !more" class="text-xs mt-1 underline cursor-pointer" style="color:#CD571B;">
                                        <span x-show="!more">+ {{ $platforms->count() - 1 }} more platform{{ $platforms->count() - 1 > 1 ? 's' : '' }}</span>
                                        <span x-show="more" x-cloak>Hide platforms</span>
                                    </button>
                                    <div x-show="more" x-cloak class="mt-1 space-y-1">
                                        @foreach($platforms->slice(1) as $pm)
                                        <div>
                                            <span class="inline-flex items-center gap-1 mb-0.5">
                                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" style="color:{{ $pNetColors[$pm['key']] ?? '#6b7280' }}">{!! $pNetIcons[$pm['key']] ?? '' !!}</svg>
                                                <span class="text-xs font-semibold capitalize" style="color:{{ $pNetColors[$pm['key']] ?? '#6b7280' }}">{{ ucfirst($pm['key']) }}</span>
                                            </span>
                                            <p class="text-sm text-gray-800 leading-relaxed">{{ Str::limit($pm['msg'], 160) }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Scheduled date -->
                            @if($post->scheduled_at)
                            <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $post->scheduled_at->format('M d, Y g:i A') }}
                            </p>
                            @endif

                            <!-- Feedback history toggle -->
                            @if($post->feedback->where('is_client_feedback', true)->count() > 0)
                            <button
                                @click="viewFeedbackId = (viewFeedbackId === {{ $post->id }} ? null : {{ $post->id }})"
                                class="mt-2 text-xs font-semibold underline cursor-pointer"
                                style="color:#CD571B;"
                            >
                                <span x-text="viewFeedbackId === {{ $post->id }} ? 'Hide feedback' : 'View feedback ({{ $post->feedback->where("is_client_feedback", true)->count() }})'"></span>
                            </button>
                            <div x-show="viewFeedbackId === {{ $post->id }}" x-cloak class="mt-2 space-y-2">
                                @foreach($post->feedback->where('is_client_feedback', true) as $fb)
                                <div class="p-2 rounded-lg text-xs border" style="background:#FEF3EC;border-color:#F5C4A0;">
                                    <p class="font-semibold" style="color:#CD571B;">{{ $fb->client_name }}</p>
                                    <p class="text-gray-700 mt-0.5">{{ $fb->feedback }}</p>
                                    <p class="text-gray-400 mt-0.5">{{ $fb->created_at->format('M d, g:i A') }}</p>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <!-- Suggest edits → opens modal -->
                            <button
                                @click="editModal = {{ $post->id }}"
                                class="mt-2 text-xs font-medium flex items-center gap-1 text-gray-500 hover:text-gray-700 cursor-pointer"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Suggest edits
                            </button>
                        </div>

                        <!-- RIGHT: action buttons — always visible -->
                        <div class="flex flex-col gap-2 flex-shrink-0 items-end">

                            <!-- Approve -->
                            @if($post->status === 'pending_client')
                            <form action="{{ route('client.approve', [$client->share_token, $post]) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-1 px-3 py-2 bg-green-600 text-white text-xs font-bold rounded-xl hover:bg-green-700 transition whitespace-nowrap cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Approve
                                </button>
                            </form>
                            @else
                            <span class="flex items-center gap-1 px-3 py-2 bg-gray-100 text-gray-400 text-xs font-bold rounded-xl whitespace-nowrap cursor-not-allowed">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                @if(in_array($post->status, ['approved','scheduled'])) Approved @else Approve @endif
                            </span>
                            @endif

                            <!-- Request Changes -->
                            @if($post->status === 'pending_client')
                            <button
                                @click="feedbackModal = {{ $post->id }}"
                                class="flex items-center gap-1 px-3 py-2 text-xs font-bold rounded-xl border transition whitespace-nowrap cursor-pointer"
                                style="border-color:#CD571B;color:#CD571B;"
                                onmouseover="this.style.background='#CD571B';this.style.color='#fff'"
                                onmouseout="this.style.background='';this.style.color='#CD571B'"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Changes
                            </button>
                            @else
                            <span class="flex items-center gap-1 px-3 py-2 bg-gray-100 text-gray-400 text-xs font-bold rounded-xl whitespace-nowrap cursor-not-allowed">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Changes
                            </span>
                            @endif

                            <!-- Set Date -->
                            <button
                                @click="dateModal = {{ $post->id }}"
                                class="flex items-center gap-1 px-3 py-2 text-xs font-bold rounded-xl border transition whitespace-nowrap cursor-pointer"
                                style="border-color:#3b82f6;color:#3b82f6;"
                                onmouseover="this.style.background='#3b82f6';this.style.color='#fff'"
                                onmouseout="this.style.background='';this.style.color='#3b82f6'"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $post->scheduled_at ? 'Change Date' : 'Set Date' }}
                            </button>

                            <!-- Feedback — always active -->
                            <button
                                @click="feedbackModal = -{{ $post->id }}"
                                class="flex items-center gap-1 px-3 py-2 text-xs font-bold rounded-xl transition whitespace-nowrap text-white cursor-pointer"
                                style="background:#6b7280;"
                                onmouseover="this.style.background='#4b5563'" onmouseout="this.style.background='#6b7280'"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                Feedback
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
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                <div class="mb-4">
                    <p class="text-xs font-semibold uppercase tracking-wider mb-0.5" style="color:#CD571B;">Content Calendar</p>
                    <h3 class="text-lg font-bold text-gray-900">{{ $client->name }}</h3>
                </div>
                <div class="flex items-center justify-between mb-5">
                    <button @click="if(calMonth===0){calMonth=11;calYear--}else{calMonth--}" class="p-2 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <h3 class="text-base font-bold text-gray-900" x-text="new Date(calYear, calMonth).toLocaleDateString('en-US', {month:'long', year:'numeric'})"></h3>
                    <button @click="if(calMonth===11){calMonth=0;calYear++}else{calMonth++}" class="p-2 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
                <div class="grid grid-cols-7 mb-1">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                    <div class="text-center text-xs font-semibold text-gray-500 py-1">{{ $day }}</div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 gap-1" id="calGrid"></div>
                <div id="calDayPosts" class="mt-5 space-y-2"></div>
            </div>
        </div>

        <!-- ───── NOTES VIEW ───── -->
        <div x-show="view === 'notes'" x-cloak>
            <!-- Header row -->
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-gray-900">Notes & Messages</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Messages between you and your account manager</p>
                </div>
                <button
                    @click="noteModal = true"
                    class="flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-white rounded-xl transition"
                    style="background:#CD571B;"
                    onmouseover="this.style.background='#b54c17'" onmouseout="this.style.background='#CD571B'"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Note
                </button>
            </div>

            @forelse($notes as $note)
            <div class="bg-white rounded-2xl border mb-3 overflow-hidden {{ $note->isFromClient() ? 'border-orange-200' : 'border-gray-200' }}">
                <!-- Note header -->
                <div class="flex items-center justify-between px-4 py-3 {{ $note->isFromClient() ? 'bg-orange-50' : 'bg-gray-50' }} border-b {{ $note->isFromClient() ? 'border-orange-100' : 'border-gray-100' }}">
                    <div class="flex items-center gap-2">
                        @if($note->isFromClient())
                        <span class="px-2 py-0.5 text-white text-xs font-bold rounded-full" style="background:#CD571B;">You</span>
                        @else
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Team</span>
                        <span class="text-xs text-gray-600 font-medium">{{ $note->author_name }}</span>
                        @endif
                    </div>
                    <span class="text-xs text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                </div>

                <!-- Note body -->
                <div class="p-4">
                    {{-- View mode --}}
                    <div x-show="notesEditId !== {{ $note->id }}">
                        <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">{{ $note->note }}</p>
                        @if($note->isFromClient())
                        <button
                            @click="notesEditId = {{ $note->id }}; notesEditText = @js($note->note); $nextTick(() => $refs['noteEdit{{ $note->id }}'] && $refs['noteEdit{{ $note->id }}'].focus())"
                            class="mt-3 inline-flex items-center gap-1 text-xs font-semibold hover:underline"
                            style="color:#CD571B;"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit note
                        </button>
                        @endif
                    </div>

                    {{-- Edit mode (client notes only) --}}
                    @if($note->isFromClient())
                    <div x-show="notesEditId === {{ $note->id }}" style="display:none;">
                        <form action="{{ route('client.note.update', [$client->share_token, $note]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <textarea
                                x-ref="noteEdit{{ $note->id }}"
                                name="note"
                                rows="4"
                                x-model="notesEditText"
                                class="w-full px-3 py-2.5 border-2 rounded-xl text-sm outline-none resize-none transition-colors"
                                style="border-color:#CD571B;"
                            ></textarea>
                            <div class="flex gap-2 mt-2">
                                <button type="submit" class="flex-1 py-2 text-white text-sm font-bold rounded-xl transition" style="background:#CD571B;" onmouseover="this.style.background='#b54c17'" onmouseout="this.style.background='#CD571B'">Save</button>
                                <button type="button" @click="notesEditId = null" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition">Cancel</button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center">
                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                <p class="text-sm font-medium text-gray-500">No notes yet</p>
                <p class="text-xs text-gray-400 mt-1">Click "Add Note" to send a message to your account manager.</p>
            </div>
            @endforelse
        </div>

    </div><!-- /container -->

    <!-- ───── MODALS ───── -->

    <!-- Feedback + Request Changes modal (shared) -->
    @foreach($posts as $post)
    <!-- "Changes" modal: feedbackModal === post.id -->
    <div
        x-show="feedbackModal === {{ $post->id }}"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display:none;"
        @click.self="feedbackModal = false"
    >
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <h3 class="text-lg font-bold mb-1">Request Changes</h3>
            <p class="text-sm text-gray-500 mb-4">Tell the team what you'd like changed.</p>
            <form action="{{ route('client.reject', [$client->share_token, $post]) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="request_changes">
                <input type="text" name="feedback_name" placeholder="Your name (optional)" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm mb-3 focus:ring-2 focus:border-transparent" style="--tw-ring-color:#CD571B;">
                <textarea name="feedback" rows="4" required placeholder="What changes would you like?" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent mb-4" style="--tw-ring-color:#CD571B;"></textarea>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 text-white py-3 rounded-xl font-semibold transition" style="background:#CD571B;" onmouseover="this.style.background='#b54c17'" onmouseout="this.style.background='#CD571B'">Send Request</button>
                    <button type="button" @click="feedbackModal = false" class="px-5 bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- "Feedback" modal: feedbackModal === -post.id -->
    <div
        x-show="feedbackModal === -{{ $post->id }}"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display:none;"
        @click.self="feedbackModal = false"
    >
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <h3 class="text-lg font-bold mb-1">Add Feedback</h3>
            <p class="text-sm text-gray-500 mb-4">Leave your thoughts on this post.</p>
            <form action="{{ route('client.feedback', $client->share_token) }}" method="POST">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <input type="text" name="feedback_name" placeholder="Your name (optional)" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm mb-3 focus:ring-2 focus:border-transparent" style="--tw-ring-color:#CD571B;">
                <textarea name="feedback" rows="4" required placeholder="Share your thoughts..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent mb-4" style="--tw-ring-color:#CD571B;"></textarea>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 text-white py-3 rounded-xl font-semibold transition" style="background:#CD571B;" onmouseover="this.style.background='#b54c17'" onmouseout="this.style.background='#CD571B'">Submit Feedback</button>
                    <button type="button" @click="feedbackModal = false" class="px-5 bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- "Set Date" modal: dateModal === post.id -->
    <div
        x-show="dateModal === {{ $post->id }}"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display:none;"
        @click.self="dateModal = false"
    >
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl overflow-hidden"
             x-data="{
                 selDate: '{{ $post->scheduled_at ? $post->scheduled_at->format('Y-m-d') : '' }}',
                 selTime: '{{ $post->scheduled_at ? $post->scheduled_at->format('H:i') : '' }}',
                 step: 'pick',
                 get preview() {
                     if (!this.selDate) return '';
                     const d = new Date(this.selDate + 'T' + (this.selTime || '00:00'));
                     return d.toLocaleDateString('en-US', { weekday:'long', year:'numeric', month:'long', day:'numeric' })
                         + (this.selTime ? ' · ' + d.toLocaleTimeString('en-US', { hour:'numeric', minute:'2-digit' }) : '');
                 }
             }">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">
                        {{ $post->scheduled_at ? 'Change Publish Date' : 'Suggest a Publish Date' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        @if($post->scheduled_at)
                            Currently set to {{ $post->scheduled_at->format('M d, Y g:i A') }}. You can suggest a new date.
                        @else
                            Let the team know when to publish this post.
                        @endif
                    </p>
                </div>
                <button type="button" @click="dateModal = false" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Pick step -->
            <div x-show="step === 'pick'" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Date *</label>
                    <input type="date" x-model="selDate" :min="new Date().toISOString().split('T')[0]"
                        class="w-full px-4 py-3 border-2 rounded-xl text-sm font-medium transition-colors outline-none"
                        :class="selDate ? 'border-blue-400 bg-blue-50 text-blue-900' : 'border-gray-200 text-gray-700'"
                    >
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        Time <span class="text-gray-400 font-normal normal-case">(optional)</span>
                    </label>
                    <input type="time" x-model="selTime"
                        class="w-full px-4 py-3 border-2 rounded-xl text-sm font-medium transition-colors outline-none"
                        :class="selTime ? 'border-blue-400 bg-blue-50 text-blue-900' : 'border-gray-200 text-gray-700'"
                    >
                </div>

                <!-- Preview pill -->
                <div x-show="selDate" class="flex items-center gap-2 px-4 py-3 bg-blue-50 rounded-xl border border-blue-200" style="display:none;">
                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm font-semibold text-blue-800" x-text="preview"></p>
                </div>

                <div class="flex gap-3 pt-1">
                    <button
                        type="button"
                        @click="if (selDate) step = 'confirm'"
                        class="flex-1 py-3 rounded-xl font-bold text-sm transition-all"
                        :class="selDate ? 'bg-blue-500 hover:bg-blue-600 text-white shadow-sm' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                    >
                        OK — Confirm
                    </button>
                    <button type="button" @click="dateModal = false" class="px-5 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">Cancel</button>
                </div>
            </div>

            <!-- Confirm step -->
            <div x-show="step === 'confirm'" class="p-6" style="display:none;">
                <div class="text-center mb-5">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">You're suggesting:</p>
                    <p class="text-base font-bold text-gray-900" x-text="preview"></p>
                </div>
                <form action="{{ route('client.suggest-date', [$client->share_token, $post]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="suggested_date" :value="selDate">
                    <input type="hidden" name="suggested_time" :value="selTime">
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-bold text-sm transition-colors shadow-sm">Send to Team</button>
                        <button type="button" @click="step = 'pick'" class="px-5 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- "Suggest edits" modal: editModal === post.id -->
    <div
        x-show="editModal === {{ $post->id }}"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display:none;"
        @click.self="editModal = false"
    >
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl" style="max-height:90vh;overflow-y:auto;">
            <div class="flex items-center justify-between mb-1">
                <h3 class="text-lg font-bold">Suggest Edits</h3>
                <button type="button" @click="editModal = false" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <p class="text-sm text-gray-500 mb-4">Suggest changes to the post content below.</p>
            <form action="{{ route('client.update-post', [$client->share_token, $post]) }}" method="POST">
                @csrf
                @if($post->facebook_message)
                <div class="mb-3">
                    <label class="text-xs font-semibold text-blue-600 mb-1 block">Facebook</label>
                    <textarea name="facebook_message" rows="3" class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent" style="--tw-ring-color:#CD571B;">{{ $post->facebook_message }}</textarea>
                </div>
                @endif
                @if($post->instagram_message)
                <div class="mb-3">
                    <label class="text-xs font-semibold text-pink-600 mb-1 block">Instagram</label>
                    <textarea name="instagram_message" rows="3" class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent" style="--tw-ring-color:#CD571B;">{{ $post->instagram_message }}</textarea>
                </div>
                @endif
                @if($post->tiktok_message)
                <div class="mb-3">
                    <label class="text-xs font-semibold text-gray-800 mb-1 block">TikTok</label>
                    <textarea name="tiktok_message" rows="2" class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl">{{ $post->tiktok_message }}</textarea>
                </div>
                @endif
                @if($post->youtube_message)
                <div class="mb-3">
                    <label class="text-xs font-semibold text-red-600 mb-1 block">YouTube</label>
                    <textarea name="youtube_message" rows="2" class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl">{{ $post->youtube_message }}</textarea>
                </div>
                @endif
                <div class="flex gap-3 mt-4">
                    <button type="submit" class="flex-1 text-white py-3 rounded-xl font-semibold transition text-sm" style="background:#CD571B;" onmouseover="this.style.background='#b54c17'" onmouseout="this.style.background='#CD571B'">Submit Edits</button>
                    <button type="button" @click="editModal = false" class="px-5 bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 text-sm">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Lightbox -->
    <div
        x-show="lightboxSrc"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click="lightboxSrc = null"
        @keydown.escape.window="lightboxSrc = null"
        class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center p-4 cursor-zoom-out"
        style="display:none;"
    >
        <button @click.stop="lightboxSrc = null"
            class="absolute top-4 right-4 w-10 h-10 bg-white/15 hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-colors z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <p class="absolute bottom-4 left-0 right-0 text-center text-white/50 text-xs">Click anywhere to close</p>
        <img :src="lightboxSrc" @click.stop class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl cursor-default">
    </div>

    <!-- Note Modal -->
    <div
        x-show="noteModal"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display:none;"
        @click.self="noteModal = false"
    >
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <h3 class="text-lg font-bold mb-1">Add a Note</h3>
            <p class="text-sm text-gray-500 mb-4">Leave a general message for your account manager.</p>
            <form action="{{ route('client.note', $client->share_token) }}" method="POST">
                @csrf
                <textarea name="note" rows="4" required placeholder="Type your message here..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent mb-4" style="--tw-ring-color:#CD571B;"></textarea>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 text-white py-3 rounded-xl font-semibold transition" style="background:#CD571B;" onmouseover="this.style.background='#b54c17'" onmouseout="this.style.background='#CD571B'">Send Note</button>
                    <button type="button" @click="noteModal = false" class="px-5 bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200">Cancel</button>
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
            const cls = Array.from(el.classList).find(c => c.startsWith('miniCarousel-'));
            if (cls) new Swiper('.' + cls, {
                loop: true,
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                pagination: { el: '.swiper-pagination', clickable: true },
            });
        });

        // Calendar
        const postDates = @json($posts->whereNotNull('scheduled_at')->map(function($p){ return ['date' => $p->scheduled_at->format('Y-m-d'), 'title' => $p->facebook_message ?: $p->instagram_message ?: 'Post', 'status' => $p->status]; })->values());

        window.renderCalendar = function(month, year) {
            const grid = document.getElementById('calGrid');
            if (!grid) return;
            grid.innerHTML = '';
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const today = new Date();

            for (let i = 0; i < firstDay; i++) grid.innerHTML += '<div></div>';

            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                const dayPosts = postDates.filter(p => p.date === dateStr);
                const isToday = today.getDate()===d && today.getMonth()===month && today.getFullYear()===year;

                const btn = document.createElement('button');
                btn.textContent = d;
                btn.style.cssText = 'width:100%;text-align:center;padding:8px 2px;font-size:13px;border-radius:8px;transition:all 0.15s;';
                if (dayPosts.length) {
                    btn.style.background = '#EC921A';
                    btn.style.color = '#fff';
                    btn.style.fontWeight = '700';
                } else {
                    btn.onmouseover = () => btn.style.background = '#FEF3EC';
                    btn.onmouseout  = () => btn.style.background = '';
                }
                if (isToday) { btn.style.outline = '2px solid #CD571B'; btn.style.outlineOffset = '1px'; }
                btn.addEventListener('click', () => {
                    const c = document.getElementById('calDayPosts');
                    if (!dayPosts.length) { c.innerHTML = '<p class="text-sm text-gray-400 text-center py-2">No posts on this day.</p>'; return; }
                    c.innerHTML = `<p class="text-xs font-semibold text-gray-500 mb-2">${dateStr}</p>` +
                        dayPosts.map(p => `<div class="p-3 border rounded-xl text-sm" style="background:#FEF3EC;border-color:#F5C4A0;">
                            <span class="font-medium text-gray-800">${p.title.substring(0,80)}${p.title.length>80?'...':''}</span>
                            <span class="ml-2 text-xs capitalize" style="color:#CD571B;">${p.status.replace(/_/g,' ')}</span>
                        </div>`).join('');
                });
                grid.appendChild(btn);
            }
        };
    });
    </script>

    <style>
    .swiper-button-next, .swiper-button-prev { color: white; background: rgba(0,0,0,0.4); width:32px;height:32px;border-radius:50%; }
    .swiper-button-next:after, .swiper-button-prev:after { font-size:14px; }
    .swiper-pagination-bullet { background:#CD571B; opacity:.6; }
    .swiper-pagination-bullet-active { opacity:1; }
    </style>

</body>
</html>
