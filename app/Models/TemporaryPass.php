<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TemporaryPass extends Model
{
    use HasFactory;

    protected $fillable = [
        'identity',
        'organisation',
        'name',
        'code',
        'status',
    ];


    protected static function booted()
    {

        // static::creating(function ($attandee) {
        //     $attandee->code = 'TVFA' . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        // });
    }
}
