<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    
    // Allow mass assignment for these fields
    protected $fillable = ['team_id_code', 'team_name', 'points', 'user_id'];

    // Relational Link: A Team belongs to the User who created it
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Inside app/Models/Team.php
    public function players() {
        // We explicitly tell Laravel the foreign key is 'team_id'
        return $this->hasMany(Player::class, 'team_id', 'id');
    }
}