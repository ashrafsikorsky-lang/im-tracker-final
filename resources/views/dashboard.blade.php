@extends('layouts.app')

@section('content')
    <div class="content" style="max-width: 800px; width: 100%;">
        
        <!-- ========================================== -->
        <!-- GREETING SECTION                           -->
        <!-- ========================================== -->
        <div class="card" style="text-align: center; background-color: #f8fafc; border-bottom: 4px solid var(--primary); margin-bottom: 30px;">
            <!-- Grab the User's Name from the Database -->
            <h2 style="color: var(--primary); margin-bottom: 5px;">Welcome back, {{ Auth::user()->name }}!</h2>
            
            <!-- Grab the Role. We use ucfirst() to capitalize 'admin' to 'Admin' -->
            <p style="font-size: 1.1rem; color: #475569; margin: 0;">
                Logged in as: <strong style="color: {{ Auth::user()->role === 'admin' ? '#dc2626' : '#0284c7' }};">{{ ucfirst(Auth::user()->role) }}</strong>
            </p>
        </div>

        <!-- ========================================== -->
        <!-- ACTION CARDS (Styled like your image!)     -->
        <!-- ========================================== -->
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            
                <a href="{{ url('/store-data') }}" class="dashboard-action-card">
                    <div class="card-icon">
                        📋 <!-- NOTE: You can replace this emoji with an <img src="your-image.png"> if you have image files! -->
                    </div>
                    <div class="card-text">
                        Register & Manage<br>Teams
                    </div>
                </a>
            
            <!-- EVERYONE SEES THIS CARD -->
            <a href="{{ url('/view-information') }}" class="dashboard-action-card">
                <div class="card-icon">
                    🏆 <!-- NOTE: You can replace this emoji with an <img src="your-image.png"> if you have image files! -->
                </div>
                <div class="card-text">
                    View Leaderboard<br>& Matches
                </div>
            </a>

        </div>
    </div>

    <!-- ========================================== -->
    <!-- CUSTOM CSS FOR THE BUTTON CARDS            -->
    <!-- ========================================== -->
    <style>
        .dashboard-action-card {
            display: flex;             /* This puts the icon and text side-by-side */
            align-items: center;
            background-color: #bae6fd; /* Light blue background like your reference */
            border-radius: 12px;
            padding: 20px;
            width: 320px;
            text-decoration: none;
            color: #0f172a;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.2s ease; /* Smooth animation when hovered */
            border: 2px solid transparent;
        }
        
        /* Make the card "pop" up when the mouse hovers over it */
        .dashboard-action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.15);
            border-color: #38bdf8;
        }

        /* Style the circle holding the image/icon */
        .card-icon {
            font-size: 3rem; 
            margin-right: 20px;
            background: white;
            border-radius: 50%;
            width: 75px;
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        .card-text {
            font-size: 1.15rem;
            font-weight: bold;
            line-height: 1.3;
        }
    </style>
@endsection