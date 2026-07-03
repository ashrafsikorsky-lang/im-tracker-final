@extends('layouts.app')

@section('content')
    <div class="content">
        <h2>Tournament Schedule</h2>
        <a href="{{ url('/dashboard') }}" style="color: var(--primary); text-decoration: none; font-weight: bold;">← Back to Dashboard</a><br><br>
        
        <div class="card">
            <h3>Upcoming Fixtures</h3>
            <table style="text-align: center;">
                <tr>
                    <th>Match #</th>
                    <th>Team 1</th>
                    <th>vs</th>
                    <th>Team 2</th>
                    <th>Date & Time</th>
                </tr>
                @forelse ($matches as $index => $match)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        
                        <!-- Eloquent Relationships: Grab the team, then grab the name! -->
                        <!-- We use ?? 'Unknown' just in case a team got deleted -->
                        <td style="font-weight: bold; color: var(--primary);">
                            {{ $match->team1->team_name ?? 'Unknown Team' }}
                        </td>
                        
                        <td style="color: #94a3b8; font-style: italic;">VS</td>
                        
                        <td style="font-weight: bold; color: var(--danger);">
                            {{ $match->team2->team_name ?? 'Unknown Team' }}
                        </td>
                        
                        <td>{{ \Carbon\Carbon::parse($match->match_time)->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">No matches scheduled yet.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection