<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedAttachments extends Model
{
    use HasFactory;

    protected $table = 'feed_attachments';

    protected $fillable = [
        'members_feed_id',
        'attachment_path',
    ];

    /**
     * Get the feed this attachment belongs to.
     */
    public function feed()
    {
        return $this->belongsTo(MemberFeed::class, 'members_feed_id');
    }
}

