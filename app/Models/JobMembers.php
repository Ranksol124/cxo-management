<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobMembers extends Model
{
    protected $table = 'job_members';

    protected $fillable = [
        'members_id',
        'jobs_id',
        'name',
        'current_address',
        'education',
        'experience',
        'cover_letter',
        'cv_upload',
    ];


    public function member()
    {
        return $this->belongsTo(\App\Models\User::class, 'members_id');
    }


    public function job()
    {
        return $this->belongsTo(JobPost::class, 'jobs_id');
    }
}
