<nav x-data="{ open: false }" class="bg-blue-600 shadow-md relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between h-16 items-center">
            
            <!-- LEFT SIDE: KPMIM Logo -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="shrink-0">
                    <img src="{{ asset('images/kpmim_logo.png') }}" alt="KPMIM Logo" class="h-10 object-contain hover:opacity-80 transition">
                </a>
            </div>

            <!-- CENTER: System Title -->
            <!-- We use 'absolute left-1/2 transform -translate-x-1/2' to guarantee it stays perfectly centered -->
            <h1 class="text-white text-xl md:text-2xl font-bold hidden sm:block tracking-wide absolute left-1/2 transform -translate-x-1/2">
                IM Tournament Tracker
            </h1>

            <!-- RIGHT SIDE: Dropdown + IM Tracker Logo -->
            <div class="flex items-center gap-4">

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        
                        <!-- The clickable button showing the User's Name -->
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md text-blue-700 bg-white hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <!-- The actual Dropdown Menu -->
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- IM Tracker Logo -->
                <div class="hidden sm:flex items-center shrink-0">
                    <img src="{{ asset('images/im_tracker_logo.png') }}" alt="IM Tracker Logo" class="h-10 object-contain">
                </div>

                <!-- Hamburger Menu for Mobile -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile View) -->
    <!-- Wrapped in a white background so it overrides the blue when opened on phones -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200 shadow-lg absolute w-full">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center justify-between">
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <!-- Mini IM Tracker logo for mobile users -->
                <img src="{{ asset('images/im_tracker_logo.png') }}" alt="IM Tracker Logo" class="h-8 object-contain">
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>