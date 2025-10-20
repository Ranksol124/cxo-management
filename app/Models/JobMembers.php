<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobMembers extends Model
{
    protected $table = 'job_members';

    protected $fillable = [
        'members_id',
        'jobs_id',
        'cv_upload',
    ];

    /**
     * Get the member associated with this job content.
     */
    public function member()
    {
        return $this->belongsTo(\App\Models\User::class, 'members_id');
    }

    /**
     * Get the job associated with this job content.
     */
    public function job()
    {
        return $this->belongsTo(JobPost::class, 'jobs_id');
    }
}
