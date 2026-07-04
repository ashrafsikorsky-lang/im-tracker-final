<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'IM Tournament Tracker') }}</title>
    
    <!-- Using Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen font-sans antialiased">

    <!-- ========================================== -->
    <!-- GLOBAL HEADER WITH LOGOS                   -->
    <!-- ========================================== -->
    <header class="bg-blue-600 py-3 px-6 flex justify-between items-center shadow-md w-full relative z-50">
        
        <!-- Left: KPMIM College Logo -->
        <div class="flex items-center">
            <a href="{{ url('/') }}" class="shrink-0">
                <img src="{{ asset('images/kpmim_logo.png') }}" alt="KPMIM Logo" class="h-10 object-contain hover:opacity-80 transition">
            </a>
        </div>

        <!-- Center: System Title -->
        <h1 class="text-white text-xl md:text-2xl font-bold hidden sm:block tracking-wide absolute left-1/2 transform -translate-x-1/2">
            IM Tournament Tracker
        </h1>

        <!-- Right: Custom IM Tracker Logo -->
        <div class="flex items-center shrink-0">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/im_tracker_logo.png') }}" alt="IM Tracker Logo" class="h-10 object-contain hover:opacity-80 transition">
            </a>
        </div>
        
    </header>

    <!-- ========================================== -->
    <!-- MAIN CONTENT AREA                          -->
    <!-- ========================================== -->
    <main class="flex-grow flex items-center justify-center p-4">
        
        <!-- THIS CONSTRAINS THE LOGIN/REGISTER CARDS TO THE PERFECT SIZE -->
        <div class="w-full max-w-sm relative z-10">
            {{ $slot }}
        </div>
        
    </main>

    <!-- ========================================== -->
    <!-- GLOBAL FOOTER                              -->
    <!-- ========================================== -->
    <footer class="bg-[#0f172a] text-gray-400 text-center py-6 text-sm w-full relative z-10">
        <p>&copy; {{ date('Y') }} KPMIM IM Tournament Tracking System. All rights reserved.</p>
        <p class="mt-1">imtracker.kpmim.edu.my</p>
    </footer>

</body>
</html>