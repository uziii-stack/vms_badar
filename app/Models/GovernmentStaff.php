<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GovernmentStaff extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
    ];

    protected $fillable = [
        'name',
        'ranks_uid',
        'govt_org_uid',
        'designation',
        'identity',
        'address',
        'contact',
        'invited_by',
        'staff_category',
        'country',
        'city',
        'car_sticker_color',
        'car_sticker_no',
        'invitaion_no',
    ];

    // Optionally, generate UUID automatically when creating a company
    protected static function booted()
    {
        static::creating(function ($govtStaff) {
            $govtStaff->uid = (string) Str::uuid(); // Generate a UUID
            $govtStaff->code = 'GVST' . str_pad(mt_rand(0, 99999999), 6, '0', STR_PAD_LEFT);
        });
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class, 'ranks_uid', 'ranks_uid');
    }

    public function invited()
    {
        return $this->belongsTo(Invitees::class, 'invited_by');
    }

    public function staff_country()
    {
        return $this->belongsTo(Country::class, 'country');
    }
    public function staff_city()
    {
        return $this->belongsTo(Cities::class, 'city');
    }

    public function staff_categories()
    {
        return $this->belongsTo(StaffCategory::class, 'staff_category');
    }

    public function govt_org()
    {
        return $this->belongsTo(GovernmentOrganization::class, 'govt_org_uid', 'uid');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'staff_program', 'staff_id', 'program_id')
            ->withTimestamps();
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupons::class, 'staff_coupon','staff_id', 'coupon_id')
            ->withTimestamps();
    }
}
