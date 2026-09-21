<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // protected $hidden = [
    //     'id',
    // ];

    // Optionally, generate UUID automatically when creating a company
    protected static function booted()
    {
        static::creating(function ($group) {
            $group->group_uid = (string) Str::uuid(); // Generate a UUID
        });
    }

    public function governmentOrganizations()
    {
        return $this->hasMany(GovernmentOrganization::class);
    }
}
