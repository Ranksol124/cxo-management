<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemberFeed extends Model
{
    use HasFactory;

    protected $table = 'members_feed';

    protected $fillable = [
        'content',
        'public',
        'report_reasons',
        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function attachments()
    {
        return $this->hasMany(FeedAttachments::class, 'members_feed_id');
    }


    public function comments()
    {
        return $this->hasMany(FeedComments::class, 'members_feed_id')->with('user');
    }


    public function likesAndDislikes()
    {
        return $this->hasOne(FeedLikesAndDislikes::class, 'members_feed_id');
    }


    public function meta()
    {
        return $this->hasOne(FeedShare::class, 'members_feed_id');
    }
}
