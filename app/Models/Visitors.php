<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;



class Visitors extends Model
{
    use HasFactory;

    protected $fillable = [
        'identity',
        'email',
        'nationality',
        'gender',
        'name',
        'event',
        'designation',
        'sector',
        'contact',
        'company',
        'code',
        'dob',
        'city',
        'address',
        'companyType',
        'companyContact',
        'companyWebsite',
        'companyEmail',
        'attandeePMDC',
        'day_1',
        'day_2',
        'day_3',
        'day_4',
        'seminar',
        'badge_print',
        'dupe_badge_print',
    ];

    protected $hidden = [
        'id',
    ];

    protected static function booted()
    {
        static::creating(function ($attandee) {
            $attandee->uid = (string) Str::uuid(); // Generate a UUID
        });

        // static::creating(function ($attandee) {
        //     $attandee->code = 'TVFA' . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        // });
    }

    public function attendances()
    {
        return $this->morphMany(Attendance::class, 'attendee');
    }

    public function eventSessions()
    {
        return $this->belongsToMany(EventSession::class, 'event_session_visitor', 'visitor_id', 'event_session_id')
            ->withTimestamps();
    }

    public function stallsDay1()
    {
        return $this->stalls()->wherePivot('day', 'day_1');
    }

    public function stallsDay2()
    {
        return $this->stalls()->wherePivot('day', 'day_2');
    }

    public function stalls()
    {
        return $this->belongsToMany(
            Stall::class,
            'stall_visitors',
            'visitors_id',
            'stall_id'
        )->withPivot('day')->withTimestamps();
    }
}
