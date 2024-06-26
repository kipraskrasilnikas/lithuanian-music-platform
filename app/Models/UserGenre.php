<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGenre extends Model
{
    use HasFactory;

    protected $table = 'user_genres';

    protected $fillable = [
        'name',
    ];

    /**
     * Get the user that owns the genre.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
