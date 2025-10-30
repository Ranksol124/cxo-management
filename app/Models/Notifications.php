<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'admin_notifications';

    protected $fillable = [
        'template_name',
        'role_id',
        'options',
        'Title',
        'tags',
        'description',
    ];

    protected $casts = [
        'options' => 'array',
    ];


}
