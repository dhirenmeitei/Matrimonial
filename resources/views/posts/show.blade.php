@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="card mb-4 shadow-sm rounded-3">

            {{-- HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3 align-items-center">
                    <img src="{{ $post->user->photo ? asset('storage/' . $post->user->photo) : asset('img/default-user.png') }}"
                        class="rounded-circle" width="45" height="45" style="object-fit:cover">
                    <div>
                        <strong>{{ $post->user->username }}</strong>
                        <div class="text-muted small">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                @if(auth()->id() === $post->user_id)
                    <form method="POST" action="{{ route('post.destroy', $post->id) }}"
                        onsubmit="return confirm('Delete this post?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                @endif
            </div>

            {{-- BODY --}}
            <div class="card-body">

                {{-- POST CONTENT --}}
                @if($post->content)
                    <p>{{ $post->content }}</p>
                @endif

                @if($post->photo)
                    <img src="{{ asset('storage/' . $post->photo) }}" class="img-fluid rounded mb-3">
                @endif

                {{-- LIKE + COMMENT COUNT --}}
                @php $liked = $post->likes->contains('user_id', auth()->id()); @endphp
                <div class="d-flex align-items-center gap-3 mb-3">
                    <i class="bi {{ $liked ? 'bi-heart-fill text-danger' : 'bi-heart' }} fs-5 like-icon"
                        data-post-id="{{ $post->id }}" style="cursor:pointer;"></i>
                    <span class="like-count" data-post-id="{{ $post->id }}">{{ $post->likes->count() }}</span>

                    <i class="bi bi-chat fs-5"></i>
                    <span>{{ $post->comments->count() }}</span>
                </div>

                {{-- ALL COMMENTS --}}
                <div id="comments-wrapper">
                    @foreach($post->comments as $comment)
                        <div class="mb-2 d-flex justify-content-between align-items-start" id="comment-{{ $comment->id }}">
                            <div>
                                <strong>
                                    <a href="{{ route('profile.viewprofile', $comment->user->id) }}"
                                        class="text-decoration-none">
                                        {{ $comment->user->username }}
                                    </a>
                                </strong>
                                {{ $comment->comment }}
                                <div class="text-muted small">{{ $comment->created_at->diffForHumans() }}</div>
                            </div>
                            {{-- DELETE COMMENT BUTTON --}}
                            @if($comment->user_id === auth()->id())
                                <!-- <button class="btn btn-sm text-danger delete-comment" data-comment-id="{{ $comment->id }}">
                                                                                                <i class="bi bi-trash"></i>
                                                                                            </button> -->
                                <button type="button" class="btn btn-sm text-danger delete-comment"
                                    data-comment-id="{{ $comment->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>

                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- ADD COMMENT --}}
                <form id="add-comment-form" method="POST" action="{{ route('post.comment', $post->id) }}"
                    class="mt-2 d-flex gap-2">
                    @csrf
                    <input type="text" name="comment" class="form-control form-control-sm rounded-pill"
                        placeholder="Write a comment…">
                    <button class="btn btn-primary btn-sm rounded-pill">Post</button>
                </form>

            </div>
        </div>

    </div>

    {{-- AJAX FOR LIKE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.like-icon').forEach(icon => {
                const countEl = document.querySelector(`.like-count[data-post-id="${icon.dataset.postId}"]`);

                icon.addEventListener('click', function () {
                    const postId = this.dataset.postId;

                    axios.post(`/post/${postId}/like`, {}, {
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                        .then(res => {
                            if (res.data.liked) {
                                icon.classList.remove('bi-heart');
                                icon.classList.add('bi-heart-fill', 'text-danger');
                            } else {
                                icon.classList.remove('bi-heart-fill', 'text-danger');
                                icon.classList.add('bi-heart');
                            }
                            countEl.textContent = res.data.count;
                        })
                        .catch(err => console.error(err));
                });
            });
        });
    </script>

    {{-- AJAX FOR COMMENT DELETE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function attachDeleteListeners() {
                document.querySelectorAll('.delete-comment').forEach(button => {
                    button.addEventListener('click', function () {
                        if (!confirm('Are you sure you want to delete this comment?')) return;

                        const commentId = this.dataset.commentId;

                        axios.delete(`/comment/${commentId}`, {
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        })
                            .then(res => {
                                if (res.data.success) {
                                    const commentEl = document.getElementById(`comment-${commentId}`);
                                    if (commentEl) commentEl.remove();

                                    // 🔥 UPDATE COMMENT COUNT
                                    const countSpan = document.querySelector('.bi-chat').nextElementSibling;
                                    countSpan.textContent = parseInt(countSpan.textContent) - 1;
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                alert('Something went wrong.');
                            });
                    });
                });
            }

            attachDeleteListeners();

            // AJAX for adding new comments
            const form = document.getElementById('add-comment-form');

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const commentInput = this.querySelector('input[name="comment"]');
                const commentText = commentInput.value.trim();
                if (!commentText) return;

                const postId = '{{ $post->id }}';

                axios.post(`/post/${postId}/comment`, { comment: commentText }, {
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                    .then(res => {
                        if (!res.data.success) return;

                        const wrapper = document.getElementById('comments-wrapper');
                        const comment = res.data.comment;
                        const userId = '{{ auth()->id() }}';

                        const html = `
            <div class="mb-2 d-flex justify-content-between align-items-start" id="comment-${comment.id}">
                <div>
                    <strong>
                        <a href="/profile/${userId}" class="text-decoration-none">
                            ${comment.username}
                        </a>
                    </strong>
                    ${comment.comment}
                    <div class="text-muted small">Just now</div>
                </div>
                <button type="button"
                        class="btn btn-sm text-danger delete-comment"
                        data-comment-id="${comment.id}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>`;

                        // ✅ Add comment to list
                        wrapper.insertAdjacentHTML('beforeend', html);

                        // ✅ Clear input
                        commentInput.value = '';

                        // ✅ Update comment count
                        const countSpan = document.querySelector('.bi-chat')?.nextElementSibling;
                        if (countSpan) {
                            countSpan.textContent = parseInt(countSpan.textContent) + 1;
                        }

                        // ✅ Reattach delete handler for new comment
                        attachDeleteListeners();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Failed to post comment');
                    });
            });

        });
    </script>

    @push('styles')
        <style>
            .card {
                border: none;
                border-radius: 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
                margin-bottom: 1.5rem;
            }

            .card img {
                max-width: 100%;
                object-fit: cover;
                border-radius: 12px;
            }

            .like-icon:hover {
                transform: scale(1.2);
                transition: transform 0.2s;
            }
        </style>
    @endpush

@endsection