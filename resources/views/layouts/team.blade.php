<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg" />
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png" />
    <link rel="manifest" href="/images/site.webmanifest" />

    <title>@yield('title', 'Dashboard') - Outsidersmedia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.datePicker = function(fieldName, initialValue) {
            var MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            return {
                open: false,
                fieldName: fieldName || 'scheduled_at',
                selectedDate: '', selectedTime: '',
                displayValue: '', hiddenValue: initialValue || '',
                selMonth: '', selDay: '', selYear: '',
                selHour: '12', selMin: '00', selAmpm: 'AM',
                pastError: '',
                months: MONTHS,
                get years() { var y = new Date().getFullYear(); return [y, y+1, y+2]; },
                get days() {
                    if (!this.selMonth || !this.selYear) return Array.from({length:31},function(_,i){return i+1;});
                    var n = new Date(parseInt(this.selYear), MONTHS.indexOf(this.selMonth)+1, 0).getDate();
                    return Array.from({length:n},function(_,i){return i+1;});
                },
                init() {
                    if (initialValue) {
                        var nm = initialValue.replace(' ','T').substring(0,16);
                        var pts = nm.split('T');
                        this.selectedDate = pts[0] || '';
                        this.selectedTime = pts[1] ? pts[1].substring(0,5) : '';
                        if (this.selectedDate) {
                            var d = new Date(this.selectedDate+'T00:00');
                            this.selMonth = MONTHS[d.getMonth()];
                            this.selDay   = String(d.getDate());
                            this.selYear  = String(d.getFullYear());
                        }
                        if (this.selectedTime) {
                            var tp = this.selectedTime.split(':');
                            var h = parseInt(tp[0]), m = parseInt(tp[1]);
                            this.selAmpm = h >= 12 ? 'PM' : 'AM';
                            h = h % 12 || 12;
                            this.selHour = String(h);
                            this.selMin  = String(m).padStart(2,'0');
                        }
                        this.updateDisplay();
                    }
                },
                applyDate() {
                    if (this.selMonth && this.selDay && this.selYear) {
                        var mi = MONTHS.indexOf(this.selMonth)+1;
                        this.selectedDate = this.selYear+'-'+String(mi).padStart(2,'0')+'-'+String(this.selDay).padStart(2,'0');
                    } else { this.selectedDate = ''; }
                    this.applyTime();
                },
                applyTime() {
                    var h = parseInt(this.selHour);
                    if (this.selAmpm==='PM' && h!==12) h+=12;
                    if (this.selAmpm==='AM' && h===12) h=0;
                    this.selectedTime = String(h).padStart(2,'0')+':'+this.selMin;
                    this.updateDisplay();
                },
                updateDisplay() {
                    if (this.selectedDate) {
                        var d = new Date(this.selectedDate+'T'+(this.selectedTime||'00:00'));
                        this.displayValue = d.toLocaleDateString('en-US',{weekday:'short',year:'numeric',month:'long',day:'numeric'})
                            +' · '+d.toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit'});
                        this.hiddenValue = this.selectedDate+'T'+(this.selectedTime||'00:00');
                    } else { this.displayValue=''; this.hiddenValue=''; }
                },
                confirm() {
                    if (this.selectedDate) {
                        var today = new Date(); today.setHours(0,0,0,0);
                        var sel = new Date(this.selectedDate+'T00:00');
                        if (sel < today) { this.pastError = 'Cannot schedule for a past date. Please choose today or a future date.'; return; }
                    }
                    this.pastError = '';
                    this.updateDisplay(); this.open=false;
                },
                clear() {
                    this.selectedDate=''; this.selectedTime='';
                    this.selMonth=''; this.selDay=''; this.selYear='';
                    this.selHour='12'; this.selMin='00'; this.selAmpm='AM';
                    this.displayValue=''; this.hiddenValue='';
                }
            };
        };
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .team-nav { scrollbar-width: thin; scrollbar-color: #EC921A rgba(120,40,10,0.4); }
        .team-nav::-webkit-scrollbar { width: 5px; }
        .team-nav::-webkit-scrollbar-track { background: rgba(120,40,10,0.35); border-radius: 20px; margin: 8px 0; }
        .team-nav::-webkit-scrollbar-thumb { background: #EC921A; border-radius: 20px; }
        .team-nav::-webkit-scrollbar-thumb:hover { background: #f5a840; }
        select[x-model="selMonth"],select[x-model="selDay"],select[x-model="selYear"],
        select[x-model="selHour"],select[x-model="selMin"],select[x-model="selAmpm"] {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar for Team -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 sidebar-brand transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6" style="border-bottom:1px solid rgba(205,87,27,0.3)">
                <div class="flex items-center gap-3">
                    <div class="sidebar-wrapper-img">
                        <img src="{{ asset('images/dashboard-sidebar-img.png') }}" alt="" srcset="" class="img-fluid">
                    </div>
                    <span class="text-xl font-bold text-white">Outsidersmedia</span>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-orange-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <div class="flex-1 min-h-0 relative">
                <nav class="team-nav h-full px-4 py-6 space-y-2 overflow-y-auto pb-10">

                <!-- Dashboard -->
                <a href="{{ route('team.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-orange-200 hover:text-white rounded-xl transition-all {{ request()->routeIs('team.dashboard') ? 'sidebar-brand-active text-white shadow-lg' : '' }}" style="{{ request()->routeIs('team.dashboard') ? 'background:rgba(205,87,27,0.3)' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Clients -->
                <a href="{{ route('clients.index') }}" class="flex items-center gap-3 px-4 py-3 text-orange-200 hover:text-white rounded-xl transition-all {{ request()->routeIs('clients.*') ? 'text-white shadow-lg' : '' }}" style="{{ request()->routeIs('clients.*') ? 'background:rgba(205,87,27,0.3)' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="font-medium">Clients</span>
                </a>

                <!-- Posts -->
                <a href="{{ route('posts.index') }}" class="flex items-center gap-3 px-4 py-3 text-orange-200 hover:text-white rounded-xl transition-all {{ request()->routeIs('posts.*') ? 'text-white shadow-lg' : '' }}" style="{{ request()->routeIs('posts.*') ? 'background:rgba(205,87,27,0.3)' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    <span class="font-medium">Posts</span>
                </a>

                <!-- Calendar -->
                <a href="{{ route('calendar.index') }}" class="flex items-center gap-3 px-4 py-3 text-orange-200 hover:text-white rounded-xl transition-all {{ request()->routeIs('calendar.*') ? 'text-white shadow-lg' : '' }}" style="{{ request()->routeIs('calendar.*') ? 'background:rgba(205,87,27,0.3)' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="font-medium">Calendar</span>
                </a>

                <!-- Feedback -->
                <a href="{{ route('feedback.index') }}" class="flex items-center gap-3 px-4 py-3 text-orange-200 hover:text-white rounded-xl transition-all {{ request()->routeIs('feedback.*') ? 'text-white shadow-lg' : '' }}" style="{{ request()->routeIs('feedback.*') ? 'background:rgba(205,87,27,0.3)' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span class="font-medium">Feedback</span>
                </a>

                <!-- Divider -->
                <div class="pt-6 pb-2">
                    <p class="px-4 text-xs font-semibold text-orange-400 uppercase tracking-wider">Content Status</p>
                </div>

                <!-- Pending Approval -->
                <a href="{{ route('posts.index', ['status' => 'pending_approval']) }}" class="flex items-center gap-3 px-4 py-3 text-orange-200 hover:text-white rounded-xl transition-all {{ request()->get('status') === 'pending_approval' ? 'text-white shadow-lg' : '' }}" style="{{ request()->get('status') === 'pending_approval' ? 'background:rgba(205,87,27,0.3)' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">Pending Approval</span>
                    @if(isset($pending_approval_count) && $pending_approval_count > 0)
                    <span class="ml-auto px-2 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">{{ $pending_approval_count }}</span>
                    @endif
                </a>

                <!-- Needs Changes -->
                <a href="{{ route('posts.index', ['status' => 'changes_requested']) }}" class="flex items-center gap-3 px-4 py-3 text-orange-200 hover:text-white rounded-xl transition-all {{ request()->get('status') === 'changes_requested' ? 'text-white shadow-lg' : '' }}" style="{{ request()->get('status') === 'changes_requested' ? 'background:rgba(205,87,27,0.3)' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span class="font-medium">Needs Changes</span>
                    @if(isset($changes_requested_count) && $changes_requested_count > 0)
                    <span class="ml-auto px-2 py-1 bg-orange-500 text-white text-xs font-bold rounded-full">{{ $changes_requested_count }}</span>
                    @endif
                </a>

                <!-- Client Notes -->
                <a href="{{ route('client-notes.index') }}" class="flex items-center gap-3 px-4 py-3 text-orange-200 hover:text-white rounded-xl transition-all {{ request()->routeIs('client-notes.*') ? 'text-white shadow-lg' : '' }}" style="{{ request()->routeIs('client-notes.*') ? 'background:rgba(205,87,27,0.3)' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    <span class="font-medium">Client Notes</span>
                </a>

                <!-- Scheduled -->
                <a href="{{ route('calendar.index') }}" class="flex items-center gap-3 px-4 py-3 text-orange-200 hover:text-white rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span class="font-medium">Scheduled</span>
                    @if(isset($scheduled_count) && $scheduled_count > 0)
                    <span class="ml-auto px-2 py-1 text-white text-xs font-bold rounded-full" style="background:#EC921A">{{ $scheduled_count }}</span>
                    @endif
                </a>
            </nav>
            <!-- Fade overlay at bottom of nav -->
            <div class="absolute bottom-0 left-0 right-0 h-10 pointer-events-none" style="background: linear-gradient(to bottom, transparent, #1a0a04);"></div>
            </div>

            <!-- User Profile -->
            <div class="p-4" style="border-top:1px solid rgba(205,87,27,0.3)">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors" style="background:rgba(205,87,27,0.2);" onmouseover="this.style.background='rgba(205,87,27,0.35)'" onmouseout="this.style.background='rgba(205,87,27,0.2)'">
                    <img src="{{ auth()->user()->profile_image_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full border-2 flex-shrink-0" style="border-color:#EC921A">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-medium text-orange-300 truncate">Team Member</p>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-3 sm:px-6">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <h1 class="lg:hidden text-sm sm:text-base font-bold text-gray-900 whitespace-nowrap overflow-hidden text-ellipsis">@yield('page-title', 'Dashboard')</h1>

                <div class="ml-auto flex items-center gap-4">
                    <!-- Notifications dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                        </button>
                        <div x-show="open" x-transition class="fixed top-16 left-3 right-3 lg:absolute lg:top-full lg:left-auto lg:right-0 lg:mt-2 lg:w-80 max-h-96 overflow-y-auto bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50" style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                                <span class="font-semibold text-gray-900">Notifications</span>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-[#CD571B] hover:text-[#b54c17]">Mark all read</button>
                                </form>
                                @endif
                            </div>
                            @forelse(auth()->user()->unreadNotifications->take(15) as $notification)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full flex px-4 py-3 hover:bg-gray-50 border-b border-gray-100 text-left">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900">{{ $notification->data['message'] ?? 'Update' }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </button>
                            </form>
                            @empty
                            <p class="px-4 py-6 text-sm text-gray-500 text-center">No new notifications</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 hover:bg-gray-100 rounded-lg px-3 py-2 transition-colors">
                            <div class="flex flex-col items-end leading-tight mr-0.5">
                                <span class="text-sm font-bold text-gray-900 max-w-[140px] truncate">{{ auth()->user()->name }}</span>
                                <span class="text-xs font-medium text-orange-500">Team Member</span>
                            </div>
                            <img src="{{ auth()->user()->profile_image_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full flex-shrink-0">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50" style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profile Settings
                            </a>
                            <div class="border-t border-gray-200 my-2"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="cursor-pointer flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" style="display: none;"></div>

    @stack('scripts')
</body>
</html>
