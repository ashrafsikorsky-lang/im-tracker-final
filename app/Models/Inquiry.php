<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category', 'message'];

    // This lets us grab the name of the user who submitted the form!
    public function user() {
        return $this->belongsTo(User::class);
    }
}