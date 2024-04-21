<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongMood extends Model
{
    use HasFactory;

    protected $fillable = [
        'mood',
    ];

    /**
     * Get the song that owns the genre.
     */
    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}
