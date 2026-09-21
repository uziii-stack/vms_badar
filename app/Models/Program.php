<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_day',
        'program_name',
        'program_start_time',
        'program_end_time',
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
        static::creating(function ($program) {
            $program->program_uid = (string) Str::uuid(); // Generate a UUID
        });
    }

    public function staff()
    {
        return $this->belongsToMany(GovernmentStaff::class, 'staff_program', 'program_id', 'staff_id')
            ->withTimestamps();
    }
}
