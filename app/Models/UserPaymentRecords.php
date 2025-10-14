<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPaymentRecords extends Model
{
    protected $table = 'user_payment_records';
    protected $fillable = [
        'member_id',
        'plan_id',
        'response',
        'start_date',
        'end_date'
    ];


    public function Plans()
    {
        return $this->hasOne(\App\Models\Plan::class, 'plan_id');
    }
}
