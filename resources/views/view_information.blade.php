@extends('layouts.app')

@section('content')
    <div class="content">
        <h2>View Leaderboard & Matches</h2>

        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-error">{{ session('error') }}</div> @endif

        <!-- ========================================== -->
        <!-- 1. SEARCH ROSTER (With Remove Player)      -->
        <!-- ========================================== -->
        <div class="card">
            <h3>Search Team ID (View Roster)</h3>
            <form method="GET" action="{{ url('/view-information') }}">
                <input type="text" name="search_id" placeholder="Enter Team ID (e.g., T001)" value="{{ request('search_id') }}">
                <button type="submit" class="btn">Search</button>
            </form>

            @if(isset($searchResult))
                <hr style="margin: 20px 0; border: 0; border-top: 1px solid var(--border);">
                <h4 style="color: var(--primary);">Team: {{ $searchResult->team_name }} ({{ $searchResult->points }} Points)</h4>
                
                @if($searchResult->players && $searchResult->players->count() > 0)
                    <table>
                        <tr>
                            <th>Player Name</th>
                            <th>Student ID</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($searchResult->players as $player)
                            <tr>
                                <td>{{ $player->player_name }}</td>
                                <td>{{ $player->student_id }}</td>
                                <td>
                                    <!-- SECURITY: Only show delete if they are Admin OR own the team -->
                                    @if(Auth::user()->role === 'admin' || $searchResult->user_id === Auth::id())
                                        <form action="{{ url('/delete-player/'.$player->id) }}" method="POST" onsubmit="return confirm('Remove player?');" style="margin: 0;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 4px 10px; width: auto; font-size: 0.8rem;">Remove</button>
                                        </form>
                                    @else
                                        <span style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">View Only</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p>No players added to this roster yet.</p>
                @endif
            @elseif(request()->has('search_id'))
                <div class="alert alert-error" style="margin-top: 15px;">No team found with that ID.</div>
            @endif
        </div>

        <!-- ========================================== -->
        <!-- 2. LEADERBOARD (With Delete Team)          -->
        <!-- ========================================== -->
        <div class="card">
            <h3>Current Rankings</h3>
            <table>
                <tr>
                    <th>Rank</th><th>Team ID</th><th>Team Name</th><th>Points</th><th>Action</th>
                </tr>
                @forelse ($teams as $index => $team)
                    <tr>
                        <td><strong>{{ $index + 1 }}</strong></td>
                        <td style="color: var(--primary); font-weight: bold;">{{ $team->team_id_code }}</td>
                        <td>{{ $team->team_name }}</td>
                        <td style="font-weight: bold; color: var(--success);">{{ $team->points }}</td>
                        
                        <td style="display: flex; gap: 5px;">
                            <!-- ADMIN ONLY: Update Points -->
                            @if(Auth::user()->role === 'admin')
                                <form action="{{ route('teams.update', $team->id) }}" method="POST" style="margin: 0;">
                                    @csrf @method('PUT')
                                    <input type="number" name="points" value="{{ $team->points }}" style="width: 60px; margin: 0; padding: 5px;" required>
                                    <button type="submit" class="btn" style="background-color: #f59e0b; padding: 5px; width: auto;">Update Pts</button>
                                </form>
                            @endif

                            <!-- BOTH ADMIN AND OWNERS: Delete Team -->
                            @if(Auth::user()->role === 'admin' || $team->user_id === Auth::id())
                                <form method="POST" action="{{ url('/delete-team') }}" onsubmit="return confirm('Delete this entire team?');" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="team_id_code" value="{{ $team->team_id_code }}">
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; width: auto; height: 100%;">Delete</button>
                                </form>
                            @else
                                <span style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">View Only</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center;">No teams registered yet.</td></tr>
                @endforelse
            </table>
        </div>

        <!-- ========================================== -->
        <!-- 3. MATCH SCHEDULE                          -->
        <!-- ========================================== -->
        <div class="card">
            <h3>Tournament Schedule</h3>
            <table style="text-align: center;">
                <tr>
                    <th>Match #</th>
                    <th>Team 1</th>
                    <th>vs</th>
                    <th>Team 2</th>
                    <th>Date & Time</th>
                    <!-- Only Admins get to see the Action column -->
                    @if(Auth::user()->role === 'admin')
                        <th>Action</th>
                    @endif
                </tr>
                @forelse ($matches as $index => $match)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="font-weight: bold; color: var(--primary);">{{ $match->team1->team_name ?? 'Unknown Team' }}</td>
                        <td style="color: #94a3b8; font-style: italic;">VS</td>
                        <td style="font-weight: bold; color: var(--danger);">{{ $match->team2->team_name ?? 'Unknown Team' }}</td>
                        <td>{{ \Carbon\Carbon::parse($match->match_time)->format('d M Y, h:i A') }}</td>
                        
                        <!-- Only Admins get the Delete button -->
                        @if(Auth::user()->role === 'admin')
                            <td>
                                <form action="{{ url('/delete-match/'.$match->id) }}" method="POST" onsubmit="return confirm('Delete this scheduled match?');" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 4px 10px; width: auto; font-size: 0.8rem;">Delete</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <!-- Adjust column span dynamically depending on role -->
                    <tr><td colspan="{{ Auth::user()->role === 'admin' ? '6' : '5' }}">No matches scheduled yet.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
@endsection