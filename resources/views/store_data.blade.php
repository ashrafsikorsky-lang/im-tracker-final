@extends('layouts.app')

@section('content')
    <div class="content">
        <h2>Team Management Hub</h2>

        <!-- Manual Session Messages -->
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-error">{{ session('error') }}</div> @endif

        <!-- NEW: Automatic Validation Error Messages -->
        @if($errors->any())
            <div class="alert alert-error" style="background-color: #fee2e2; color: #991b1b; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 5px solid #dc2626;">
                <strong style="display: block; margin-bottom: 5px;">Wait! Please fix the following errors:</strong>
                <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- CREATE: REGISTER TEAM & INITIAL ROSTER     -->
        <!-- ========================================== -->
        <div class="card" style="background-color: #f0f9ff; border-left: 5px solid #0284c7;">
            <h3 style="color: #0284c7;">1. Register New Team</h3>
            <form method="POST" action="{{ url('/store-team') }}">
                @csrf
                <label>Team ID (Primary Key):</label>
                <input type="text" name="team_id_code" placeholder="e.g., T001" required>
                
                <label>Team Name:</label>
                <input type="text" name="team_name" placeholder="e.g., Cyber Strikers" required>
                
                <label>Starting Players (Optional):</label>
                <div id="player-rows">
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <input type="text" name="players[0][name]" placeholder="Player Name" style="margin: 0;">
                        <input type="text" name="players[0][student_id]" placeholder="Student ID" style="margin: 0;">
                    </div>
                </div>

                <button type="button" onclick="addPlayerRow()" class="btn" style="background-color: #64748b; margin-bottom: 15px; width: auto; font-size: 0.85rem; padding: 8px 12px;">+ Add Another Player Box</button>
                <button type="submit" class="btn" style="background-color: #0284c7;">Create Team & Roster</button>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- UPDATE: MODIFY EXISTING TEAM               -->
        <!-- ========================================== -->
        <div class="card" style="background-color: #f0fdf4; border-left: 5px solid #16a34a;">
            <h3 style="color: #16a34a;">2. Update Existing Team</h3>
            <form method="POST" action="{{ url('/update-team-info') }}">
                @csrf
                <label>Select Team to Update:</label>
                <select name="team_id_code" required>
                    <option value="">-- Choose Your Team --</option>
                    @foreach($myTeams as $team)
                        <option value="{{ $team->team_id_code }}">{{ $team->team_id_code }} - {{ $team->team_name }}</option>
                    @endforeach
                </select>

                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 15px;">Fill out whichever fields you want to update, leave the rest blank.</p>
                
                <label>Change Team Name To:</label>
                <input type="text" name="new_team_name" placeholder="New Team Name">
                
                <label>Add New Player(s) to Roster:</label>
                <!-- This container holds the dynamic UPDATE rows -->
                <div id="update-player-rows">
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <input type="text" name="new_players[0][name]" placeholder="Player Name" style="margin: 0;">
                        <input type="text" name="new_players[0][student_id]" placeholder="Student ID" style="margin: 0;">
                    </div>
                </div>

                <!-- Javascript Button for Updates -->
                <button type="button" onclick="addUpdatePlayerRow()" class="btn" style="background-color: #64748b; margin-bottom: 15px; width: auto; font-size: 0.85rem; padding: 8px 12px;">+ Add Another Player Box</button>
                <br>
                <button type="submit" class="btn" style="background-color: #16a34a;">Submit Updates</button>
            </form>
        </div>

        <!-- ========================================== -->
        <!-- SCHEDULE MATCH (Admin Only)                -->
        <!-- ========================================== -->
        @if(Auth::user()->role === 'admin')
        <div class="card" style="background-color: #f5f3ff; border-left: 5px solid #8b5cf6;">
            <h3 style="color: #8b5cf6;">3. Schedule Match (Admin Only)</h3>
            <form method="POST" action="{{ url('/store-match') }}">
                @csrf
                <label>Team 1:</label>
                <select name="team1_code" required>
                    <option value="">-- Select Team 1 --</option>
                    @foreach($myTeams as $team)
                        <option value="{{ $team->team_id_code }}">{{ $team->team_id_code }} - {{ $team->team_name }}</option>
                    @endforeach
                </select>

                <label>Team 2:</label>
                <select name="team2_code" required>
                    <option value="">-- Select Team 2 --</option>
                    @foreach($myTeams as $team)
                        <option value="{{ $team->team_id_code }}">{{ $team->team_id_code }} - {{ $team->team_name }}</option>
                    @endforeach
                </select>

                <label>Match Date & Time:</label><input type="datetime-local" name="match_time" required>
                <button type="submit" class="btn" style="background-color: #8b5cf6;">Add to Schedule</button>
            </form>
        </div>
        @endif

        <script>
            // 1. Script for CREATE form
            let playerIndex = 1; 
            function addPlayerRow() {
                const container = document.getElementById('player-rows');
                const row = document.createElement('div');
                row.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px;';
                row.innerHTML = `
                    <input type="text" name="players[${playerIndex}][name]" placeholder="Player Name" style="margin: 0;">
                    <input type="text" name="players[${playerIndex}][student_id]" placeholder="Student ID" style="margin: 0;">
                `;
                container.appendChild(row);
                playerIndex++;
            }

            // 2. Script for UPDATE form (NEW!)
            let updatePlayerIndex = 1; 
            function addUpdatePlayerRow() {
                const container = document.getElementById('update-player-rows');
                const row = document.createElement('div');
                row.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px;';
                row.innerHTML = `
                    <input type="text" name="new_players[${updatePlayerIndex}][name]" placeholder="Player Name" style="margin: 0;">
                    <input type="text" name="new_players[${updatePlayerIndex}][student_id]" placeholder="Student ID" style="margin: 0;">
                `;
                container.appendChild(row);
                updatePlayerIndex++;
            }
        </script>
    </div>
@endsection