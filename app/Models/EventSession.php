<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'description',
        'paid',
        'amount',
    ];

    protected $casts = [
        'paid' => 'boolean',
        'amount' => 'decimal:4',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function visitors()
    {
        return $this->belongsToMany(Visitors::class, 'event_session_visitor', 'event_session_id', 'visitor_id')
            ->withTimestamps();
    }
}
