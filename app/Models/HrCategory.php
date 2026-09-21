<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrCategory extends Model
{
    use HasFactory;

    public function depo()
    {
        return $this->belongsTo(DepoGroup::class, 'depo_category', 'id');
    }
}
