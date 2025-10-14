<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedShare extends Model
{
    protected $table = 'feed_share_link';

    protected $fillable = [
        'members_feed_id',
        'meta_title',
        'meta_description',
        'share_link'
    ];

    /**
     * Get the feed this attachment belongs to.
     */
    public function feed()
    {
        return $this->belongsTo(MemberFeed::class, 'members_feed_id');
    }
}
