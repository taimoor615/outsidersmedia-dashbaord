@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.team')

@section('title', 'Create Client')
@section('page-title', 'Create Client')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <a href="{{ route('clients.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Clients
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Add Your Client</h1>
            <p class="mt-2 text-gray-600">Tell us about your client to create amazing social media content</p>
        </div>

        <form action="{{ route('clients.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- CLIENT INFORMATION -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Client Information</h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">What is the client's name? *</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter company name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent @error('name') border-red-500 @enderror"
                            required
                        >
                        @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="client@example.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent @error('email') border-red-500 @enderror"
                            required
                        >
                        @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">What is their website URL? *</label>
                        <input
                            type="url"
                            name="website_url"
                            value="{{ old('website_url') }}"
                            placeholder="https://example.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Where is this client located? *</label>
                        <input
                            type="text"
                            name="location"
                            value="{{ old('location') }}"
                            placeholder="City, State/Province, Country"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">What does the client do? *</label>
                        <textarea
                            name="business_description"
                            rows="4"
                            placeholder="Brief overview of their product/service and industry"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >{{ old('business_description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">What makes this client stand out?</label>
                        <textarea
                            name="unique_value"
                            rows="3"
                            placeholder="Their unique value, secret sauce, or strengths"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >{{ old('unique_value') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- AUDIENCE & STRATEGY -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Audience & Strategy</h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Who is their target audience?</label>
                        <input
                            type="text"
                            name="target_audience"
                            value="{{ old('target_audience') }}"
                            placeholder="Examples: Millennial moms in the US, HR Directors in tech, etc."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Social media goals (Select all that apply)</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(['Build brand awareness', 'Drive website traffic', 'Generate leads', 'Build community', 'Showcase products/services', 'Educate audience', 'Other'] as $goal)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input
                                    type="checkbox"
                                    name="social_goals[]"
                                    value="{{ $goal }}"
                                    {{ in_array($goal, old('social_goals', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#CD571B] border-gray-300 rounded focus:ring-[#CD571B]"
                                >
                                <span class="ml-3 text-sm text-gray-700">{{ $goal }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- BRAND IDENTITY -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Brand Identity & Content Direction</h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Brand tone of voice * (Select up to 3)</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach(['Friendly', 'Professional', 'Fun/playful', 'Inspirational', 'Bold', 'Educational', 'Minimalist', 'Other'] as $tone)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input
                                    type="checkbox"
                                    name="brand_tone[]"
                                    value="{{ $tone }}"
                                    {{ in_array($tone, old('brand_tone', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#CD571B] border-gray-300 rounded focus:ring-[#CD571B]"
                                >
                                <span class="ml-3 text-sm text-gray-700">{{ $tone }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Content types to prioritize * (Select all that apply)</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(['Product/service promotion', 'Behind-the-scenes', 'Client testimonials', 'Industry tips & education', 'Company news/updates', 'Lifestyle/inspirational', 'Employee/team features', 'Other'] as $type)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <input
                                    type="checkbox"
                                    name="content_types[]"
                                    value="{{ $type }}"
                                    {{ in_array($type, old('content_types', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#CD571B] border-gray-300 rounded focus:ring-[#CD571B]"
                                >
                                <span class="ml-3 text-sm text-gray-700">{{ $type }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Content to avoid</label>
                        <textarea
                            name="content_to_avoid"
                            rows="3"
                            placeholder="Any topics or content types to avoid"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >{{ old('content_to_avoid') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred calls to action</label>
                        <input
                            type="text"
                            name="preferred_cta"
                            value="{{ old('preferred_cta') }}"
                            placeholder="e.g., Book a consult, Shop now, Learn more"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="flex items-center p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                            <input
                                type="checkbox"
                                name="share_third_party_content"
                                value="1"
                                {{ old('share_third_party_content') ? 'checked' : '' }}
                                class="w-4 h-4 text-[#CD571B] border-gray-300 rounded focus:ring-[#CD571B]"
                            >
                            <span class="ml-3 text-sm text-gray-700">Share information from third-party sources (articles & blogs) that audience might find helpful</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keywords for captions</label>
                        <textarea
                            name="keywords"
                            rows="2"
                            placeholder="Specific keywords or phrases to include"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >{{ old('keywords') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Competitors to research</label>
                        <textarea
                            name="competitors"
                            rows="2"
                            placeholder="List competitors we should research"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >{{ old('competitors') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- ASSETS & BRANDING -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Assets & Branding</h2>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Brand assets link (Google Drive/Dropbox)</label>
                    <input
                        type="url"
                        name="brand_assets_link"
                        value="{{ old('brand_assets_link') }}"
                        placeholder="https://drive.google.com/..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                    >
                    <p class="mt-2 text-sm text-gray-500">Share logos, color palette, brand guidelines, fonts, photos, testimonials, etc.</p>
                </div>
            </div>

            <!-- SCHEDULING & APPROVAL -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Content Scheduling & Approval</h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Client's timezone *</label>
                        <select
                            name="timezone"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                            required
                        >
                            <option value="">Select Timezone</option>
                            @foreach($timezones as $tz)
                            <option value="{{ $tz }}" {{ old('timezone', 'America/New_York') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-data="postingDays({{ json_encode(old('posting_days', [])) }}, {{ json_encode(old('posting_times', [])) }})">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Posting days &amp; times (Select days and set time windows)</label>
                        <div class="space-y-2">
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <label class="flex items-center gap-3 p-3 hover:bg-gray-50 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="posting_days[]"
                                        value="{{ $day }}"
                                        {{ in_array($day, old('posting_days', [])) ? 'checked' : '' }}
                                        x-model="selected"
                                        @change="toggle('{{ $day }}')"
                                        class="w-4 h-4 border-gray-300 rounded focus:ring-[#CD571B]"
                                        style="accent-color:#CD571B;"
                                    >
                                    <span class="font-medium text-sm text-gray-800">{{ $day }}</span>
                                </label>
                                <div x-show="days['{{ $day }}']" x-cloak class="px-4 pb-3 flex items-center gap-4 bg-orange-50 border-t border-orange-100">
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs font-semibold text-gray-600">Start</label>
                                        <input type="time" name="posting_times[{{ $day }}][start]"
                                            :value="times['{{ $day }}'] && times['{{ $day }}'].start ? times['{{ $day }}'].start : '09:00'"
                                            class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                            style="--tw-ring-color:#CD571B;">
                                    </div>
                                    <span class="text-gray-400 text-sm">—</span>
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs font-semibold text-gray-600">End</label>
                                        <input type="time" name="posting_times[{{ $day }}][end]"
                                            :value="times['{{ $day }}'] && times['{{ $day }}'].end ? times['{{ $day }}'].end : '17:00'"
                                            class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                            style="--tw-ring-color:#CD571B;">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @push('scripts')
                    <script>
                    function postingDays(selectedDays, savedTimes) {
                        const allDays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                        const daysState = {};
                        allDays.forEach(d => daysState[d] = selectedDays.includes(d));
                        return {
                            selected: selectedDays,
                            days: daysState,
                            times: savedTimes || {},
                            toggle(day) { this.days[day] = this.selected.includes(day); }
                        };
                    }
                    </script>
                    @endpush

                    <div>
                        <label class="flex items-center p-4 bg-orange-50 border border-orange-200 rounded-lg cursor-pointer">
                            <input
                                type="checkbox"
                                name="needs_approval"
                                value="1"
                                {{ old('needs_approval', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-[#CD571B] border-gray-300 rounded focus:ring-[#CD571B]"
                            >
                            <span class="ml-3 text-sm font-medium text-gray-900">Require content approval before posting</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Approval email addresses *</label>
                        <input
                            type="text"
                            name="approval_emails"
                            value="{{ old('approval_emails') }}"
                            placeholder="email1@example.com, email2@example.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >
                        <p class="mt-2 text-sm text-gray-500">Separate multiple emails with commas</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Additional notes</label>
                        <textarea
                            name="additional_notes"
                            rows="4"
                            placeholder="Any seasonal promotions, sensitive topics, services not to promote, partnership dos and don'ts, etc."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >{{ old('additional_notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- PLAN SELECTION -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Select Plan</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                    <!-- Starter Plan -->
                    <label class="relative flex flex-col p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-[#CD571B] transition-all group">
                        <input
                            type="radio"
                            name="plan_type"
                            value="starter"
                            {{ old('plan_type') === 'starter' ? 'checked' : '' }}
                            class="sr-only peer"
                            required
                        >
                        <div class="absolute top-4 right-4 w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-[#CD571B] peer-checked:bg-[#CD571B] flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Starter</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                8 posts per month
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Up to 2 networks
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Unlimited revisions
                            </li>
                        </ul>
                    </label>

                    <!-- Business Plan -->
                    <label class="relative flex flex-col p-6 border-2 border-[#CD571B] rounded-xl cursor-pointer hover:border-[#CD571B] transition-all group bg-orange-50">
                        <input
                            type="radio"
                            name="plan_type"
                            value="business"
                            {{ old('plan_type', 'business') === 'business' ? 'checked' : '' }}
                            class="sr-only peer"
                        >
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 px-3 py-1 bg-[#CD571B] text-white text-xs font-bold rounded-full">POPULAR</div>
                        <div class="absolute top-4 right-4 w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-[#CD571B] peer-checked:bg-[#CD571B] flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Business</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                12 posts per month
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Up to 4 networks
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Unlimited revisions
                            </li>
                        </ul>
                    </label>

                    <!-- Scale Plan -->
                    <label class="relative flex flex-col p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-[#CD571B] transition-all group">
                        <input
                            type="radio"
                            name="plan_type"
                            value="scale"
                            {{ old('plan_type') === 'scale' ? 'checked' : '' }}
                            class="sr-only peer"
                        >
                        <div class="absolute top-4 right-4 w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-[#CD571B] peer-checked:bg-[#CD571B] flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Scale</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                16 posts per month
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Up to 2 networks
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Unlimited revisions
                            </li>
                        </ul>
                    </label>
                </div>

                <!-- Network Selection -->
                <div x-data="{
                    selected: {{ json_encode(old('networks', [])) }},
                    toggle(net, checked) {
                        if (checked && !this.selected.includes(net)) { this.selected.push(net); }
                        else { this.selected = this.selected.filter(s => s !== net); }
                    }
                }">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Select Social Networks</label>
                    <div class="space-y-2">
                        @foreach(['Facebook', 'Instagram', 'TikTok', 'YouTube', 'Google Business'] as $network)
                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                            <label class="flex items-center gap-3 p-3 hover:bg-gray-50 cursor-pointer transition-colors">
                                <input
                                    type="checkbox"
                                    name="networks[]"
                                    value="{{ $network }}"
                                    {{ in_array($network, old('networks', [])) ? 'checked' : '' }}
                                    @change="toggle('{{ $network }}', $event.target.checked)"
                                    class="w-4 h-4 border-gray-300 rounded focus:ring-[#CD571B]"
                                    style="accent-color:#CD571B;"
                                >
                                <span class="font-medium text-sm text-gray-800">{{ $network }}</span>
                            </label>
                            <div x-show="selected.includes('{{ $network }}')" x-cloak class="px-4 pb-3 bg-orange-50 border-t border-orange-100">
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Profile URL</label>
                                <input
                                    type="url"
                                    name="network_links[{{ $network }}]"
                                    value="{{ old('network_links.'.$network) }}"
                                    placeholder="https://..."
                                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                                >
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <button
                    type="submit"
                    class="flex-1 btn-brand"
                >
                    Create Client
                </button>
                <a
                    href="{{ route('clients.index') }}"
                    class="flex-1 bg-gray-100 text-gray-700 py-4 px-6 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 text-center text-lg"
                >
                    Cancel
                </a>
            </div>

        </form>
    </div>
@endsection
