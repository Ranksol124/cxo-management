<?php

namespace App\Http\Controllers;

use App\Models\MemberFeed;
use App\Models\FeedLikesAndDislikes;
use App\Models\FeedComments;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function like(MemberFeed $feed)
    {
        $userId = auth()->id();

        $likeRecord = FeedLikesAndDislikes::firstOrNew([
            'members_feed_id' => $feed->id,
            'user_id' => $userId
        ]);

        if ($likeRecord->feed_likes) {
            $likeRecord->feed_likes = false;
        } else {
            $likeRecord->feed_likes = true;
            $likeRecord->feed_dislikes = false;
        }
        
        $likeRecord->save();

        $likesCount = FeedLikesAndDislikes::where('members_feed_id', $feed->id)->where('feed_likes', true)->count();

        return response()->json([
            'status' => $likeRecord->feed_likes ? 'liked' : 'unliked',
            'likes' => $likesCount
        ]);
    }

    public function dislike(MemberFeed $feed)
    {
        $userId = auth()->id();

        $likeRecord = FeedLikesAndDislikes::firstOrNew([
            'members_feed_id' => $feed->id,
            'user_id' => $userId
        ]);

        if ($likeRecord->feed_dislikes) {
            $likeRecord->feed_dislikes = false;
        } else {
            $likeRecord->feed_dislikes = true;
            $likeRecord->feed_likes = false;
        }

        $likeRecord->save();

        $likesCount = FeedLikesAndDislikes::where('members_feed_id', $feed->id)->where('feed_likes', true)->count();

        return response()->json([
            'status' => $likeRecord->feed_dislikes ? 'disliked' : 'undisliked',
            'likes' => $likesCount
        ]);
    }

    public function getComments(MemberFeed $feed)
    {
        $comments = FeedComments::where('members_feed_id', $feed->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $payload = $comments->map(function ($c) {
            return [
                'user_name' => $c->user ? $c->user->name : 'Anonymous',
                'content' => $c->feed_comments,
                'created_at' => $c->created_at->format('jS M Y, h:i A')
            ];
        });

        return response()->json([
            'comments' => $payload
        ]);
    }

    public function unpublish($id)
    {
        $feed = MemberFeed::findOrFail($id);
        if($feed->public == 0){

            $feed->public = 1;
        }else{
            $feed->public = 0;
        }
        $feed->save();

        return back()->with('success', 'Post unpublished successfully.');
    }

    public function destroy($id)
    {
        $feed = MemberFeed::findOrFail($id);

        // Optional: check if current user owns the post
        // if ($feed->user_id !== auth()->id()) {
        //     abort(403, 'Unauthorized');
        // }

        $feed->delete();

        return redirect()->back()->with('success', 'Post deleted successfully.');
    }

    public function comment(Request $request, MemberFeed $feed)
    {
        $request->validate([
            'feed_comments' => 'required|string|max:1000',
        ]);

        $comment = FeedComments::create([
            'members_feed_id' => $feed->id,
            'user_id' => auth()->id(),
            'feed_comments' => $request->feed_comments,
        ]);

        $userName = $comment->User->name ?? 'Anonymous';
        $createdAt = $comment->created_at->format('jS M Y, h:i A');

        return response()->json([
            'user_name' => $userName,
            'content' => $comment->feed_comments,
            'created_at' => $createdAt
        ]);
    }
}
