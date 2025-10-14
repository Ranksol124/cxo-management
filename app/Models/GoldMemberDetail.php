<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoldMemberDetail extends Model
{
    protected $fillable = [
        'member_id',
        'organization',
        'organization_status',
        'number_of_employees',
        'gender',
        'qualification',
        // 'annual_membership_fee',
        // 'registration_agreement',
        'payment_timeline',
        'organization_business',
        'expectation',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
