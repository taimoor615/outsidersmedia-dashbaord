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
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Edit Client: {{ $client->name }}</h1>
            <p class="mt-1 sm:mt-2 text-gray-600 text-sm sm:text-base">Update client information and preferences</p>
        </div>

        <form action="{{ route('clients.update', $client) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- CLIENT INFORMATION -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Client Information</h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Client Name *</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $client->name) }}"
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
                            value="{{ old('email', $client->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent @error('email') border-red-500 @enderror"
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                            <input
                                type="text"
                                name="location"
                                value="{{ old('location', $client->location) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Business Description</label>
                        <textarea
                            name="business_description"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >{{ old('business_description', $client->business_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">What makes this client stand out from competitors? <span class="text-gray-400 font-normal">(Their unique value, secret sauce, or strengths)</span></label>
                        <textarea
                            name="unique_value"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >{{ old('unique_value', $client->unique_value) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Target Audience</label>
                        <textarea
                            name="target_audience"
                            rows="3"
                            placeholder="Examples: Millennial moms in the US, HR Directors in tech, etc."
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >{{ old('target_audience', $client->target_audience) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- CONTENT STRATEGY -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-8">
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
                                    class="w-4 h-4 text-[#CD571B] border-gray-300 rounded focus:ring-[#CD571B]"
                                >
                                <span class="ml-3 text-sm text-gray-700">{{ $goal }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Brand Tone (Max 3)</label>
                        <div id="brand-tone-group" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach(['Friendly', 'Professional', 'Fun/playful', 'Inspirational', 'Bold', 'Educational', 'Minimalist', 'Other'] as $tone)
                            <label class="brand-tone-label flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="brand_tone[]"
                                    value="{{ $tone }}"
                                    {{ in_array($tone, old('brand_tone', $client->brand_tone ?? [])) ? 'checked' : '' }}
                                    class="brand-tone-cb w-4 h-4 text-[#CD571B] border-gray-300 rounded focus:ring-[#CD571B]"
                                >
                                <span class="ml-3 text-sm text-gray-700">{{ $tone }}</span>
                            </label>
                            @endforeach
                        </div>
                        <p id="brand-tone-hint" class="text-xs text-gray-400 mt-2 hidden">Maximum 3 tones selected.</p>
                        <script>
                        (function() {
                            function syncBrandTone() {
                                var cbs = document.querySelectorAll('.brand-tone-cb');
                                var count = document.querySelectorAll('.brand-tone-cb:checked').length;
                                var hint = document.getElementById('brand-tone-hint');
                                hint.classList.toggle('hidden', count < 3);
                                cbs.forEach(function(cb) {
                                    var lbl = cb.closest('.brand-tone-label');
                                    if (!cb.checked && count >= 3) {
                                        cb.disabled = true;
                                        lbl.style.opacity = '0.45';
                                        lbl.style.cursor = 'not-allowed';
                                    } else {
                                        cb.disabled = false;
                                        lbl.style.opacity = '';
                                        lbl.style.cursor = '';
                                    }
                                });
                            }
                            document.addEventListener('DOMContentLoaded', function() {
                                document.querySelectorAll('.brand-tone-cb').forEach(function(cb) {
                                    cb.addEventListener('change', syncBrandTone);
                                });
                                syncBrandTone();
                            });
                        })();
                        </script>
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
                                    class="w-4 h-4 text-[#CD571B] border-gray-300 rounded focus:ring-[#CD571B]"
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
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="flex items-center p-4 bg-gray-50 rounded-lg cursor-pointer">
                            <input
                                type="checkbox"
                                name="share_third_party_content"
                                value="1"
                                {{ old('share_third_party_content', $client->share_third_party_content) ? 'checked' : '' }}
                                class="w-4 h-4 text-[#CD571B] border-gray-300 rounded focus:ring-[#CD571B]"
                            >
                            <span class="ml-3 text-sm text-gray-700">Share third-party content</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keywords for captions</label>
                        <textarea
                            name="keywords"
                            rows="3"
                            placeholder="Specific keywords, hashtags, or phrases to include"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >{{ old('keywords', $client->keywords) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Competitors to research</label>
                        <textarea
                            name="competitors"
                            rows="4"
                            placeholder="List competitor names or URLs, one per line"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >{{ old('competitors', $client->competitors) }}</textarea>
                        <p class="mt-2 text-sm text-gray-500">You can paste URLs or names — one per line.</p>
                    </div>
                </div>
            </div>

            <!-- ASSETS & BRANDING -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Assets & Branding</h2>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Brand assets link (Google Drive / Dropbox)</label>
                    <textarea
                        name="brand_assets_link"
                        rows="4"
                        placeholder="Paste one or more links here — each on its own line. You can also add labels before each link."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent font-mono text-sm"
                    >{{ old('brand_assets_link', $client->brand_assets_link) }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">Paste multiple links, each on its own line. Labels are welcome (e.g. "Client Assets: https://...").</p>
                </div>
            </div>

            <!-- SCHEDULING -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Scheduling & Approval</h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Timezone *</label>
                        <select
                            name="timezone"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                            required
                        >
                            @foreach($timezones as $tz)
                            <option value="{{ $tz }}" {{ old('timezone', $client->timezone) === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-data="postingDays({{ json_encode(old('posting_days', $client->posting_days ?? [])) }}, {{ json_encode(old('posting_times', $client->posting_times ?? [])) }})">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Posting days &amp; times</label>
                        <div class="space-y-2">
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <label class="flex items-center gap-3 p-3 hover:bg-gray-50 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="posting_days[]"
                                        value="{{ $day }}"
                                        x-model="selected"
                                        @change="toggle('{{ $day }}')"
                                        class="w-4 h-4 border-gray-300 rounded"
                                        style="accent-color:#CD571B;"
                                    >
                                    <span class="font-medium text-sm text-gray-800">{{ $day }}</span>
                                </label>
                                <div x-show="days['{{ $day }}']" x-cloak class="px-4 pb-3 flex items-center gap-4 bg-orange-50 border-t border-orange-100">
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs font-semibold text-gray-600">Start</label>
                                        <input type="time" name="posting_times[{{ $day }}][start]"
                                            :value="times['{{ $day }}'] && times['{{ $day }}'].start ? times['{{ $day }}'].start : '09:00'"
                                            class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                    </div>
                                    <span class="text-gray-400">—</span>
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs font-semibold text-gray-600">End</label>
                                        <input type="time" name="posting_times[{{ $day }}][end]"
                                            :value="times['{{ $day }}'] && times['{{ $day }}'].end ? times['{{ $day }}'].end : '17:00'"
                                            class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
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
                                {{ old('needs_approval', $client->needs_approval) ? 'checked' : '' }}
                                class="w-4 h-4 text-[#CD571B] border-gray-300 rounded focus:ring-[#CD571B]"
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
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                        >
                    </div>
                </div>
            </div>

            <!-- NETWORKS & STATUS -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Social Networks & Status</h2>

                {{-- Keep plan_type so validation passes without changing it --}}
                <input type="hidden" name="plan_type" value="{{ $client->plan_type }}">

                <div class="space-y-6">
                    <div x-data="{
                        selected: {{ json_encode(old('networks', $client->networks ?? [])) }},
                        toggle(net, checked) {
                            if (checked && !this.selected.includes(net)) { this.selected.push(net); }
                            else { this.selected = this.selected.filter(s => s !== net); }
                        }
                    }">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Social Networks</label>
                        <div class="space-y-2">
                            @foreach(['Facebook', 'Instagram', 'LinkedIn', 'Twitter/X', 'TikTok', 'YouTube', 'Google Business'] as $network)
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <label class="flex items-center gap-3 p-3 hover:bg-gray-50 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="networks[]"
                                        value="{{ $network }}"
                                        {{ in_array($network, old('networks', $client->networks ?? [])) ? 'checked' : '' }}
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
                                        value="{{ old('network_links.'.$network, $client->network_links[$network] ?? '') }}"
                                        placeholder="https://..."
                                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
                                    >
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Account Status *</label>
                        <select
                            name="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#CD571B] focus:border-transparent"
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
                    class="flex-1 btn-brand"
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
