@extends('layouts.admin')

@section('title', 'Client')
@section('page-title', 'Client')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Clients</h1>
                    <p class="mt-2 text-gray-600">Manage your client accounts and subscriptions</p>
                </div>
                <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Client
                </a>
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

        <!-- Clients Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($clients as $client)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-200 overflow-hidden">

                <!-- Card Header -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $client->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $client->email }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            @if($client->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                {{ ucfirst($client->status) }}
                            </span>
                            @endif
                        </div>
                    </div>

                    @if($client->location)
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $client->location }}</span>
                    </div>
                    @endif

                    @if($client->website_url)
                    <a href="{{ $client->website_url }}" target="_blank" class="flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        Visit Website
                    </a>
                    @endif
                </div>

                <!-- Plan Info -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Plan</p>
                            <p class="text-sm font-bold text-gray-900 capitalize">{{ $client->plan_type }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Posts/Month</p>
                            <p class="text-sm font-bold text-gray-900">{{ $client->posts_per_month }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Networks</p>
                            <p class="text-sm font-bold text-gray-900">{{ $client->networks ? count($client->networks) : 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Social Networks -->
                @if($client->networks && count($client->networks) > 0)
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex flex-wrap gap-2">
                        @foreach($client->networks as $network)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $network }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="px-6 py-4 flex items-center justify-between gap-2">
                    <a href="{{ $client->share_url }}" target="_blank" class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Client View
                    </a>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.clients.show', $client) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="View Details">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <a href="{{ route('admin.clients.edit', $client) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this client?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
            @empty
            <div class="col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No clients yet</h3>
                    <p class="text-gray-500 mb-6">Start by adding your first client</p>
                    <a href="{{ route('admin.clients.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                        Add New Client
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($clients->hasPages())
        <div class="mt-8">
            {{ $clients->links() }}
        </div>
        @endif

    </div>
@endsection
