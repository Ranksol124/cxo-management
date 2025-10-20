<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberContent extends Model
{
    protected $table = 'member_contents';
    protected $fillable = [
        'member_id',
        'title',
        'description',
        'status',
        'content_type',
        'news_type',
    ];

    function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
    function attachments()
    {
        return $this->hasMany(MemberContentAttachment::class, 'member_content_id');
    }
}
