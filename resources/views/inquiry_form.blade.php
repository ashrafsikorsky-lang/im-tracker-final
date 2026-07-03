@extends('layouts.app')

@section('content')
    <div class="content" style="max-width: 600px; margin: 0 auto;">
        <h2>Tournament Support & Disputes</h2>
        <p style="text-align: center; color: #64748b; margin-bottom: 20px;">Need help or want to report a match issue? Send a ticket to the Admin.</p>

        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

        <div class="card">
            <form method="POST" action="{{ url('/submit-inquiry') }}">
                @csrf
                <label>Issue Category:</label>
                <select name="category" required>
                    <option value="">-- Select Category --</option>
                    <option value="Score Dispute">Score Dispute</option>
                    <option value="Rule Clarification">Rule Clarification</option>
                    <option value="Team Roster Issue">Team Roster Issue</option>
                    <option value="General Feedback">General Feedback</option>
                </select>

                <label>Message Detail:</label>
                <textarea name="message" rows="5" placeholder="Please describe the issue in detail..." style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 5px; margin-bottom: 15px;" required></textarea>

                <button type="submit" class="btn">Submit Ticket</button>
            </form>
        </div>
    </div>
@endsection