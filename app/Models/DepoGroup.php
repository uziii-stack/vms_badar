<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepoGroup extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->hasOne(HrCategory::class, 'id','depo_category');
    }
}
