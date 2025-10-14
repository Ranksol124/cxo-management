<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedComments extends Model
{
    protected $table = 'feed_comments';

    protected $fillable = [
        'members_feed_id',
        'user_id',
        'feed_comments',
    ];

    /**
     * Get the feed this attachment belongs to.
     */
    public function feed()
    {
        return $this->belongsTo(MemberFeed::class, 'members_feed_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function Users()
    {
        return $this->belongsTo(MemberFeed::class, 'user_id');
    }
}
