<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function locations() {
        return $this->hasMany(UserLocation::class);
    }

    public function genres() {
        return $this->hasMany(UserGenre::class);
    }

    public function specialties() {
        return $this->hasMany(UserSpecialty::class);
    }

    public function resources() {
        return $this->hasMany(Resource::class);
    }

    /**
     * Get the artist moods associated with the user.
     */
    public function moods()
    {
        return $this->hasMany(UserMood::class);
    }

    public function songs() {
        return $this->hasMany(Song::class);
    }
}
