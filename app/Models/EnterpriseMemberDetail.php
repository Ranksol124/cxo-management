<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnterpriseMemberDetail extends Model
{
    protected $fillable = [
        'member_id',
        'organization',
        'organization_business',
        'organization_contact',
        'second_member_name',
        'second_member_contact',
        'second_member_designation',
        'second_member_email',
        'organization_status',
        'number_of_employees',
        'payment_term',
        'mailing_address',
        'expectation',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
