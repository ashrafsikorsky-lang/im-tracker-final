<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IM Tournament Tracker</title>
    <!-- Using Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<!-- Flexbox setup to ensure the footer stays at the bottom of the screen -->
<body class="bg-gray-50 flex flex-col min-h-screen font-sans">

    <!-- Solid Blue Header with Logos -->
    <header class="bg-blue-600 py-3 px-6 flex justify-between items-center shadow-md">
        
        <!-- Left: KPMIM College Logo -->
        <div class="flex items-center">
            <!-- asset() points directly to your public folder -->
            <img src="{{ asset('images/kpmim_logo.png') }}" alt="KPMIM Logo" class="h-12 object-contain">
        </div>

        <!-- Center: System Title (Optional, hidden on very small phone screens to save space) -->
        <h1 class="text-white text-2xl font-bold hidden sm:block">IM Tournament Tracker</h1>

        <!-- Right: Your Custom IM Tracker Logo -->
        <div class="flex items-center">
            <img src="{{ asset('images/im_tracker_logo.png') }}" alt="IM Tracker Logo" class="h-12 object-contain">
        </div>
        
    </header>

    <!-- Main Content Area with Background Banner -->
    <!-- The inline style loads the banner. The bg-cover and bg-center keep it looking good on all screens. -->
    <main class="flex-grow flex items-center justify-center p-4 relative bg-cover bg-center" 
          style="background-image: url('{{ asset('images/banner_background.png') }}');">
        
        <!-- Dark Overlay: This adds a transparent dark shadow over the banner so your white card pops -->
        <div class="absolute inset-0 bg-black/40"></div>
        
        <!-- Centered White Card (z-10 keeps it above the dark overlay) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 w-full max-w-sm relative z-10">
            
            <h2 class="text-blue-600 text-2xl font-bold text-center mb-8 uppercase tracking-wide">Welcome</h2>
            
            <div class="flex flex-col space-y-4">
                <!-- Log In Button -->
                <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-md transition duration-150 shadow-sm">
                    Log In
                </a>
                
                <!-- Sign Up Button -->
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block w-full text-center bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold py-2.5 rounded-md transition duration-150">
                        Sign Up
                    </a>
                @endif
            </div>

        </div>
    </main>

    <!-- Dark Footer -->
    <footer class="bg-[#0f172a] text-gray-400 text-center py-6 text-sm relative z-10">
        <p>&copy; 2026 KPMIM IM Tournament Tracking System. All rights reserved.</p>
        <p class="mt-1">imtracker@kpmim.edu.my</p>
    </footer>

</body>
</html>