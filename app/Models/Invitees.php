<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitees extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ranks_uid',
        'designation',
        'status',
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
        static::creating(function ($invitees) {
            $invitees->uid = (string) Str::uuid(); // Generate a UUID
        });
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class, 'ranks_uid', 'ranks_uid');
    }

    public function GovernmentStaff()
    {
        return $this->hasMany(GovernmentStaff::class, 'id', 'invited_by');
    }
}
