<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSpecialty extends Model
{
    use HasFactory;

    protected $table = "user_specialties";
    protected $fillable = [
        'name',
    ];

    /**
     * Get the user that owns the specialty.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
