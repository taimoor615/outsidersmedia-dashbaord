@extends('layouts.admin')

@section('title', 'Create Team Member')
@section('page-title', 'Team Member')

@section('content')

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Page Header -->
        <div class="mb-8">
            <a href="{{ route('admin.team.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Team Management
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Add New Team Member</h1>
            <p class="mt-2 text-gray-600">Send an invitation to a new team member</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form action="{{ route('admin.team.store') }}" method="POST">
                @csrf

                <!-- Name Field -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                        placeholder="John Doe"
                        required
                    >
                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                        placeholder="john@outsidersmedia.com"
                        required
                    >
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">An activation email will be sent to this address</p>
                </div>

                <!-- Timezone Field -->
                <div class="mb-8">
                    <label for="timezone" class="block text-sm font-semibold text-gray-700 mb-2">Timezone *</label>
                    <select
                        id="timezone"
                        name="timezone"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all @error('timezone') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select Timezone</option>
                        @foreach($timezones as $tz)
                        <option value="{{ $tz }}" {{ old('timezone') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                        @endforeach
                    </select>
                    @error('timezone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">What happens next?</p>
                            <ul class="list-disc list-inside space-y-1 text-blue-700">
                                <li>An activation email will be sent to the provided email address</li>
                                <li>The team member will set their own password</li>
                                <li>Once verified, they can log in and start working</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-4 rounded-xl font-semibold shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200"
                    >
                        Send Invitation
                    </button>
                    <a
                        href="{{ route('admin.team.index') }}"
                        class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 text-center"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
