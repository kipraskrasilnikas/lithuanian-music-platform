<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistMood extends Model
{
    use HasFactory;
    protected $table = 'user_moods';
    protected $fillable = ['user_id', 'mood'];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
