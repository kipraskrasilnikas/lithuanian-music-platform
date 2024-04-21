<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;
    
    protected $table = 'songs';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'song_url'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function genres()
    {
        return $this->hasMany(SongGenre::class);
    }

    public function moods() {
        return $this->hasMany(SongMood::class);
    }
}
