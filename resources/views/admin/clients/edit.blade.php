@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')

@section('title', 'Edit Client')
@section('page-title', 'Edit Client')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Client Details
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Client: {{ $client->name }}</h1>
            <p class="mt-2 text-gray-600">Update client information and preferences</p>
        </div>

        <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- CLIENT INFORMATION -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Client Information</h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Client Name *</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $client->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            required
                        >
                        @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $client->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            required
                        >
                        @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Website URL</label>
                            <input
                                type="url"
                                name="website_url"
                                value="{{ old('website_url', $client->website_url) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                            <input
                                type="text"
                                name="location"
                                value="{{ old('location', $client->location) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Business Description</label>
                        <textarea
                            name="business_description"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >{{ old('business_description', $client->business_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unique Value Proposition</label>
                        <textarea
                            name="unique_value"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >{{ old('unique_value', $client->unique_value) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Target Audience</label>
                        <input
                            type="text"
                            name="target_audience"
                            value="{{ old('target_audience', $client->target_audience) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>
                </div>
            </div>

            <!-- CONTENT STRATEGY -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Content Strategy</h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Social Media Goals</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(['Build brand awareness', 'Drive website traffic', 'Generate leads', 'Build community', 'Showcase products/services', 'Educate audience', 'Other'] as $goal)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="social_goals[]"
                                    value="{{ $goal }}"
                                    {{ in_array($goal, old('social_goals', $client->social_goals ?? [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                >
                                <span class="ml-3 text-sm text-gray-700">{{ $goal }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Brand Tone (Max 3)</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach(['Friendly', 'Professional', 'Fun/playful', 'Inspirational', 'Bold', 'Educational', 'Minimalist', 'Other'] as $tone)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="brand_tone[]"
                                    value="{{ $tone }}"
                                    {{ in_array($tone, old('brand_tone', $client->brand_tone ?? [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                >
                                <span class="ml-3 text-sm text-gray-700">{{ $tone }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Content Types</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(['Product/service promotion', 'Behind-the-scenes', 'Client testimonials', 'Industry tips & education', 'Company news/updates', 'Lifestyle/inspirational', 'Employee/team features', 'Other'] as $type)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="content_types[]"
                                    value="{{ $type }}"
                                    {{ in_array($type, old('content_types', $client->content_types ?? [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                >
                                <span class="ml-3 text-sm text-gray-700">{{ $type }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred CTA</label>
                        <input
                            type="text"
                            name="preferred_cta"
                            value="{{ old('preferred_cta', $client->preferred_cta) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="flex items-center p-4 bg-gray-50 rounded-lg cursor-pointer">
                            <input
                                type="checkbox"
                                name="share_third_party_content"
                                value="1"
                                {{ old('share_third_party_content', $client->share_third_party_content) ? 'checked' : '' }}
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <span class="ml-3 text-sm text-gray-700">Share third-party content</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SCHEDULING -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Scheduling & Approval</h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Timezone *</label>
                        <select
                            name="timezone"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            required
                        >
                            @foreach($timezones as $tz)
                            <option value="{{ $tz }}" {{ old('timezone', $client->timezone) === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Posting Days</label>
                        <div class="grid grid-cols-7 gap-2">
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <label class="flex flex-col items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer text-center">
                                <input
                                    type="checkbox"
                                    name="posting_days[]"
                                    value="{{ $day }}"
                                    {{ in_array($day, old('posting_days', $client->posting_days ?? [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mb-2"
                                >
                                <span class="text-xs text-gray-700">{{ substr($day, 0, 3) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center p-4 bg-indigo-50 border border-indigo-200 rounded-lg cursor-pointer">
                            <input
                                type="checkbox"
                                name="needs_approval"
                                value="1"
                                {{ old('needs_approval', $client->needs_approval) ? 'checked' : '' }}
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <span class="ml-3 text-sm font-medium text-gray-900">Require approval before posting</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Approval Emails</label>
                        <input
                            type="text"
                            name="approval_emails"
                            value="{{ old('approval_emails', $client->approval_emails) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>
                </div>
            </div>

            <!-- PLAN & STATUS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Plan & Status</h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Plan Type *</label>
                        <div class="grid grid-cols-3 gap-4">
                            @foreach(['starter' => ['posts' => 8, 'price' => 359], 'business' => ['posts' => 12, 'price' => 539], 'scale' => ['posts' => 16, 'price' => 659]] as $plan => $details)

                            <label class="relative cursor-pointer group">

                                <input
                                    type="radio"
                                    name="plan_type"
                                    value="{{ $plan }}"
                                    {{ old('plan_type', $client->plan_type) === $plan ? 'checked' : '' }}
                                    class="sr-only peer"
                                >

                                <div class="p-4 border-2 rounded-xl transition-all duration-200
                                            border-gray-200 hover:border-indigo-300
                                            peer-checked:border-indigo-600 peer-checked:bg-indigo-50">

                                    <div class="text-center">
                                        <p class="font-bold text-lg capitalize text-gray-700 peer-checked:text-indigo-900">{{ $plan }}</p>
                                        <p class="text-2xl font-bold text-gray-900">${{ $details['price'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $details['posts'] }} posts/mo</p>
                                    </div>

                                    <div class="hidden peer-checked:block absolute top-2 right-2 text-indigo-600">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Social Networks</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach(['Facebook', 'Instagram', 'LinkedIn', 'Twitter/X', 'TikTok', 'YouTube', 'Google Business'] as $network)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="networks[]"
                                    value="{{ $network }}"
                                    {{ in_array($network, old('networks', $client->networks ?? [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                >
                                <span class="ml-3 text-sm text-gray-700">{{ $network }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Account Status *</label>
                        <select
                            name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            required
                        >
                            <option value="active" {{ old('status', $client->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $client->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $client->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <button
                    type="submit"
                    class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 px-6 rounded-xl font-semibold shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 transition-all"
                >
                    Update Client
                </button>
                <a
                    href="{{ route('clients.show', $client) }}"
                    class="flex-1 bg-gray-100 text-gray-700 py-4 px-6 rounded-xl font-semibold hover:bg-gray-200 transition-all text-center"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
