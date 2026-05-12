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
                        value="{{ $post->client?->name ?? 'Unknown Client' }}"
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
            <p class="text-sm text-gray-500 mb-4">Only platforms chosen for {{ $post->client?->name ?? 'this client' }} are shown.</p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($allowedPlatforms as $platform)
                <label class="relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all" :class="platforms.includes('{{ $platform }}') ? 'border-[#CD571B] bg-orange-50' : 'border-gray-200'">
                    <input
                        type="checkbox"
                        name="platforms[]"
                        value="{{ $platform }}"
                        x-model="platforms"
                        {{ in_array($platform, $post->platforms ?? []) ? 'checked' : '' }}
                        class="sr-only"
                    >
                    <span class="text-sm font-semibold capitalize" :class="platforms.includes('{{ $platform }}') ? 'text-[#b54c17]' : 'text-gray-700'">{{ $platform }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Existing Media -->
        @if($post->media->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Current Media</h2>
                <span class="text-sm text-gray-500">{{ $post->media->count() }} file(s) uploaded</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($post->media as $media)
                <div class="relative rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                    @if($media->isImage())
                    <img src="{{ $media->url }}" alt="Post media" class="w-full h-36 object-cover">
                    @else
                    <video src="{{ $media->url }}" class="w-full h-36 object-cover" muted playsinline></video>
                    @endif

                    <!-- Type badge -->
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-xs font-semibold {{ $media->isImage() ? 'bg-blue-500' : 'bg-purple-500' }} text-white shadow">
                        {{ $media->isImage() ? 'Image' : 'Video' }}
                    </span>

                    <!-- Always-visible Remove button -->
                    <button
                        type="button"
                        onclick="if(confirm('Remove this file from the post?')) { document.getElementById('delete-media-{{ $media->id }}').submit(); }"
                        class="absolute bottom-0 left-0 right-0 flex items-center justify-center gap-1.5 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold transition-colors"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Remove
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Add New Media -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-2">
                {{ $post->post_type === 'video' ? 'Replace / Add Video' : 'Add More Images' }}
                <span class="text-sm font-normal text-gray-500 ml-2">Optional</span>
            </h2>

            @if($post->post_type === 'video')
            <!-- Video specs banner -->
            <div class="mb-5 flex flex-wrap gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 text-xs font-medium rounded-lg border border-purple-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.362a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                    MP4 or MOV only
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 text-xs font-medium rounded-lg border border-purple-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    Max 100 MB
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 text-xs font-medium rounded-lg border border-purple-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                    1080×1920 (Reels/TikTok) · 1920×1080 (landscape)
                </span>
            </div>

            <div
                x-data="{
                    dragging: false,
                    files: [],
                    addFiles(list) {
                        const f = Array.from(list)[0];
                        if (!f) return;
                        const url = URL.createObjectURL(f);
                        this.files = [{ name: f.name, size: this.fmt(f.size), preview: url, id: Date.now() }];
                        this.sync();
                    },
                    remove() { this.files = []; this.sync(); },
                    fmt(b) { return b > 1048576 ? (b/1048576).toFixed(1)+' MB' : (b/1024).toFixed(0)+' KB'; },
                    sync() {
                        const dt = new DataTransfer();
                        if (this.files[0]) {
                            fetch(this.files[0].preview).then(r => r.blob()).then(b => {
                                dt.items.add(new File([b], this.files[0].name, { type: b.type }));
                                document.getElementById('video-input').files = dt.files;
                            });
                        } else {
                            document.getElementById('video-input').files = dt.files;
                        }
                    }
                }"
                @dragover.prevent="dragging = true"
                @dragleave="dragging = false"
                @drop.prevent="dragging = false; addFiles($event.dataTransfer.files)"
            >
                <!-- Drop Zone -->
                <div
                    x-show="files.length === 0"
                    @click="$refs.vInput.click()"
                    :class="dragging ? 'border-purple-500 bg-purple-50' : 'border-gray-300 hover:border-purple-400 hover:bg-gray-50'"
                    class="border-2 border-dashed rounded-xl p-10 text-center cursor-pointer transition-all"
                >
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.362a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                    </svg>
                    <p class="text-base font-semibold text-gray-700 mb-1">Drop your video here</p>
                    <p class="text-sm text-gray-500 mb-3">or <span class="text-purple-600 font-medium">click to browse</span></p>
                    <p class="text-xs text-gray-400">MP4 · MOV · Max 100 MB</p>
                    <input type="file" id="video-input" x-ref="vInput" name="media[]" accept="video/*" hidden @change="addFiles($event.target.files)">
                </div>

                <!-- Video Preview -->
                <div x-show="files.length > 0" class="space-y-3" style="display:none;">
                    <template x-for="f in files" :key="f.id">
                        <div class="flex items-center gap-4 p-4 bg-purple-50 border border-purple-200 rounded-xl">
                            <video :src="f.preview" class="w-24 h-16 object-cover rounded-lg flex-shrink-0" muted playsinline></video>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate" x-text="f.name"></p>
                                <p class="text-xs text-gray-500 mt-0.5" x-text="f.size"></p>
                            </div>
                            <button type="button" @click="remove()" class="flex items-center gap-1.5 px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition-colors flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Remove
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            @else
            <!-- Image specs banner -->
            @php
                $isCarousel = $post->post_type === 'carousel';
            @endphp
            <div class="mb-5 flex flex-wrap gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-[#b54c17] text-xs font-medium rounded-lg border border-orange-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    JPG · PNG · WebP
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-[#b54c17] text-xs font-medium rounded-lg border border-orange-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    Max 10 MB per image
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 text-[#b54c17] text-xs font-medium rounded-lg border border-orange-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                    {{ $isCarousel ? '1080 × 1350 px (portrait 4:5 recommended)' : '1080 × 1080 px (Facebook) · 1080 × 1350 px (Instagram)' }}
                </span>
                @if($isCarousel)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg border border-blue-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Carousel: select multiple images at once (up to 10)
                </span>
                @endif
            </div>

            <div
                x-data="{
                    dragging: false,
                    files: [],
                    addFiles(list) {
                        Array.from(list).forEach(f => {
                            if (!f.type.startsWith('image/')) return;
                            const reader = new FileReader();
                            reader.onload = e => {
                                this.files.push({ name: f.name, size: this.fmt(f.size), preview: e.target.result, id: Date.now() + Math.random(), file: f });
                                this.sync();
                            };
                            reader.readAsDataURL(f);
                        });
                    },
                    remove(id) {
                        this.files = this.files.filter(f => f.id !== id);
                        this.sync();
                    },
                    fmt(b) { return b > 1048576 ? (b/1048576).toFixed(1)+' MB' : (b/1024).toFixed(0)+' KB'; },
                    sync() {
                        const dt = new DataTransfer();
                        this.files.forEach(f => dt.items.add(f.file));
                        document.getElementById('image-input').files = dt.files;
                    }
                }"
                @dragover.prevent="dragging = true"
                @dragleave="dragging = false"
                @drop.prevent="dragging = false; addFiles($event.dataTransfer.files)"
            >
                <!-- Drop Zone -->
                <div
                    @click="$refs.imgInput.click()"
                    :class="dragging ? 'border-[#CD571B] bg-orange-50' : 'border-gray-300 hover:border-[#CD571B] hover:bg-orange-50/40'"
                    class="border-2 border-dashed rounded-xl p-10 text-center cursor-pointer transition-all"
                >
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    @if($isCarousel)
                    <p class="text-base font-semibold text-gray-700 mb-1">Drop images here or click to browse</p>
                    <p class="text-sm text-gray-500 mb-1">
                        To select <span class="font-semibold text-[#CD571B]">multiple images</span>:
                        hold <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs font-mono">Ctrl</kbd>
                        (Windows) or <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded text-xs font-mono">⌘ Cmd</kbd>
                        (Mac) while clicking each file
                    </p>
                    @else
                    <p class="text-base font-semibold text-gray-700 mb-1">Drop your image here</p>
                    <p class="text-sm text-gray-500 mb-3">or <span class="text-[#CD571B] font-medium">click to browse</span></p>
                    @endif
                    <p class="text-xs text-gray-400 mt-2">JPG · PNG · WebP · Max 10 MB each</p>
                    <input type="file" id="image-input" x-ref="imgInput" name="media[]" accept="image/*" {{ $isCarousel ? 'multiple' : '' }} hidden @change="addFiles($event.target.files)">
                </div>

                <!-- Image Previews -->
                <div x-show="files.length > 0" class="mt-4" style="display:none;">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-semibold text-gray-700">
                            <span x-text="files.length"></span> image(s) selected
                        </p>
                        @if($isCarousel)
                        <button type="button" @click="$refs.imgInput.click()" class="text-xs text-[#CD571B] hover:underline font-medium">+ Add more</button>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <template x-for="f in files" :key="f.id">
                            <div class="relative rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                                <div style="aspect-ratio:4/5;"><img :src="f.preview" class="w-full h-full object-cover"></div>
                                <div class="p-2">
                                    <p class="text-xs text-gray-600 truncate font-medium" x-text="f.name"></p>
                                    <p class="text-xs text-gray-400" x-text="f.size"></p>
                                </div>
                                <button
                                    type="button"
                                    @click="remove(f.id)"
                                    class="absolute top-2 right-2 w-6 h-6 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-full shadow transition-colors"
                                    title="Remove"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                    >{{ old('facebook_message', $post->facebook_message) }}</textarea>
                </div>

                <div x-show="platforms.includes('instagram')">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Instagram Message</label>
                    <textarea
                        name="instagram_message"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                    >{{ old('instagram_message', $post->instagram_message) }}</textarea>
                </div>

                <div x-show="platforms.includes('tiktok')">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">TikTok Message</label>
                    <textarea
                        name="tiktok_message"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                    >{{ old('tiktok_message', $post->tiktok_message) }}</textarea>
                </div>

                <div x-show="platforms.includes('youtube')">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">YouTube Short Message</label>
                    <textarea
                        name="youtube_message"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
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
                <div x-data="datePicker('scheduled_at', '{{ old('scheduled_at', $post->scheduled_at?->format('Y-m-d\TH:i')) }}')">
                    <div @click="open = !open" role="button" tabindex="0" @keydown.enter.prevent="open = !open"
                        class="w-full flex items-center gap-3 px-4 py-3 border-2 rounded-xl bg-white hover:bg-gray-50 transition-colors cursor-pointer select-none"
                        :class="open ? 'border-[#CD571B]' : 'border-gray-200 hover:border-[#CD571B]'">
                        <svg class="w-5 h-5 flex-shrink-0" :class="displayValue ? 'text-[#CD571B]' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="flex-1 text-sm" :class="displayValue ? 'text-gray-900 font-medium' : 'text-gray-400'" x-text="displayValue || 'No date set'"></span>
                        <span x-show="displayValue" @click.stop="clear()" class="text-gray-400 hover:text-red-500 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </span>
                    </div>
                    <div x-show="open" @click.outside="open = false" class="mt-2 bg-white border border-gray-200 rounded-2xl shadow-xl p-5" style="display:none;">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Select date & time</p>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Date</p>
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div class="relative">
                                <select x-model="selMonth" @change="applyDate()" class="w-full appearance-none px-2.5 py-2.5 pr-7 border-2 rounded-xl text-sm font-medium bg-white outline-none cursor-pointer transition-colors" :class="selMonth ? 'border-[#CD571B] bg-orange-50 text-gray-900' : 'border-gray-200 text-gray-400'">
                                    <option value="">Month</option>
                                    <template x-for="m in months" :key="m"><option :value="m" x-text="m.slice(0,3)"></option></template>
                                </select>
                                <svg class="w-3.5 h-3.5 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="relative">
                                <select x-model="selDay" @change="applyDate()" class="w-full appearance-none px-2.5 py-2.5 pr-7 border-2 rounded-xl text-sm font-medium bg-white outline-none cursor-pointer transition-colors" :class="selDay ? 'border-[#CD571B] bg-orange-50 text-gray-900' : 'border-gray-200 text-gray-400'">
                                    <option value="">Day</option>
                                    <template x-for="d in days" :key="d"><option :value="d" x-text="d"></option></template>
                                </select>
                                <svg class="w-3.5 h-3.5 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="relative">
                                <select x-model="selYear" @change="applyDate()" class="w-full appearance-none px-2.5 py-2.5 pr-7 border-2 rounded-xl text-sm font-medium bg-white outline-none cursor-pointer transition-colors" :class="selYear ? 'border-[#CD571B] bg-orange-50 text-gray-900' : 'border-gray-200 text-gray-400'">
                                    <option value="">Year</option>
                                    <template x-for="y in years" :key="y"><option :value="y" x-text="y"></option></template>
                                </select>
                                <svg class="w-3.5 h-3.5 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Time</p>
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            <div class="relative">
                                <select x-model="selHour" @change="applyTime()" class="w-full appearance-none px-2.5 py-2.5 pr-7 border-2 border-[#CD571B] bg-orange-50 rounded-xl text-sm font-semibold text-gray-900 outline-none cursor-pointer">
                                    <template x-for="h in ['1','2','3','4','5','6','7','8','9','10','11','12']" :key="h"><option :value="h" x-text="h"></option></template>
                                </select>
                                <svg class="w-3.5 h-3.5 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-[#CD571B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="relative">
                                <select x-model="selMin" @change="applyTime()" class="w-full appearance-none px-2.5 py-2.5 pr-7 border-2 border-[#CD571B] bg-orange-50 rounded-xl text-sm font-semibold text-gray-900 outline-none cursor-pointer">
                                    <template x-for="m in ['00','05','10','15','20','25','30','35','40','45','50','55']" :key="m"><option :value="m" x-text="m"></option></template>
                                </select>
                                <svg class="w-3.5 h-3.5 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-[#CD571B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="relative">
                                <select x-model="selAmpm" @change="applyTime()" class="w-full appearance-none px-2.5 py-2.5 pr-7 border-2 border-[#CD571B] bg-orange-50 rounded-xl text-sm font-semibold text-gray-900 outline-none cursor-pointer">
                                    <option>AM</option><option>PM</option>
                                </select>
                                <svg class="w-3.5 h-3.5 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-[#CD571B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <div x-show="selectedDate" class="mb-4 px-4 py-2.5 bg-orange-50 border border-orange-200 rounded-xl" style="display:none;">
                            <p class="text-xs text-gray-500">Selected</p>
                            <p class="text-sm font-bold text-gray-900" x-text="displayValue"></p>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="confirm()"
                                :disabled="!selectedDate"
                                class="flex-1 py-2.5 rounded-xl font-bold text-sm transition-all"
                                :class="selectedDate ? 'bg-[#CD571B] hover:bg-[#b54c17] text-white shadow-sm' : 'bg-gray-100 text-gray-400 cursor-not-allowed'">
                                OK — Confirm
                            </button>
                            <button type="button" @click="clear(); open = false" class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">Clear</button>
                        </div>
                    </div>
                    <input type="hidden" :name="fieldName" :value="hiddenValue">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <button
                type="submit"
                class="flex-1 btn-brand"
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
