<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Rank extends Model
{
    use HasFactory;

    protected $fillable = [
        'ranks_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
    ];

    // Optionally, generate UUID automatically when creating a company
    protected static function booted()
    {
        static::creating(function ($rank) {
            $rank->ranks_uid = (string) Str::uuid(); // Generate a UUID
        });
    }

    public function invitee()
    {
        return $this->hasMany(Invitees::class, 'ranks_uid');
    }

    public function GovernmentStaff()
    {
        return $this->hasMany(GovernmentStaff::class, 'ranks_uid', 'ranks_uid');
    }
}
