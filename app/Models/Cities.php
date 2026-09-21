<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function GovernmentStaff()
    {
        return $this->hasMany(GovernmentStaff::class, 'city');
    }
    public function GovernmentOrganisation()
    {
        return $this->hasMany(GovernmentOrganization::class, 'country');
    }
}
