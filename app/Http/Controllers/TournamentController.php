<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Team;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiry;

class TournamentController extends Controller
{
    // ==========================================
    // SECTION 1: VIEWING PAGES 
    // ==========================================
    
    // Shows the main dashboard (Now includes Leaderboard & Match Schedule!)
    public function dashboard(Request $request) {
        // 1. Get all teams for the Leaderboard, sorted by highest points
        $teams = Team::orderBy('points', 'desc')->get();

        // 2. Handle the Roster Search function
        $searchResult = null;
        if ($request->has('search_id') && $request->search_id != '') {
            $searchResult = Team::with('players')->where('team_id_code', $request->search_id)->first();
        }

        // 3. Get all the matches for the Schedule Table using the Game model
        $matches = Game::with(['team1', 'team2'])->orderBy('match_time', 'asc')->get();

        return view('dashboard', compact('teams', 'searchResult', 'matches'));
    }

    // Shows the Team Management Hub
    public function storeData() {
        // READ LOGIC: Admins see ALL teams and their players. Normal users see their OWN.
        if (Auth::user()->role === 'admin') {
            $myTeams = Team::with('players')->get();
        } else {
            $myTeams = Team::with('players')->where('user_id', Auth::id())->get();
        }
        
        return view('store_data', compact('myTeams'));
    }

    // ==========================================
    // SECTION 2: CREATE, UPDATE, DELETE TEAMS (Shared CRUD)
    // ==========================================

    // CREATE: Register a new team and players at the same time
    public function storeTeam(Request $request) {
        // STRICT ERROR BLOCKING: Validate everything BEFORE saving
        $request->validate([
            'team_name' => 'required|unique:teams,team_name',       // Must be unique in 'teams' table
            'players.*.student_id' => 'nullable|distinct|unique:players,student_id' // Must be unique in 'players' table
        ], [
            // Custom Error Messages
            'team_name.unique' => 'The Team Name you entered is already in use.',
            'players.*.student_id.unique' => 'One of the Student IDs is already registered to a team.',
            'players.*.student_id.distinct' => 'You entered the exact same Student ID twice in this form.'
        ]);
        
        // Step 1.5: Auto-Generate the Team ID (e.g., KPMIM-001)
        $latestTeam = Team::latest('id')->first();
        $nextNumber = $latestTeam ? $latestTeam->id + 1 : 1;
        $generatedTeamId = 'KPMIM-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Step 2: Save the Team to the Database (Default to 0 points)
        $team = Team::create([
            'team_id_code' => $generatedTeamId,
            'team_name' => $request->team_name,
            'points' => 0, 
            'user_id' => Auth::id()
        ]);

        // Step 3: Loop through and save the initial players
        if ($request->has('players')) {
            foreach ($request->players as $player) {
                if (!empty($player['name']) && !empty($player['student_id'])) {
                    Player::create([
                        'team_id' => $team->id, 
                        'player_name' => $player['name'], 
                        'student_id' => $player['student_id']
                    ]);
                }
            }
        }
        
        // Return with the newly generated ID so the user knows what it is!
        return back()->with('success', 'Team and Roster Created Successfully! Your Team ID is: ' . $generatedTeamId);
    }

    // UPDATE: Change a Team Name or Add Multiple New Players
    public function updateTeamInfo(Request $request) {
        // Step 1: Find the exact team
        $team = Team::where('team_id_code', $request->team_id_code)->first();
        if (!$team) return back()->with('error', 'Team not found.');

        // Step 2: Security Check
        if (Auth::user()->role !== 'admin' && $team->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized! You can only edit your own team.');
        }

        // STRICT ERROR BLOCKING FOR UPDATES
        $request->validate([
            // Only check unique name if they actually typed a new one!
            'new_team_name' => 'nullable|unique:teams,team_name',
            // Check all new student IDs
            'new_players.*.student_id' => 'nullable|distinct|unique:players,student_id'
        ], [
            'new_team_name.unique' => 'That Team Name is already taken by someone else.',
            'new_players.*.student_id.unique' => 'One of the new Student IDs is already registered.',
            'new_players.*.student_id.distinct' => 'You entered the exact same Student ID twice in the update form.'
        ]);

        // Step 3: If they typed a new name, update it
        if ($request->filled('new_team_name')) {
            $team->update(['team_name' => $request->new_team_name]);
        }

        // Step 4: Loop through and save multiple new players
        if ($request->has('new_players')) {
            foreach ($request->new_players as $player) {
                if (!empty($player['name']) && !empty($player['student_id'])) {
                    Player::create([
                        'team_id' => $team->id,
                        'player_name' => $player['name'],
                        'student_id' => $player['student_id']
                    ]);
                }
            }
        }

        return back()->with('success', 'Team Updated Successfully!');
    }

    // DELETE: Delete an entire team
    public function deleteTeam(Request $request) {
        // Step 1: Find the exact team
        $team = Team::where('team_id_code', $request->team_id_code)->first();
        if (!$team) return back()->with('error', 'Team not found.');
        
        // Step 2: Security Check - Make sure they own the team (unless they are Admin)
        if (Auth::user()->role !== 'admin' && $team->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized! You can only delete your own team.');
        }
        
        // Step 3: Delete it
        $team->delete();
        return back()->with('success', 'Team Deleted Successfully!');
    }


    // ==========================================
    // SECTION 3: ADMIN-ONLY ACTIONS
    // ==========================================

    // CREATE: Schedule a match between two teams
    public function storeMatch(Request $request) {
        if (Auth::user()->role !== 'admin') return back()->with('error', 'Unauthorized!');
        
        $t1 = Team::where('team_id_code', $request->team1_code)->first();
        $t2 = Team::where('team_id_code', $request->team2_code)->first();

        if (!$t1 || !$t2) return back()->with('error', 'Invalid Team IDs.');
        if ($t1->id === $t2->id) return back()->with('error', 'A team cannot play itself!');

        Game::create([
            'team1_id' => $t1->id, 
            'team2_id' => $t2->id, 
            'match_time' => $request->match_time, 
            'status' => 'Upcoming'
        ]);
        
        return back()->with('success', 'Match Scheduled!');
    }

    // UPDATE: Change a team's points directly from the Leaderboard
    public function updateTeamInline(Request $request, $id) {
        if (Auth::user()->role !== 'admin') return back()->with('error', 'Unauthorized!');
        
        Team::findOrFail($id)->update(['points' => $request->points]);
        return back()->with('success', 'Points updated!');
    }

    // DELETE: Remove an entire team directly from the Leaderboard
    public function destroyTeamInline($id) {
        if (Auth::user()->role !== 'admin') return back()->with('error', 'Unauthorized!');
        
        Team::findOrFail($id)->delete();
        return back()->with('success', 'Team deleted!');
    }

    // DELETE: Remove a single player from the Leaderboard Search
    public function destroyPlayer($id) {
        // Find the player and the team they belong to
        $player = Player::findOrFail($id);
        $team = Team::find($player->team_id);
        
        // Security Check: Make sure they own the team (unless they are Admin)
        if (Auth::user()->role !== 'admin' && $team->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized! You can only delete your own players.');
        }
        
        $player->delete();
        return back()->with('success', 'Player removed from roster!');
    }

    // DELETE: Remove a scheduled match from the Leaderboard page
    public function destroyMatch($id) {
        // Security Check: Only Admins can delete matches!
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized! Only Admins can delete matches.');
        }
        
        // Find the specific match (game) and delete it
        Game::findOrFail($id)->delete();
        
        return back()->with('success', 'Match deleted successfully!');
    }

    // ==========================================
    // SECTION 4: SUPPORT & DISPUTE SYSTEM
    // ==========================================

    // Shows the form for Users to submit a ticket
    public function createInquiry() {
        return view('inquiry_form');
    }

    // Saves the User's ticket to the database
    public function storeInquiry(Request $request) {
        $request->validate([
            'category' => 'required',
            'message' => 'required'
        ]);

        Inquiry::create([
            'user_id' => Auth::id(),
            'category' => $request->category,
            'message' => $request->message
        ]);

        return back()->with('success', 'Your ticket has been submitted to the Admin!');
    }

    // Shows the Admin Inbox with all submitted tickets
    public function adminInbox() {
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Unauthorized Access.');
        }

        // Grabs all inquiries and the user info, sorted by newest first
        $inquiries = Inquiry::with('user')->latest()->get();
        return view('admin_inbox', compact('inquiries'));
    }

    // Allows Admins to delete/resolve tickets
    public function destroyInquiry($id) {
        if (Auth::user()->role !== 'admin') return back()->with('error', 'Unauthorized!');
        
        Inquiry::findOrFail($id)->delete();
        return back()->with('success', 'Ticket marked as resolved and deleted!');
    }

    // ==========================================
    // SECTION 5: AI DOCUMENTATION GENERATOR
    // ==========================================
    public function generateDocs() {
        // Fetch the key safely
        $apiKey = trim(env('GEMINI_API_KEY'));
        
        $prompt = "You are an expert technical writer. Write a brief, professional system documentation for a Laravel-based 'Tournament Tracking System'. 
        The system includes: Team Registration, Player Management, Match Scheduling, a Leaderboard, and an Admin vs User role system. 
        Format the response in clean, raw HTML (use <h2>, <ul>, <li>, <p>, and <strong>). Do not include any markdown formatting like ```html.";

        // THE NUCLEAR FIX: I split the URL into Base URL and Path to completely prevent Guzzle scheme errors.
        // I am also using gemini-3.5-flash, which is the current 2026 free-tier model.
        $response = Http::withOptions([
            'verify' => false 
        ])
        ->baseUrl('[https://generativelanguage.googleapis.com](https://generativelanguage.googleapis.com)')
        ->post('/v1beta/models/gemini-3.5-flash:generateContent?key=' . $apiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            $generatedText = $response->json()['candidates'][0]['content']['parts'][0]['text'];
            return view('documentation', ['docs' => $generatedText]);
        }

        // If it fails, print the exact API error to a black screen
        dd($response->json());
    }
}