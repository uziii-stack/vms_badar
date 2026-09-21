<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'event_id',
        'image_1',
        'image_2',
        'image_3',
        'head_content',
        'body_content',
        'foot_content',
        'section_4_content',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Delete images when model is deleted
    protected static function booted()
    {
        static::deleting(function ($template) {
            if ($template->image_1) {
                Storage::disk('public')->delete($template->image_1);
            }
            if ($template->image_2) {
                Storage::disk('public')->delete($template->image_2);
            }
            if ($template->image_3) {
                Storage::disk('public')->delete($template->image_3);
            }
        });
    }
}
