<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'picture',
        'sponsor_picture',
        'start_date',
        'end_date',
        'event_time',
        'event_location',
        'website',
        'policy_content_1',
        'policy_content_2',
        'show_cnic_on_badge',
        'show_contact_on_badge',
        'status',
        'deleted',
    ];

    // Scope to get not deleted
    public function scopeActive($query)
    {
        return $query->where('deleted', 0);
    }

    protected $casts = [
        'show_cnic_on_badge' => 'boolean',
        'show_contact_on_badge' => 'boolean',
    ];

    // protected $hidden = [
    //     'id',
    // ];

    public function sessions()
    {
        return $this->hasMany(EventSession::class);
    }
}
