<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;
    protected $table = 'resources';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'address', 'rating', 'type', 'description', 'image', 'email', 'telephone'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
