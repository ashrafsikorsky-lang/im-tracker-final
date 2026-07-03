<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Team;

class TeamManagementTest extends TestCase
{
    // CRITICAL: This trait resets the temporary database after every single test!
    use RefreshDatabase; 

    /**
     * TEST 1: Verify a user can successfully create a team
     */
    public function test_user_can_create_a_team()
    {
        // 1. Setup: Create a fake user and log them in
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2. Action: Simulate filling out the HTML form and submitting it
        $response = $this->post('/store-team', [
            'team_id_code' => 'T999',
            'team_name' => 'Cyber Strikers',
        ]);

        // 3. Assert (Check): Did the page redirect back with a success message?
        $response->assertSessionHas('success', 'Team and Roster Created Successfully!');

        // 4. Assert: Did the data actually save into the database?
        $this->assertDatabaseHas('teams', [
            'team_id_code' => 'T999',
            'team_name' => 'Cyber Strikers',
            'points' => 0,
            'user_id' => $user->id,
        ]);
    }

    /**
     * TEST 2: Verify the system blocks duplicate Team IDs
     */
    public function test_system_blocks_duplicate_team_ids()
    {
        // 1. Setup: Log in
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2. Setup: Manually insert a team into the database
        Team::create([
            'team_id_code' => 'T001',
            'team_name' => 'Original Team',
            'points' => 0,
            'user_id' => $user->id,
        ]);

        // 3. Action: Try to submit the form using the EXACT SAME team_id_code
        $response = $this->post('/store-team', [
            'team_id_code' => 'T001', // This should trigger the error!
            'team_name' => 'Copycat Team',
        ]);

        // 4. Assert: Verify that Laravel threw a validation error for 'team_id_code'
        $response->assertSessionHasErrors('team_id_code');
        
        // 5. Assert: Verify the fake team did NOT save to the database
        $this->assertDatabaseMissing('teams', [
            'team_name' => 'Copycat Team',
        ]);
    }
}