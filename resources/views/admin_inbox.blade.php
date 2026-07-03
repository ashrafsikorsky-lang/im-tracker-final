@extends('layouts.app')

@section('content')
    <div class="content">
        <h2>Admin Inbox (Support Tickets)</h2>

        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

        <div class="card">
            <h3>Pending Inquiries</h3>
            
            @forelse($inquiries as $inquiry)
                <div style="border-left: 4px solid var(--danger); background-color: #f8fafc; padding: 15px; margin-bottom: 15px; border-radius: 5px; display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h4 style="margin: 0 0 5px 0; color: #0f172a;">{{ $inquiry->category }}</h4>
                        <p style="margin: 0 0 10px 0; font-size: 0.85rem; color: #64748b;">
                            Submitted by: <strong>{{ $inquiry->user->name }}</strong> on {{ $inquiry->created_at->format('d M Y, h:i A') }}
                        </p>
                        <p style="margin: 0; color: #334155; line-height: 1.5;">{{ $inquiry->message }}</p>
                    </div>
                    
                    <form action="{{ url('/resolve-inquiry/'.$inquiry->id) }}" method="POST" onsubmit="return confirm('Mark this ticket as resolved?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn" style="background-color: #10b981; padding: 8px 15px; width: auto;">Mark Resolved</button>
                    </form>
                </div>
            @empty
                <p style="text-align: center; color: #64748b;">No pending tickets! Inbox is clear.</p>
            @endforelse
        </div>
    </div>
@endsection