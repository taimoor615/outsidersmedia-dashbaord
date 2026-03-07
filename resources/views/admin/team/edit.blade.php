@extends('layouts.admin')

@section('title', 'Edit Team Member')
@section('page-title', 'Edit Team Member')

@section('content')

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <a href="{{ route('admin.team.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Team Management
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Team Member</h1>
            <p class="mt-2 text-gray-600">Update team member information</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form action="{{ route('admin.team.update', $team) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $team->name) }}"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                        required
                    >
                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $team->email) }}"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                        required
                    >
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="timezone" class="block text-sm font-semibold text-gray-700 mb-2">Timezone *</label>
                    <select
                        id="timezone"
                        name="timezone"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        required
                    >
                        @foreach($timezones as $tz)
                        <option value="{{ $tz }}" {{ old('timezone', $team->timezone) === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-8">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Account Status *</label>
                    <select
                        id="status"
                        name="status"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        required
                    >
                        <option value="active" {{ old('status', $team->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $team->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500">Inactive users cannot log in</p>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-4 rounded-xl font-semibold shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 transition-all"
                    >
                        Update Team Member
                    </button>
                    <a
                        href="{{ route('admin.team.index') }}"
                        class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-xl font-semibold hover:bg-gray-200 transition-all text-center"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
