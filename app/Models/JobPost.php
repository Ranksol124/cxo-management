<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class JobPost extends Model
{
    use Encryptable;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'company',
        'designation',
        'job_type',
        'salary',
        'due_date',
        'link',
        'job_image',
        'website_preview',
        'address',
        'job_status',
    ];

    protected $table = 'job_posts';

    protected function title(): Attribute
    {
        return $this->makeEncryptableAttribute();
    }

    protected function description(): Attribute
    {
        return $this->makeEncryptableAttribute();
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

}
