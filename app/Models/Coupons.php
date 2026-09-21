<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Coupons extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_day',
        'coupon_name',
        'coupon_validity_start_time',
        'coupon_validity_end_time',
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
        static::creating(function ($coupon) {
            $coupon->coupon_uid = (string) Str::uuid(); // Generate a UUID
        });
    }

    public function staff()
    {
        return $this->belongsToMany(GovernmentStaff::class, 'staff_coupon', 'coupon_id', 'staff_id')
            ->withTimestamps();
    }
}
