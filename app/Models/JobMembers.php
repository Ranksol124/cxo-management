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
}
