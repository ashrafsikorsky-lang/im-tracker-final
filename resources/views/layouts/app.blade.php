<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IM Tournament Tracker</title>
    <!-- Tailwind CSS for the Header/Footer -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- RESTORED GLOBAL CSS: This fixes all your broken forms, cards, and tables! -->
    <style>
        :root {
            --primary: #2563eb;
            --danger: #dc2626;
            --success: #16a34a;
        }
        
        /* --- Buttons --- */
        .btn { background-color: var(--primary); color: white; padding: 10px 18px; border-radius: 6px; cursor: pointer; transition: 0.2s; border: none; font-weight: 600; display: inline-block; text-align: center; font-size: 0.95rem; }
        .btn:hover { background-color: #1d4ed8; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3); }
        .btn-danger { background-color: var(--danger); }
        .btn-danger:hover { background-color: #b91c1c; box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.3); }
        
        /* --- Alerts --- */
        .alert-error { background-color: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; border: 1px solid #f87171; margin-bottom: 15px; font-weight: 500; }
        .alert-success { background-color: #dcfce3; color: #166534; padding: 12px; border-radius: 6px; border: 1px solid #4ade80; margin-bottom: 15px; font-weight: 500; }

        /* --- Cards --- */
        .card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); margin-bottom: 24px; width: 100%; border: 1px solid #e2e8f0; }
        .card h2, .card h3 { color: var(--primary); margin-top: 0; margin-bottom: 16px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; font-weight: 700; font-size: 1.25rem; }
        
        /* --- Tables --- */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.95rem; }
        table th, table td { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        table th { background-color: #f8fafc; color: #475569; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em; font-weight: 700; border-top: 1px solid #e2e8f0; }
        table tr:hover { background-color: #f1f5f9; transition: background-color 0.2s; }

        /* --- Forms & Inputs --- */
        label { font-weight: 600; color: #475569; display: block; margin-top: 12px; margin-bottom: 6px; font-size: 0.9rem; }
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], select, textarea {
            width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; font-family: inherit; font-size: 0.95rem; transition: all 0.2s; background-color: #fcfcfc;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2); background-color: white; }
        textarea { resize: vertical; min-height: 120px; }
        
        /* Constrain wide tables on mobile */
        .table-responsive { overflow-x: auto; width: 100%; }
    </style>
</head>
<body class="bg-slate-50 flex flex-col min-h-screen font-sans antialiased text-gray-800">

    <!-- ========================================== -->
    <!-- 1. GLOBAL HEADER                           -->
    <!-- ========================================== -->
    <header class="bg-blue-600 py-3 px-6 flex justify-between items-center shadow-md w-full relative z-50">
        <!-- Left: KPMIM College Logo -->
        <div class="flex items-center shrink-0">
            <a href="{{ Auth::check() ? route('dashboard') : url('/') }}">
                <img src="{{ asset('images/kpmim_logo.png') }}" alt="KPMIM Logo" class="h-10 object-contain hover:opacity-80 transition">
            </a>
        </div>

        <!-- Center: System Title -->
        <h1 class="text-white text-xl md:text-2xl font-bold hidden sm:block tracking-wide absolute left-1/2 transform -translate-x-1/2 shadow-sm">
            IM Tournament Tracker
        </h1>

        <!-- Right: Custom IM Tracker Logo -->
        <div class="flex items-center shrink-0">
            <a href="{{ Auth::check() ? route('dashboard') : url('/') }}">
                <img src="{{ asset('images/im_tracker_logo.png') }}" alt="IM Tracker Logo" class="h-10 object-contain hover:opacity-80 transition">
            </a>
        </div>
    </header>

    <!-- ========================================== -->
    <!-- 2. NAVIGATION MENU (Only shows if logged in)-->
    <!-- ========================================== -->
    @auth
    <nav class="bg-[#0f172a] text-white shadow-md border-b-4 border-blue-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-center gap-x-8 gap-y-2 py-3 text-sm font-semibold tracking-wide">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-400 transition flex items-center gap-2">Dashboard</a>
            <a href="{{ url('/store-data') }}" class="hover:text-blue-400 transition flex items-center gap-2">Manage Teams</a>
            <a href="{{ url('/support') }}" class="hover:text-blue-400 transition flex items-center gap-2">Support & Disputes</a>
            
            @if(Auth::user()->role === 'admin')
                <a href="{{ url('/admin-inbox') }}" class="text-yellow-400 hover:text-yellow-200 transition flex items-center gap-2">Admin Inbox</a>
            @endif
            
            <form method="POST" action="{{ route('logout') }}" class="inline m-0 p-0">
                @csrf
                <button type="submit" class="hover:text-red-400 font-semibold transition bg-transparent border-none cursor-pointer text-white m-0 p-0 flex items-center gap-2">Logout</button>
            </form>
        </div>
    </nav>
    @endauth

    <!-- ========================================== -->
    <!-- 3. MAIN CONTENT AREA                       -->
    <!-- ========================================== -->
    <main class="flex-grow flex flex-col items-center justify-start p-6 w-full max-w-5xl mx-auto mt-4">
        @yield('content')
    </main>

    <!-- ========================================== -->
    <!-- 4. GLOBAL FOOTER                           -->
    <!-- ========================================== -->
    <footer class="bg-[#0f172a] text-gray-400 text-center py-6 text-sm w-full mt-auto border-t border-gray-800">
        <p>&copy; 2026 KPMIM IM Tournament Tracking System. All rights reserved.</p>
        <p class="mt-1 hover:text-gray-300 transition cursor-pointer">imtracker.kpmim.edu.my</p>
    </footer>

</body>
</html>