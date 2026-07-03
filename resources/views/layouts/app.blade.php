<!DOCTYPE html>
<html>
<head>
    <title>IM Tournament Tracker</title>
    
    <!-- Link to your style.css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    
    <!-- Specific styling for the Header and Footer -->
    <style>
        /* Override body padding so header touches the top of the screen */
        body { padding: 0; min-height: 100vh; justify-content: flex-start; }
        
        .main-header { background-color: var(--primary); color: white; padding: 20px; text-align: center; width: 100%; }
        .main-header h1 { color: white; margin: 0; font-size: 1.8rem; }
        
        /* The Navigation Bar (Interactable links) */
        .nav-bar { background-color: #1e40af; width: 100%; padding: 12px; display: flex; justify-content: center; gap: 25px; }
        .nav-bar a, .nav-btn { color: white; text-decoration: none; font-weight: bold; font-size: 0.95rem; cursor: pointer; }
        .nav-bar a:hover, .nav-btn:hover { text-decoration: underline; color: #cbd5e1; }
        
        /* The Footer */
        .main-footer { background-color: #0f172a; color: #94a3b8; text-align: center; padding: 15px; width: 100%; font-size: 0.85rem; margin-top: auto; }
        
        /* Ensure the middle content has breathing room */
        .page-content { width: 100%; padding: 2rem; display: flex; flex-direction: column; align-items: center; }
    </style>
</head>
<body>

    <!-- ========================================== -->
    <!-- THE HEADER & NAVIGATION BAR                -->
    <!-- ========================================== -->
    <header class="main-header">
        <h1>IM Tournament Tracker</h1>
    </header>

    <!-- Only show the navigation links IF the user is logged in -->
    @auth
        <nav class="nav-bar">
            <a href="{{ url('/dashboard') }}">Dashboard</a>
            
            @if(Auth::user()->role === 'admin')
                <a href="{{ url('/store-data') }}">Manage Teams</a>
                <a href="{{ url('/admin-inbox') }}" style="color: #fef08a;">Admin Inbox</a>
            @endif
            
            <a href="{{ url('/view-information') }}">View Leaderboard & Matches</a>
            
            <!-- Everyone gets access to the Support form -->
            <a href="{{ url('/support') }}">Support & Disputes</a>
            
            <form method="POST" action="{{ route('logout') }}" style="display:inline; margin: 0;">
                @csrf
                <button type="submit" class="nav-btn" style="background:none; border:none; padding:0;">Logout</button>
            </form>
        </nav>
    @endauth

    <!-- ========================================== -->
    <!-- THE MAIN CONTENT                           -->
    <!-- ========================================== -->
    <main class="page-content">
        @yield('content')
    </main>

    <!-- ========================================== -->
    <!-- THE FOOTER                                 -->
    <!-- ========================================== -->
    <footer class="main-footer">
        &copy; 2026 KPMIM IM Tournament Tracking System. All rights reserved.<br>
        imtracker.kpmim.edu.my
    </footer>

</body>
</html>