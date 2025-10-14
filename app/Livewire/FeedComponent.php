<?php

namespace App\Livewire;
use App\Models\MemberFeed;

use Livewire\Component;

class FeedComponent extends Component
{
    public MemberFeed $feed;
    public bool $showComments = false;
    public bool $showShare = false;
    public string $newComment = '';

    protected $rules = [
        'newComment' => 'required|string|max:500',
    ];

    public function like()
    {
        $this->feed->increment('likes');
        $this->feed->refresh();
    }

    public function dislike()
    {
        if ($this->feed->likes > 0) {
            $this->feed->decrement('likes');
            $this->feed->refresh();
        }
    }

    public function toggleComments()
    {
        $this->showComments = !$this->showComments;
    }

    public function toggleShare()
    {
        $this->showShare = !$this->showShare;
    }

    public function submitComment()
    {
        $this->validate();

        $comments = $this->feed->feed_comments
            ? json_decode($this->feed->feed_comments, true)
            : [];

        $comments[] = [
            'user_name' => auth()->user()->name ?? 'User',
            'content' => $this->newComment,
            'created_at' => now()->toDateTimeString(),
        ];

        $this->feed->feed_comments = json_encode($comments);
        $this->feed->save();

        $this->newComment = '';
        $this->feed->refresh();
    }


    public function render()
    {
        return view('livewire.feed-component');
    }
}
