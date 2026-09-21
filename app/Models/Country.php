<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function GovernmentStaff()
    {
        return $this->hasMany(GovernmentStaff::class, 'country');
    }
    public function GovernmentOrganisation()
    {
        return $this->hasMany(GovernmentOrganization::class, 'country');
    }
}
