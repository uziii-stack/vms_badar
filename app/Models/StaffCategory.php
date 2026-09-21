<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function GovernmentStaff()
    {
        return $this->hasMany(GovernmentStaff::class, 'id', 'staff_category');
    }
}
