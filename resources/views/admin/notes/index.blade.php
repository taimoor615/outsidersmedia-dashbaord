@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')

@section('title', 'Client Notes')
@section('page-title', 'Client Notes')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ editId: null, editText: '' }">

    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Client Notes</h1>
            <p class="mt-1 text-sm text-gray-500">Notes from clients and team members</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Add Note + Filter Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- Add Note Form -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Add a Note</h2>
            <form action="{{ route('client-notes.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <select name="client_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#CD571B] focus:border-transparent">
                        <option value="">Select a client…</option>
                        @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                    <textarea name="note" rows="3" required maxlength="2000"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#CD571B] focus:border-transparent resize-none"
                        placeholder="Write your note here…"></textarea>
                    @error('note')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#CD571B] text-white text-sm font-medium rounded-lg hover:bg-[#b54c17] transition-colors">
                    Add Note
                </button>
            </form>
        </div>

        <!-- Filter by Client -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Filter</h2>
            <form method="GET" action="{{ route('client-notes.index') }}" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <select name="client_id" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#CD571B] focus:border-transparent">
                        <option value="">All clients</option>
                        @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if(request('client_id'))
                <a href="{{ route('client-notes.index') }}" class="block text-xs text-[#CD571B] hover:underline">Clear filter</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Notes List -->
    @forelse($notes as $note)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-4">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">

                <!-- Author / client badge row -->
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    @if($note->isFromClient())
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full border border-blue-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Client
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-orange-50 text-[#CD571B] text-xs font-semibold rounded-full border border-orange-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Team
                    </span>
                    @endif
                    <span class="text-sm font-medium text-gray-900">{{ $note->author_name ?? $note->client->name }}</span>
                    <span class="text-gray-300">·</span>
                    <a href="{{ route('clients.show', $note->client) }}" class="text-xs text-gray-500 hover:text-[#CD571B]">{{ $note->client->name }}</a>
                    <span class="text-gray-300">·</span>
                    <span class="text-xs text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                </div>

                <!-- Note body / inline edit -->
                <div x-show="editId !== {{ $note->id }}">
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $note->note }}</p>
                </div>

                <form x-show="editId === {{ $note->id }}" action="{{ route('client-notes.update', $note) }}" method="POST" class="mt-2" style="display:none;">
                    @csrf
                    @method('PUT')
                    <textarea name="note" rows="3" x-model="editText" required maxlength="2000"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#CD571B] focus:border-transparent resize-none"></textarea>
                    <div class="flex gap-2 mt-2">
                        <button type="submit" class="px-3 py-1.5 bg-[#CD571B] text-white text-xs font-medium rounded-lg hover:bg-[#b54c17] transition-colors">Save</button>
                        <button type="button" @click="editId = null" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 flex-shrink-0">
                <button
                    x-show="editId !== {{ $note->id }}"
                    @click="editId = {{ $note->id }}; editText = {{ json_encode($note->note) }}"
                    class="p-1.5 text-gray-400 hover:text-[#CD571B] hover:bg-orange-50 rounded-lg transition-colors"
                    title="Edit note"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>
                @if(auth()->user()->isAdmin())
                <form action="{{ route('client-notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Delete this note?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete note">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
        </svg>
        <p class="text-gray-500 text-sm">No notes yet.</p>
    </div>
    @endforelse

    <!-- Pagination -->
    @if($notes->hasPages())
    <div class="mt-6">
        {{ $notes->links() }}
    </div>
    @endif

</div>
@endsection
