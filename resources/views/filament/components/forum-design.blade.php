<div>
@php
    $userReaction = $getRecord()->likesAndDislikes;
    $userLiked = $userReaction && $userReaction->feed_likes;
    $userDisliked = $userReaction && $userReaction->feed_dislikes;

    $record = $getRecord();
@endphp

@if($getRecord()->public == 1 || (auth()->check() && auth()->id() == $getRecord()->user_id)) 
    <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">Private</span>

    <div x-data="{ showComments: false }" class="rounded-md bg-white w-[1124px] shadow-sm space-y-2 p-4"
        data-id="{{ $getRecord()->id }}" data-liked="{{ $userLiked ? '1' : '0' }}"
        data-disliked="{{ $userDisliked ? '1' : '0' }}">

        <div class="flex items-center w-full justify-between border-b py-3 px-2">
            <div class="flex items-center gap-2">
                <img src="{{ $getRecord()->user->profile_picture_url }}" alt="{{ $getRecord()->user->name ?? 'Anonymous' }}"
                    class="w-8 h-8 rounded-full object-cover">

                <div class="text-sm font-semibold text-gray-700">
                    {{ $getRecord()->user->name ?? 'Anonymous' }}
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500">
                    {{ $getRecord()->created_at->format('jS M Y, h:i A') }}
                </span>

                @if(auth()->check() && auth()->id() == $getRecord()->user_id)
                    <x-filament::dropdown placement="bottom-end">
                        <x-slot name="trigger">
                            <button class="text-gray-500 hover:text-gray-700">⋮</button>
                        </x-slot>

                        <x-filament::dropdown.list>
                            <!-- Edit -->
                            <x-filament::dropdown.list.item 
                                tag="a" 
                                :href="url('/portal/member-feeds/' . $getRecord()->id . '/edit')" 
                                icon="heroicon-o-pencil">
                                Edit
                            </x-filament::dropdown.list.item>

                            <!-- Unpublish -->
                            <x-filament::dropdown.list.item 
                                icon="heroicon-o-x-circle"
                                onclick="event.preventDefault(); document.getElementById('unpublish-form-{{ $getRecord()->id }}').submit();">
                                Unpublish
                            </x-filament::dropdown.list.item>

                            <!-- Delete -->
                            <x-filament::dropdown.list.item 
                                icon="heroicon-o-trash"
                                onclick="if(confirm('Are you sure you want to delete this post?')) { document.getElementById('delete-form-{{ $getRecord()->id }}').submit(); }">
                                Delete
                            </x-filament::dropdown.list.item>
                        </x-filament::dropdown.list>
                    </x-filament::dropdown>

                    <!-- Hidden forms here as before -->
                    <form id="unpublish-form-{{ $getRecord()->id }}" 
                        action="{{ route('member-feeds.unpublish', $getRecord()->id) }}" 
                        method="POST" 
                        class="hidden">
                        @csrf
                        @method('PATCH')
                    </form>

                    <form id="delete-form-{{ $getRecord()->id }}" 
                        action="{{ route('member-feeds.destroy', $getRecord()->id) }}" 
                        method="POST" 
                        class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>

        <div class="text-gray-800 text-sm text-left p-2 w-[1124px] whitespace-pre-line">
            {!! $getRecord()->content !!}
        </div>

        <div>
            @foreach($record->attachments as $file)
                <img src="{{ asset('storage/' . $file->attachment_path) }}" class="w-40 h-40 object-cover" />
            @endforeach
        </div>

        <div class="flex items-center justify-start space-x-6 text-sm text-gray-600 pt-2">
            <span id="likes-count-{{ $getRecord()->id }}">
                <!-- {{ $getRecord()->likesAndDislikes?->feed_likes ?? 0 }} Likes -->
            </span>

            <span>
                {{ $getRecord()->comments->count() }} Comments
            </span>
        </div>

        <div class="flex items-center justify-between border-t">
            <div class="flex items-center justify-left space-x-6 text-sm text-gray-600  pt-2 mt-2">

                <button type="button" class="like-button flex items-center space-x-1 text-gray-600 hover:text-blue-600"
                    data-id="{{ $getRecord()->id }}">
                    <svg class="like-icon w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14 9V5a3 3 0 00-6 0v4H4.5A1.5 1.5 0 003 10.5v6A1.5 1.5 0 004.5 18H9l1.293 3.293A1 1 0 0011.95 22h2.1a2 2 0 001.9-1.415L17.9 12.2A2 2 0 0016.06 10H14z" />
                    </svg>
                    <span>Like</span>
                </button>

                <button type="button" class="dislike-button flex items-center space-x-1 text-gray-600 hover:text-red-600"
                    data-id="{{ $getRecord()->id }}">
                    <svg class="dislike-icon w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10 15v4a3 3 0 006 0v-4h4.5A1.5 1.5 0 0022 13.5v-6A1.5 1.5 0 0020.5 6H15l-1.293-3.293A1 1 0 0012.05 2H9.95a2 2 0 00-1.9 1.415L6.1 11.8A2 2 0 007.94 14H10z" />
                    </svg>
                    <span>Dislike</span>
                </button>

            </div>
            <div>
                <button @click="showComments = !showComments"
                    class="comment-toggle text-sm text-gray-400 hover:text-blue-600" data-id="{{ $getRecord()->id }}">
                    Comment
                </button>
            </div>
        </div>

        <div x-show="showComments" x-cloak class="mt-4 space-y-3 comment-section" id="comments-{{ $getRecord()->id }}">
            <textarea class="comment-input border p-2 w-full mb-2 text-sm" placeholder="Write a comment..."
                data-id="{{ $getRecord()->id }}"></textarea>
            <div class="w-full text-right">
                <button class="post-comment bg-blue-500 text-white px-3 text-right py-1 rounded text-xs hover:bg-blue-600"
                    data-id="{{ $getRecord()->id }}">Post</button>
            </div>

            <div class="comments-loader hidden flex items-center space-x-2 text-sm text-gray-500 mt-2">
                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span>Loading comments...</span>
            </div>

            <div class="comments-list mt-2 text-sm text-gray-700"></div>
        </div>

        <div class="share-section hidden mt-3" id="share-{{ $getRecord()->id }}">
            <input type="text" readonly class="border p-2 w-full text-sm" value="{{ url('/post/' . $getRecord()->id) }}">
        </div>
    </div>
@endif

@once
<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('[data-id]').forEach(el => {
            const liked = el.getAttribute('data-liked') === '1';
            const disliked = el.getAttribute('data-disliked') === '1';

            const likeBtn = el.querySelector('.like-button');
            const dislikeBtn = el.querySelector('.dislike-button');
            const likeIcon = likeBtn?.querySelector('.like-icon');
            const dislikeIcon = dislikeBtn?.querySelector('.dislike-icon');

            if (liked && likeIcon) likeIcon.classList.add('text-blue-600');
            if (disliked && dislikeIcon) dislikeIcon.classList.add('text-red-600');
        });

        document.querySelectorAll(".like-button").forEach(btn => {
            btn.addEventListener("click", () => {
                fetch(`/feed/${btn.dataset.id}/like`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    }
                }).then(res => res.json()).then(data => {
                    const icon = btn.querySelector('.like-icon');
                    const dislikeIcon = btn.parentElement.querySelector('.dislike-icon');

                    if (data.status === 'liked') {
                        icon.classList.add('text-blue-600');
                    } else {
                        icon.classList.remove('text-blue-600');
                    }

                    if (dislikeIcon) dislikeIcon.classList.remove('text-red-600');

                    if (data.likes !== undefined) {
                        document.querySelector(`#likes-count-${btn.dataset.id}`).textContent = `${data.likes} Likes`;
                    }
                });
            });
        });

        document.querySelectorAll(".dislike-button").forEach(btn => {
            btn.addEventListener("click", () => {
                fetch(`/feed/${btn.dataset.id}/dislike`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    }
                }).then(res => res.json()).then(data => {
                    const icon = btn.querySelector('.dislike-icon');
                    const likeIcon = btn.parentElement.querySelector('.like-icon');

                    if (data.status === 'disliked') {
                        icon.classList.add('text-red-600');
                    } else {
                        icon.classList.remove('text-red-600');
                    }

                    if (likeIcon) likeIcon.classList.remove('text-blue-600');

                    if (data.likes !== undefined) {
                        document.querySelector(`#likes-count-${btn.dataset.id}`).textContent = `${data.likes} Likes`;
                    }
                });
            });
        });

        document.querySelectorAll(".comment-toggle").forEach(btn => {
            btn.addEventListener("click", () => {
                const container = document.getElementById(`comments-${btn.dataset.id}`);
                if (!container) return;
                const list = container.querySelector('.comments-list');
                const loader = container.querySelector('.comments-loader');
                if (!list || !loader) return;
                if (container.dataset.loading === '1') return;

                setTimeout(() => {
                    const isHidden = getComputedStyle(container).display === 'none';
                    if (isHidden) return;

                    container.dataset.loading = '1';
                    list.innerHTML = '';
                    loader.classList.remove('hidden');

                    fetch(`/feed/${btn.dataset.id}/comments`, {
                        method: "GET",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }
                    }).then(res => res.json()).then(data => {
                        loader.classList.add('hidden');
                        delete container.dataset.loading;
                        data.comments.forEach(c => {
                            const div = document.createElement("div");
                            div.className = "mb-3 p-2 border rounded";
                            div.innerHTML = `<div class="flex items-center justify-between p-2"><div class="text-sm font-semibold">${c.user_name}</div><div class="text-xs text-gray-500">${c.created_at}</div></div><div class="mt-1 px-5">${c.content}</div>`;
                            list.appendChild(div);
                        });
                    }).catch(() => {
                        loader.classList.add('hidden');
                        delete container.dataset.loading;
                        const errDiv = document.createElement("div");
                        errDiv.className = "text-sm text-red-500";
                        errDiv.textContent = "Failed to load comments.";
                        list.appendChild(errDiv);
                    });
                }, 0);
            });
        });


        document.querySelectorAll(".post-comment").forEach(btn => {
            btn.addEventListener("click", () => {
                const input = document.querySelector(`.comment-input[data-id="${btn.dataset.id}"]`);
                if (input.value.trim() === "") return;

                fetch(`/feed/${btn.dataset.id}/comment`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({ feed_comments: input.value })
                }).then(res => res.json()).then(data => {
                    const list = input.closest(".comment-section").querySelector(".comments-list");
                    const div = document.createElement("div");
                    div.className = "mb-3 mt-5 p-2 border rounded";
                    div.innerHTML = `<div class="flex items-center justify-between p-2"><div class="text-sm font-semibold">${data.user_name}</div><div class="text-xs text-gray-500">${data.created_at}</div></div><div class="mt-1 px-5">${data.content}</div>`;
                    list.insertBefore(div, list.firstChild);
                    input.value = "";
                }).catch(err => {
                    console.error(err);
                });
            });
        });

        document.querySelectorAll(".share-toggle").forEach(btn => {
            btn.addEventListener("click", () => {
                const section = document.getElementById("share-" + btn.dataset.id);
                section.classList.toggle("hidden");
            });
        });
    });


 
</script>
@endonce
</div>
