@extends('layouts.app')

@section('content')
    <!-- The White Card Container -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 w-full max-w-sm">
        
        <h2 class="text-blue-600 text-2xl font-bold text-center mb-8 uppercase tracking-wide">SYSTEM LOGIN</h2>
        
        @if ($errors->any())
            <div class="alert alert-error text-sm mb-4">Invalid credentials. Please try again.</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="flex flex-col space-y-4">
            @csrf
            
            <!-- Email Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email Address:</label>
                <input type="email" name="email" required autofocus class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            
            <!-- Password Field -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password:</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
            </div>
            
            <!-- Login Button -->
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md transition shadow-sm mt-2">
                Log In
            </button>
            
            <!-- Register Link -->
            <div class="text-center mt-6">
                <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline font-bold transition">
                    Need an account? Sign up
                </a>
            </div>
        </form>
        
    </div>
@endsection