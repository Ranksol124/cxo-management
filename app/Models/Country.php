<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
