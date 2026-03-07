@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')

@section('title', 'Create Post')
@section('page-title', 'Create Post')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="mb-8">
        <a href="{{ route('posts.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Posts
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Create New Post</h1>
        <p class="mt-2 text-gray-600">Create engaging social media content for your clients</p>
    </div>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" x-data="postForm({{ \Illuminate\Support\Js::from($clientNetworks) }})" class="space-y-8">
        @csrf

        <!-- Client Selection & Post Type -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Post Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Select Client *</label>
                    <select
                        name="client_id"
                        x-model="selectedClient"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('client_id') border-red-500 @enderror"
                    >
                        <option value="">Choose a client...</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Post Type *</label>
                    <select
                        name="post_type"
                        x-model="postType"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                        <option value="standard">Standard Post</option>
                        <option value="carousel">Carousel Post (4 Images)</option>
                        <option value="video">Video Post</option>
                    </select>
                </div>
            </div>

            <!-- Platform Warnings -->
            <div x-show="postType === 'standard'" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">✓ Standard posts are compatible with all platforms</p>
            </div>

            <div x-show="postType === 'carousel'" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">⚠️ Carousels are not compatible with YouTube, TikTok, X and Google Business Profile. Please select a different platform.</p>
            </div>

            <div x-show="postType === 'video'" class="mt-4 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                <p class="text-sm text-purple-800">✓ Video posts are compatible with all platforms</p>
            </div>
        </div>

        <!-- Platform Selection (only platforms selected for this client when creating) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Select Platforms *</h2>
            <p class="text-sm text-gray-500 mb-4">Only platforms chosen for this client are shown. Select a client first.</p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Facebook -->
                <label class="relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-blue-50" :class="platforms.includes('facebook') ? 'border-blue-500 bg-blue-50' : 'border-gray-200'" x-show="!selectedClient || (clientNetworks[selectedClient] || []).includes('facebook')">
                    <input type="checkbox" name="platforms[]" value="facebook" x-model="platforms" class="sr-only">
                    <svg class="w-10 h-10 mb-2" :class="platforms.includes('facebook') ? 'text-blue-600' : 'text-gray-400'" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <span class="text-sm font-semibold" :class="platforms.includes('facebook') ? 'text-blue-600' : 'text-gray-700'">Facebook</span>
                </label>

                <!-- Instagram -->
                <label class="relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-pink-50" :class="platforms.includes('instagram') ? 'border-pink-500 bg-pink-50' : 'border-gray-200'" x-show="!selectedClient || (clientNetworks[selectedClient] || []).includes('instagram')">
                    <input type="checkbox" name="platforms[]" value="instagram" x-model="platforms" class="sr-only">
                    <svg class="w-10 h-10 mb-2" :class="platforms.includes('instagram') ? 'text-pink-600' : 'text-gray-400'" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    <span class="text-sm font-semibold" :class="platforms.includes('instagram') ? 'text-pink-600' : 'text-gray-700'">Instagram</span>
                </label>

                <!-- LinkedIn -->
                {{-- <label class="relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-blue-50" :class="platforms.includes('linkedin') ? 'border-blue-600 bg-blue-50' : 'border-gray-200'">
                    <input type="checkbox" name="platforms[]" value="linkedin" x-model="platforms" class="sr-only">
                    <svg class="w-10 h-10 mb-2" :class="platforms.includes('linkedin') ? 'text-blue-700' : 'text-gray-400'" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    <span class="text-sm font-semibold" :class="platforms.includes('linkedin') ? 'text-blue-700' : 'text-gray-700'">LinkedIn</span>
                </label> --}}

                <!-- Twitter/X -->
                {{-- <label class="relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-gray-100" :class="platforms.includes('twitter') ? 'border-gray-800 bg-gray-100' : 'border-gray-200'" x-show="postType !== 'carousel'">
                    <input type="checkbox" name="platforms[]" value="twitter" x-model="platforms" class="sr-only">
                    <svg class="w-10 h-10 mb-2" :class="platforms.includes('twitter') ? 'text-gray-900' : 'text-gray-400'" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    <span class="text-sm font-semibold" :class="platforms.includes('twitter') ? 'text-gray-900' : 'text-gray-700'">X/Twitter</span>
                </label> --}}

                <!-- TikTok -->
                <label class="relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-gray-100" :class="platforms.includes('tiktok') ? 'border-gray-900 bg-gray-100' : 'border-gray-200'" x-show="(postType !== 'carousel') && (!selectedClient || (clientNetworks[selectedClient] || []).includes('tiktok'))">
                    <input type="checkbox" name="platforms[]" value="tiktok" x-model="platforms" class="sr-only">
                    <svg class="w-10 h-10 mb-2" :class="platforms.includes('tiktok') ? 'text-gray-900' : 'text-gray-400'" fill="currentColor" viewBox="0 0 24 24"><path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                    <span class="text-sm font-semibold" :class="platforms.includes('tiktok') ? 'text-gray-900' : 'text-gray-700'">TikTok</span>
                </label>

                <!-- YouTube -->
                <label class="relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-red-50" :class="platforms.includes('youtube') ? 'border-red-600 bg-red-50' : 'border-gray-200'" x-show="(postType !== 'carousel') && (!selectedClient || (clientNetworks[selectedClient] || []).includes('youtube'))">
                    <input type="checkbox" name="platforms[]" value="youtube" x-model="platforms" class="sr-only">
                    <svg class="w-10 h-10 mb-2" :class="platforms.includes('youtube') ? 'text-red-600' : 'text-gray-400'" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    <span class="text-sm font-semibold" :class="platforms.includes('youtube') ? 'text-red-600' : 'text-gray-700'">YouTube</span>
                </label>

                <!-- Google Business -->
                <label class="relative flex flex-col items-center p-4 border-2 rounded-xl cursor-pointer transition-all hover:bg-green-50" :class="platforms.includes('google') ? 'border-green-600 bg-green-50' : 'border-gray-200'" x-show="(postType !== 'carousel') && (!selectedClient || (clientNetworks[selectedClient] || []).includes('google'))">
                    <input type="checkbox" name="platforms[]" value="google" x-model="platforms" class="sr-only">
                    <svg class="w-10 h-10 mb-2" :class="platforms.includes('google') ? 'text-green-600' : 'text-gray-400'" fill="currentColor" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    <span class="text-sm font-semibold" :class="platforms.includes('google') ? 'text-green-600' : 'text-gray-700'">Google</span>
                </label>
            </div>

            @error('platforms')<p class="mt-4 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <!-- Media Upload -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Media Upload</h2>

            <!-- Standard Post -->
            <div x-show="postType === 'standard'">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Upload Image</label>
                <input
                    type="file"
                    name="media[]"
                    accept="image/*"
                    :disabled="postType !== 'standard'"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                >
            </div>

            <!-- Carousel Post -->
            <div x-show="postType === 'carousel'">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Upload 4 Images for Carousel</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="i in 4" :key="i">
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-purple-500 transition-colors">
                            <input
                                type="file"
                                name="media[]"
                                accept="image/*"
                                :disabled="postType !== 'carousel'"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                            >
                            <p class="mt-2 text-xs text-gray-500" x-text="'Image ' + i"></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Video Post -->
            <div x-show="postType === 'video'">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Upload Video</label>
                <input
                    type="file"
                    name="media[]"
                    accept="video/*"
                    :disabled="postType !== 'video'"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer"
                >
                <p class="mt-3 text-sm text-gray-500">Supported: MP4, MOV. Max 100MB</p>
            </div>

            <!-- Webpage URL -->
            <div x-show="postType !== 'video'" class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Add Webpage URL (Optional)</label>
                <input
                    type="url"
                    name="webpage_url"
                    placeholder="https://example.com"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                <p class="mt-2 text-sm text-gray-500">Link to your website or landing page</p>
            </div>
        </div>

        <!-- Platform Messages -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Platform-Specific Messages</h2>

            <div class="space-y-6">
                <!-- Facebook -->
                <div x-show="platforms.includes('facebook')">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Message for Facebook
                    </label>
                    <textarea
                        name="facebook_message"
                        rows="4"
                        placeholder="Write your Facebook post..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ></textarea>
                </div>

                <!-- Instagram -->
                <div x-show="platforms.includes('instagram')">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        Message for Instagram
                    </label>
                    <textarea
                        name="instagram_message"
                        rows="4"
                        placeholder="Write your Instagram caption..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                    ></textarea>
                </div>

                <!-- TikTok -->
                <!-- TikTok -->
                <div x-show="platforms.includes('tiktok')">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-5 h-5 text-gray-900" fill="currentColor" viewBox="0 0 24 24"><path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        Message for TikTok
                    </label>
                    <textarea
                        name="tiktok_message"
                        rows="4"
                        placeholder="Write your TikTok description..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                    ></textarea>
                </div>

                <!-- YouTube -->
                <div x-show="platforms.includes('youtube')">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        Message for YouTube Short
                    </label>
                    <textarea
                        name="youtube_message"
                        rows="4"
                        placeholder="Write your YouTube Short description..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent"
                    ></textarea>
                </div>
            </div>
        </div>

        <!-- Google Business Profile Section -->
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
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">End Date *</label>
                        <input
                            type="date"
                            name="offer_end_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <!-- Add Time Toggle -->
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" x-model="showOfferTime" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="text-sm font-medium text-gray-700">Add specific times</span>
                    </label>
                </div>

                <div x-show="showOfferTime" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                        <input
                            type="time"
                            name="offer_start_time"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                        <input
                            type="time"
                            name="offer_end_time"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <!-- Add More Details Toggle -->
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" x-model="showOfferDetails" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="text-sm font-medium text-gray-700">Add more details (Optional)</span>
                    </label>
                </div>

                <div x-show="showOfferDetails" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Coupon Code</label>
                        <input
                            type="text"
                            name="offer_code"
                            placeholder="e.g., SAVE20"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Offer Link</label>
                        <input
                            type="url"
                            name="offer_link"
                            placeholder="https://..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Terms and Conditions</label>
                        <textarea
                            name="offer_terms"
                            rows="3"
                            placeholder="Enter terms and conditions..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <div x-show="googlePostType === 'event'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date *</label>
                        <input
                            type="date"
                            name="event_start_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">End Date *</label>
                        <input
                            type="date"
                            name="event_end_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time *</label>
                        <input
                            type="time"
                            name="event_start_time"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">End Time *</label>
                        <input
                            type="time"
                            name="event_end_time"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                    </div>
                </div>
            </div>

            <!-- Button (for all Google post types) -->
            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Add Button (Optional)</label>
                    <select
                        name="google_button"
                        x-model="googleButton"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
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
                    <input
                        type="url"
                        name="google_button_link"
                        placeholder="https://..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                </div>
            </div>
        </div>

        <!-- Schedule Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Schedule Post</h2>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Schedule Date & Time</label>
                    <input
                        type="datetime-local"
                        name="scheduled_at"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                    <p class="mt-2 text-sm text-gray-500">Leave empty to save as draft. Post will be sent to client for approval before publishing.</p>
                </div>

                <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-xl">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        <div class="text-sm text-indigo-800">
                            <p class="font-semibold mb-1">Post Workflow:</p>
                            <ol class="list-decimal list-inside space-y-1">
                                <li>Post is created and saved</li>
                                <li>Sent to client for approval</li>
                                <li>Client can approve, request changes, or reject</li>
                                <li>Once approved, post will be published at scheduled time</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <button
                type="submit"
                class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 px-6 rounded-xl font-semibold shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 transition-all text-lg flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Create Post & Send for Approval
            </button>
            <a
                href="{{ route('posts.index') }}"
                class="px-8 py-4 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all text-lg"
            >
                Cancel
            </a>
        </div>

    </form>
</div>

<script>
function postForm(clientNetworks = {}) {
    return {
        clientNetworks: clientNetworks,
        postType: 'standard',
        selectedClient: '',
        platforms: [],
        googlePostType: 'whats_new',
        googleButton: 'none',
        showOfferTime: false,
        showOfferDetails: false,
        showEventDetails: false,

        filterPlatforms() {
            if (this.postType === 'carousel') {
                const allowed = ['facebook', 'instagram'];
                this.platforms = this.platforms.filter(p => allowed.includes(p));
            }
            if (this.selectedClient && this.clientNetworks[this.selectedClient]) {
                this.platforms = this.platforms.filter(p => this.clientNetworks[this.selectedClient].includes(p));
            }
        },

        init() {
            this.$watch('postType', () => this.filterPlatforms());
            this.$watch('selectedClient', () => this.filterPlatforms());
        }
    }
}
</script>
@endsection
