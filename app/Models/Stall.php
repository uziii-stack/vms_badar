<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Stall extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    public function visitors()
    {
        return $this->belongsToMany(
            Visitors::class,
            'stall_visitors',
            'stall_id',
            'visitors_id'
        )
            ->withPivot('day')
            ->withTimestamps();
    }
}
