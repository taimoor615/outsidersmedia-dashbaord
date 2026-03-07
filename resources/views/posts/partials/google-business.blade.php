<div class="space-y-4">
    <h4 class="text-md font-medium text-gray-900">Google Business Profile</h4>

    <!-- Post Type Selection -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Google Post Type</label>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <label class="flex items-center p-3 cursor-pointer rounded-lg border border-gray-300 hover:bg-gray-50">
                <input type="radio" name="google_post_type" value="whats_new" class="h-4 w-4 text-blue-600 focus:ring-blue-500" {{ old('google_post_type', 'whats_new') == 'whats_new' ? 'checked' : '' }}>
                <span class="ml-3 text-sm font-medium text-gray-900">What's New</span>
            </label>

            <label class="flex items-center p-3 cursor-pointer rounded-lg border border-gray-300 hover:bg-gray-50">
                <input type="radio" name="google_post_type" value="offer" class="h-4 w-4 text-blue-600 focus:ring-blue-500" {{ old('google_post_type') == 'offer' ? 'checked' : '' }}>
                <span class="ml-3 text-sm font-medium text-gray-900">Offer</span>
            </label>

            <label class="flex items-center p-3 cursor-pointer rounded-lg border border-gray-300 hover:bg-gray-50">
                <input type="radio" name="google_post_type" value="event" class="h-4 w-4 text-blue-600 focus:ring-blue-500" {{ old('google_post_type') == 'event' ? 'checked' : '' }}>
                <span class="ml-3 text-sm font-medium text-gray-900">Event</span>
            </label>
        </div>
    </div>

    <!-- Google Message -->
    <div>
        <label for="google_message" class="block text-sm font-medium text-gray-700 mb-2">Google Business Message</label>
        <textarea name="google_message" id="google_message" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your Google Business message...">{{ old('google_message') }}</textarea>
    </div>

    <!-- What's New Section -->
    <div id="whatsNewSection" class="google-section">
        <div class="bg-gray-50 p-4 rounded-lg space-y-4">
            <h5 class="text-sm font-medium text-gray-900">What's New Details</h5>

            <!-- Button Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Call to Action Button (Optional)</label>
                <select name="google_button" id="google_button" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="none" {{ old('google_button', 'none') == 'none' ? 'selected' : '' }}>None</option>
                    <option value="book" {{ old('google_button') == 'book' ? 'selected' : '' }}>Book</option>
                    <option value="order_online" {{ old('google_button') == 'order_online' ? 'selected' : '' }}>Order Online</option>
                    <option value="buy" {{ old('google_button') == 'buy' ? 'selected' : '' }}>Buy</option>
                    <option value="learn_more" {{ old('google_button') == 'learn_more' ? 'selected' : '' }}>Learn More</option>
                    <option value="sign_up" {{ old('google_button') == 'sign_up' ? 'selected' : '' }}>Sign Up</option>
                </select>
            </div>

            <!-- Button Link -->
            <div id="buttonLinkSection" class="hidden">
                <label for="google_button_link" class="block text-sm font-medium text-gray-700 mb-2">Button Link</label>
                <input type="url" name="google_button_link" id="google_button_link" value="{{ old('google_button_link') }}" placeholder="https://example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <!-- Offer Section -->
    <div id="offerSection" class="google-section hidden">
        <div class="bg-gray-50 p-4 rounded-lg space-y-4">
            <h5 class="text-sm font-medium text-gray-900">Offer Details</h5>

            <!-- Offer Title -->
            <div>
                <label for="offer_title" class="block text-sm font-medium text-gray-700 mb-2">Offer Title</label>
                <input type="text" name="offer_title" id="offer_title" value="{{ old('offer_title') }}" placeholder="e.g., 20% Off Summer Sale" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="offer_start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="offer_start_date" id="offer_start_date" value="{{ old('offer_start_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="offer_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="offer_end_date" id="offer_end_date" value="{{ old('offer_end_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Add Time Toggle -->
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" id="addOfferTime" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Add specific times</span>
                </label>
            </div>

            <!-- Time Range (Hidden by default) -->
            <div id="offerTimeSection" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="offer_start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                    <input type="time" name="offer_start_time" id="offer_start_time" value="{{ old('offer_start_time') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="offer_end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                    <input type="time" name="offer_end_time" id="offer_end_time" value="{{ old('offer_end_time') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- More Details Toggle -->
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" id="addMoreDetails" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Add more details</span>
                </label>
            </div>

            <!-- More Details Section (Hidden by default) -->
            <div id="moreDetailsSection" class="hidden space-y-4">
                <div>
                    <label for="coupon_code" class="block text-sm font-medium text-gray-700 mb-2">Coupon Code</label>
                    <input type="text" name="coupon_code" id="coupon_code" value="{{ old('coupon_code') }}" placeholder="e.g., SAVE20" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="offer_link" class="block text-sm font-medium text-gray-700 mb-2">Offer Link</label>
                    <input type="url" name="offer_link" id="offer_link" value="{{ old('offer_link') }}" placeholder="https://example.com/offer" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="terms_conditions" class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                    <textarea name="terms_conditions" id="terms_conditions" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter terms and conditions...">{{ old('terms_conditions') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Section -->
    <div id="eventSection" class="google-section hidden">
        <div class="bg-gray-50 p-4 rounded-lg space-y-4">
            <h5 class="text-sm font-medium text-gray-900">Event Details</h5>

            <!-- Event Title -->
            <div>
                <label for="event_title" class="block text-sm font-medium text-gray-700 mb-2">Event Title</label>
                <input type="text" name="event_title" id="event_title" value="{{ old('event_title') }}" placeholder="e.g., Summer Workshop 2024" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="event_start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="event_start_date" id="event_start_date" value="{{ old('event_start_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="event_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="event_end_date" id="event_end_date" value="{{ old('event_end_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Add Time Toggle -->
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" id="addEventTime" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Add specific times</span>
                </label>
            </div>

            <!-- Time Range (Hidden by default) -->
            <div id="eventTimeSection" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="event_start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                    <input type="time" name="event_start_time" id="event_start_time" value="{{ old('event_start_time') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="event_end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                    <input type="time" name="event_end_time" id="event_end_time" value="{{ old('event_end_time') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Button Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Call to Action Button (Optional)</label>
                <select name="event_button" id="event_button" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="none">None</option>
                    <option value="book">Book</option>
                    <option value="order_online">Order Online</option>
                    <option value="buy">Buy</option>
                    <option value="learn_more">Learn More</option>
                    <option value="sign_up">Sign Up</option>
                </select>
            </div>

            <!-- Button Link -->
            <div id="eventButtonLinkSection" class="hidden">
                <label for="event_button_link" class="block text-sm font-medium text-gray-700 mb-2">Button Link</label>
                <input type="url" name="event_button_link" id="event_button_link" placeholder="https://example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Google post type handling
    const googlePostTypeRadios = document.querySelectorAll('input[name="google_post_type"]');
    const whatsNewSection = document.getElementById('whatsNewSection');
    const offerSection = document.getElementById('offerSection');
    const eventSection = document.getElementById('eventSection');

    function updateGoogleSections() {
        const selectedType = document.querySelector('input[name="google_post_type"]:checked')?.value;

        whatsNewSection.classList.add('hidden');
        offerSection.classList.add('hidden');
        eventSection.classList.add('hidden');

        if (selectedType === 'whats_new') {
            whatsNewSection.classList.remove('hidden');
        } else if (selectedType === 'offer') {
            offerSection.classList.remove('hidden');
        } else if (selectedType === 'event') {
            eventSection.classList.remove('hidden');
        }
    }

    googlePostTypeRadios.forEach(radio => {
        radio.addEventListener('change', updateGoogleSections);
    });

    // Button link visibility
    const googleButton = document.getElementById('google_button');
    const buttonLinkSection = document.getElementById('buttonLinkSection');

    if (googleButton) {
        googleButton.addEventListener('change', function() {
            if (this.value !== 'none') {
                buttonLinkSection.classList.remove('hidden');
            } else {
                buttonLinkSection.classList.add('hidden');
            }
        });

        // Initial check
        if (googleButton.value !== 'none') {
            buttonLinkSection.classList.remove('hidden');
        }
    }

    // Offer time toggle
    const addOfferTime = document.getElementById('addOfferTime');
    const offerTimeSection = document.getElementById('offerTimeSection');

    if (addOfferTime) {
        addOfferTime.addEventListener('change', function() {
            if (this.checked) {
                offerTimeSection.classList.remove('hidden');
            } else {
                offerTimeSection.classList.add('hidden');
            }
        });
    }

    // More details toggle
    const addMoreDetails = document.getElementById('addMoreDetails');
    const moreDetailsSection = document.getElementById('moreDetailsSection');

    if (addMoreDetails) {
        addMoreDetails.addEventListener('change', function() {
            if (this.checked) {
                moreDetailsSection.classList.remove('hidden');
            } else {
                moreDetailsSection.classList.add('hidden');
            }
        });
    }

    // Event time toggle
    const addEventTime = document.getElementById('addEventTime');
    const eventTimeSection = document.getElementById('eventTimeSection');

    if (addEventTime) {
        addEventTime.addEventListener('change', function() {
            if (this.checked) {
                eventTimeSection.classList.remove('hidden');
            } else {
                eventTimeSection.classList.add('hidden');
            }
        });
    }

    // Event button link
    const eventButton = document.getElementById('event_button');
    const eventButtonLinkSection = document.getElementById('eventButtonLinkSection');

    if (eventButton) {
        eventButton.addEventListener('change', function() {
            if (this.value !== 'none') {
                eventButtonLinkSection.classList.remove('hidden');
            } else {
                eventButtonLinkSection.classList.add('hidden');
            }
        });
    }

    // Initialize sections
    updateGoogleSections();
});
</script>
@endpush
