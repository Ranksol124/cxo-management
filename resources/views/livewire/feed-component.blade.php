<div>
    @livewireStyles



    <div class="rounded-md bg-white w-[1124px] shadow-sm space-y-2 p-4">

        <div class="flex items-center justify-between border-b-2 py-3 px-2">
            <div class="text-sm font-semibold text-gray-700">{{ $feed->user->name ?? 'Anonymous' }}</div>
            <div class="text-xs text-gray-500">{{ $feed->created_at->format('jS M Y, h:i A') }}</div>
        </div>

        <div class="text-gray-800 text-sm text-left p-2 whitespace-pre-line">
            {!! $feed->content !!}
            <div class="flex items-center justify-start space-x-6 text-sm text-gray-600 pt-2 mt-2">
                <span>{{ $feed->likes ?? 0 }} Likes</span>
                <span>{{ $feed->feed_comments ? count(json_decode($feed->feed_comments, true)) : 0 }} Comments</span>
            </div>
        </div>

        <div class="flex items-center justify-between space-x-6 text-sm text-gray-600 border-t pt-2 mt-2">
            <button wire:click="like" class="text-blue-600 cursor-pointer">Like</button>
            <button wire:click="dislike" class="hover:text-blue-600 cursor-pointer">Dislike</button>
            <button wire:click="toggleComments" class="hover:text-blue-600 cursor-pointer">Comment</button>
            <button wire:click="toggleShare" class="hover:text-gray-800 cursor-pointer">Share</button>
        </div>

        @if($showComments)
            <div class="mt-4 space-y-3">
                <textarea wire:model.defer="newComment" rows="3" class="w-full border rounded-md p-2 text-sm"
                    placeholder="Write a comment..."></textarea>

                <button wire:click="submitComment"
                    class="mt-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 cursor-pointer">
                    Post Comment
                </button>

                <div>
                    @if($feed->feed_comments)
                        @foreach(json_decode($feed->feed_comments, true) as $comment)
                            <div class="mt-3 text-sm bg-gray-50 p-2 rounded-md">
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>{{ $comment['user_name'] ?? 'User' }}</span>
                                    <span>{{ \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() }}</span>
                                </div>
                                <div class="mt-1 text-gray-800">{{ $comment['content'] }}</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endif

        @if($showShare)
            <div class="absolute bg-white shadow-md rounded p-2 mt-1 right-0 z-10">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">LinkedIn</a>
            </div>
        @endif
    </div>


    @livewireScripts
</div>
