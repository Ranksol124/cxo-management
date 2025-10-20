<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberContentAttachment extends Model
{
    protected $table = 'member_contents_attachments';
    protected $fillable = [
        'member_content_id',
        'file_path',
    ];
    function memberContent(){
        return $this->belongsTo(MemberContent::class, 'member_content_id');
    }
}
