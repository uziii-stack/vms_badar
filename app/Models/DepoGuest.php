<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DepoGuest extends Model
{
    use HasFactory;

    protected $table = 'depo_guests'; // optional if you named it differently
    protected $primaryKey = 'uid'; // set your actual primary key here

    public $incrementing = false; // if it's not auto-incrementing
    protected $keyType = 'string'; // or 'int' depending on your key type


    // protected $hidden = ['id'];

    protected $fillable = [
        'uid ',
        'depo_guest_name',
        'depo_guest_rank',
        'depo_guest_designation',
        'depo_guest_contact',
        'depo_guest_service',
        'depo_identity',
        'depo_guest_email',
        'badge_type',
        'depo_uid',
        'depo_address',
        'isPrinted',
    ];

    protected static function booted()
    {
        static::creating(function ($depoGuest) {

            // Ensure UUID is generated only if not already provided
            if (empty($depoGuest->uid)) {
                $depoGuest->uid = (string) Str::uuid();
            }

            // Generate badge_type only if not provided
            if (empty($depoGuest->badge_type)) {
                $depoGuest->badge_type = $depoGuest->badge(8, "HR");
            }
        });
    }

    /**
     * Badge generator
     */
    protected function badge($characters, $prefix)
    {
        $possible = '0123456789';
        $code = $prefix;

        for ($i = 0; $i < $characters; $i++) {
            $code .= $possible[mt_rand(0, strlen($possible) - 1)];
        }

        return $code;
    }

    public function attendances()
    {
        return $this->morphMany(Attendance::class, 'attendee');
    }
}
