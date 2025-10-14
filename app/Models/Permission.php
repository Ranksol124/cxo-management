<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'guard_name',
    ];

    protected $attributes = [
        'guard_name' => 'web', // ✅ default for Filament
    ];
}
