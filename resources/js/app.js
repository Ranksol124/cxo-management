import './bootstrap';
document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    window.submitLike = function (feedId) {
        console.log("Trying to like feed:", feedId);
        fetch(`/feed/${feedId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
        })
            .then(res => res.json())
            .then(data => {
                document.getElementById(`like-count-${feedId}`).innerText = `${data.likes} Likes`;
            })
            .catch(error => console.error("Like error:", error));
    };

    window.submitDislike = function (feedId) {
        fetch(`/feed/${feedId}/dislike`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
        })
            .then(res => res.json())
            .then(data => {
                document.getElementById(`like-count-${feedId}`).innerText = `${data.likes} Likes`;
            })
            .catch(error => console.error("Dislike error:", error));
    };

    window.toggleComments = function (feedId) {
        const section = document.getElementById(`comment-section-${feedId}`);
        if (section) section.classList.toggle('hidden');
    };

    window.toggleShare = function (feedId) {
        const dropdown = document.getElementById(`share-dropdown-${feedId}`);
        if (dropdown) dropdown.classList.toggle('hidden');
    };

    window.submitComment = function (feedId) {
        const textarea = document.getElementById(`comment-input-${feedId}`);
        const content = textarea.value.trim();
        if (!content) return;

        fetch(`/feed/${feedId}/comment`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ comment: content }),
        })
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById(`comment-list-${feedId}`);
                const newComment = document.createElement('div');
                newComment.className = 'mt-3 text-sm bg-gray-50 p-2 rounded-md';
                newComment.innerHTML = `
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>${data.user_name ?? 'You'}</span>
                        <span>Just now</span>
                    </div>
                    <div class="mt-1 text-gray-800">${data.content}</div>
                `;
                list.prepend(newComment);
                textarea.value = '';
                const count = document.getElementById(`comment-count-${feedId}`);
                count.innerText = `${data.total_comments} Comments`;
            })
            .catch(error => console.error('Comment Error:', error));
    };
});

