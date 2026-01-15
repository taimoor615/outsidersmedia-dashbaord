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
                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">+12%</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">24</h3>
            <p class="text-sm text-gray-600">Total Clients</p>
        </div>

        <!-- Pending Approvals -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">Urgent</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">5</h3>
            <p class="text-sm text-gray-600">Pending Approvals</p>
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
            <h3 class="text-2xl font-bold text-gray-900 mb-1">18</h3>
            <p class="text-sm text-gray-600">Scheduled Posts</p>
        </div>

        <!-- Total Posts Published -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">+8%</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">342</h3>
            <p class="text-sm text-gray-600">Posts Published</p>
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
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View All</a>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">

                    <!-- Feedback Item -->
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-4">
                            <img src="https://ui-avatars.com/api/?name=TechCorp&color=7F9CF5&background=EBF4FF" alt="Client" class="w-12 h-12 rounded-full">
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">TechCorp Solutions</h3>
                                        <p class="text-sm text-gray-500">2 hours ago</p>
                                    </div>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Approved</span>
                                </div>
                                <p class="text-gray-700 text-sm">"Love the new post about our product launch! The graphics are stunning. Can we schedule this for tomorrow at 9 AM?"</p>
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Item -->
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-4">
                            <img src="https://ui-avatars.com/api/?name=FitLife&color=F59E0B&background=FEF3C7" alt="Client" class="w-12 h-12 rounded-full">
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">FitLife Gym</h3>
                                        <p class="text-sm text-gray-500">5 hours ago</p>
                                    </div>
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Needs Changes</span>
                                </div>
                                <p class="text-gray-700 text-sm">"Great content! Could we change the CTA from 'Learn More' to 'Join Today'? Also, please use the updated logo."</p>
                            </div>
                        </div>
                    </div>

                    <!-- Feedback Item -->
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-4">
                            <img src="https://ui-avatars.com/api/?name=Gourmet&color=EF4444&background=FEE2E2" alt="Client" class="w-12 h-12 rounded-full">
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">Gourmet Bistro</h3>
                                        <p class="text-sm text-gray-500">Yesterday</p>
                                    </div>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Approved</span>
                                </div>
                                <p class="text-gray-700 text-sm">"Perfect! The food photography looks amazing. Ready to publish."</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Upcoming Scheduled Posts -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Upcoming Scheduled Posts</h2>
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View Calendar</a>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">

                    <!-- Post Item -->
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">New Product Launch Announcement</h3>
                                <p class="text-sm text-gray-600 mb-2">TechCorp Solutions • Facebook</p>
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="flex items-center text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Tomorrow, 9:00 AM
                                    </span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Approved</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Post Item -->
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-purple-600 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">Workout Wednesday Tips</h3>
                                <p class="text-sm text-gray-600 mb-2">FitLife Gym • Instagram</p>
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="flex items-center text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Wed, Jan 17, 6:00 AM
                                    </span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Approved</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Post Item -->
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-300 to-blue-500 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">Industry Insights Article</h3>
                                <p class="text-sm text-gray-600 mb-2">TechCorp Solutions • LinkedIn</p>
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="flex items-center text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Thu, Jan 18, 10:00 AM
                                    </span>
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Right Column - Narrower -->
        <div class="space-y-6">

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.clients.create') }}" class="flex items-center gap-3 p-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition-colors">
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Recent Notes</h2>
                <div class="space-y-4">
                    <div class="border-l-4 border-indigo-500 pl-4 py-2">
                        <p class="text-sm text-gray-700 mb-1">Client prefers posts in the morning between 8-10 AM</p>
                        <p class="text-xs text-gray-500">TechCorp • 2 days ago</p>
                    </div>
                    <div class="border-l-4 border-purple-500 pl-4 py-2">
                        <p class="text-sm text-gray-700 mb-1">Updated brand colors - use new palette from drive</p>
                        <p class="text-xs text-gray-500">FitLife • 3 days ago</p>
                    </div>
                    <div class="border-l-4 border-pink-500 pl-4 py-2">
                        <p class="text-sm text-gray-700 mb-1">Special promotion running next week - create 5 posts</p>
                        <p class="text-xs text-gray-500">Gourmet Bistro • 5 days ago</p>
                    </div>
                </div>
            </div>

            <!-- Team Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Team Activity</h2>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <img src="https://ui-avatars.com/api/?name=John+Doe&color=7F9CF5&background=EBF4FF" alt="User" class="w-8 h-8 rounded-full">
                        <div class="flex-1">
                            <p class="text-sm text-gray-900"><span class="font-semibold">John Doe</span> created a new post</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="https://ui-avatars.com/api/?name=Jane+Smith&color=F59E0B&background=FEF3C7" alt="User" class="w-8 h-8 rounded-full">
                        <div class="flex-1">
                            <p class="text-sm text-gray-900"><span class="font-semibold">Jane Smith</span> approved 3 posts</p>
                            <p class="text-xs text-gray-500">4 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <img src="https://ui-avatars.com/api/?name=Mike+Johnson&color=EF4444&background=FEE2E2" alt="User" class="w-8 h-8 rounded-full">
                        <div class="flex-1">
                            <p class="text-sm text-gray-900"><span class="font-semibold">Mike Johnson</span> added a new client</p>
                            <p class="text-xs text-gray-500">Yesterday</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
