<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'discount_price',
        'currency',
        'trial_days',
        'max_users',
        'max_projects',
        'description',
        'image',
        'interval',
        'interval_count',
        'is_active',
        'is_featured'
    ];

    public function PlanFeatures()
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function members()
    {
        return $this->hasMany(\App\Models\Member::class, 'plan_id');
    }

    public function roles()
    {
        return $this->belongsToMany(\Spatie\Permission\Models\Role::class, 'plan_role');
    }


    
    public function UserPaymentRecords()
    {
        return $this->hasMany(\App\Models\UserPaymentRecords::class, 'plan_id');
    }
}
