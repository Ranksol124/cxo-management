<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedLikesAndDislikes extends Model
{
    protected $table = 'feed_likes_and_dislikes';

    protected $fillable = [
        'members_feed_id',
        'user_id',
        'feed_likes',
        'feed_dislikes'
    ];

    /**
     * Get the feed this attachment belongs to.
     */
    public function feed()
    {
        return $this->belongsTo(MemberFeed::class, 'members_feed_id');
    }
    
}
