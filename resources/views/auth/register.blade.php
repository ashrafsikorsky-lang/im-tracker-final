@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 400px; margin-top: 50px;">
        <h2 style="text-align:center; color: var(--primary);">SYSTEM SIGN UP</h2>
        
        @if ($errors->any())
            <div class="alert alert-error">Please check your inputs (Passwords must be 8+ chars).</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <label>Full Name:</label>
            <input type="text" name="name" required autofocus>
            
            <label>Email Address:</label>
            <input type="email" name="email" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation" required>
            
            <button type="submit" class="btn">Register Account</button>
            
            <div style="text-align:center; margin-top:15px;">
                <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: bold;">Already registered? Log in</a>
            </div>
        </form>
    </div>
@endsection