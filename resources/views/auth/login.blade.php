@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 400px; margin-top: 100px;">
        <h2 style="text-align:center; color: var(--primary);">SYSTEM LOGIN</h2>
        
        @if ($errors->any())
            <div class="alert alert-error">Invalid credentials. Please try again.</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label>Email Address:</label>
            <input type="email" name="email" required autofocus>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <button type="submit" class="btn">Log In</button>
            
            <div style="text-align:center; margin-top:15px;">
                <a href="{{ route('register') }}" style="color: var(--primary); text-decoration: none; font-weight: bold;">Need an account? Sign up</a>
            </div>
        </form>
    </div>
@endsection