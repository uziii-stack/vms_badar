<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GovernmentOrganization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'country',
        'city',
        'code',
        'group',
        'ref_no',
        'allowed_quantity',
        'status',
        'head_name',
        'head_email',
        'head_contact',
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
        static::creating(function ($govtOrg) {
            $govtOrg->uid = (string) Str::uuid(); // Generate a UUID
        });

        static::creating(function ($govtOrg) {
            $govtOrg->code = 'GV' . str_pad(mt_rand(0, 99999999), 6, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Relationship: GovernmentOrganization belongs to a group.
     */
    public function govtOrgGroup()
    {
        return $this->belongsTo(Group::class, 'group');
    }

    public function govtStaff()
    {

        return $this->hasMany(GovernmentStaff::class, 'govt_org_uid', 'uid');
    }

    public function govtOrgCity()
    {
        return $this->belongsTo(Cities::class, 'city');
    }
    public function govtOrgCountry()
    {
        return $this->belongsTo(Country::class, 'country');
    }
}
