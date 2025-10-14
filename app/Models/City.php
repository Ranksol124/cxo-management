<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
