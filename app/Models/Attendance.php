<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'attendee_id',
        'attendee_type',
        'event_id',
        'attendance_date',
        'present',
        'checked_in_at',
    ];

    public function attendee()
    {
        return $this->morphTo();  // this connects to any attendee model dynamically
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
