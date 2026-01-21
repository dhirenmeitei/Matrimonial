@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- CREATE POST --}}
        <div class="card mb-4 shadow-sm rounded-3">
            <div class="card-body">

                <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="d-flex gap-3">
                        {{-- USER PHOTO --}}
                        <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('img/default-user.png') }}"
                            class="rounded-circle" width="45" height="45" style="object-fit:cover">

                        {{-- TEXTAREA --}}
                        <textarea name="content" class="form-control border-0 shadow-none" rows="2"
                            placeholder="What's on your mind, {{ auth()->user()->username }}?"></textarea>
                    </div>

                    <hr>

                    {{-- ACTIONS --}}
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- FILE UPLOAD --}}
                        <label class="btn btn-light d-flex align-items-center gap-2 mb-0">
                            <i class="bi bi-image text-success fs-5"></i>
                            <span class="fw-semibold">Photo</span>
                            <input type="file" name="photo" id="photoInput" hidden accept="image/*">
                        </label>

                        {{-- POST BUTTON --}}
                        <button class="btn btn-primary px-4">
                            Post
                        </button>
                    </div>

                    {{-- IMAGE PREVIEW --}}
                    <div id="photoPreviewWrapper" class="mt-3 d-none">
                        <img id="photoPreview" class="img-fluid rounded" style="max-height:200px;">
                    </div>

                </form>
            </div>
        </div>

        {{-- POSTS --}}
        @foreach($posts as $post)
            <div class="card mb-4 shadow-sm rounded-3">
                {{-- HEADER --}}
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-0">
                    <div class="d-flex gap-3 align-items-center">
                        {{-- USER PHOTO & NAME --}}
                        <img src="{{ $post->user->photo ? asset('storage/' . $post->user->photo) : asset('img/default-user.png') }}"
                            class="rounded-circle" width="45" height="45" style="object-fit:cover">
                        <div>
                            <a href="{{ route('profile.viewprofile', $post->user->id) }}"
                                class="fw-semibold text-dark text-decoration-none">
                                {{ $post->user->username }}
                            </a>
                            <div class="text-muted small">
                                {{ $post->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 align-items-center">
                        {{-- DELETE / FOLLOW BUTTONS --}}
                        @if(auth()->id() === $post->user_id)
                            <form method="POST" action="{{ route('post.destroy', $post->id) }}"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm">❌</button>
                            </form>
                        @else
                            @php
                                $status = auth()->user()->followStatus($post->user->id);
                                $pendingRequest = \App\Models\Follow::where('follower_id', $post->user->id)
                                    ->where('following_id', auth()->id())
                                    ->where('status', 'pending')
                                    ->first();
                            @endphp

                            @if($pendingRequest)
                                <form method="POST" action="{{ route('follow.accept', $pendingRequest->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Accept</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('follow.toggle', $post->user->id) }}">
                                    @csrf
                                    @if($status === 'accepted')
                                        <button class="btn btn-sm btn-danger">Unfollow</button>
                                    @elseif($status === 'pending')
                                        <button class="btn btn-sm btn-secondary" disabled>Requested</button>
                                    @else
                                        <button class="btn btn-sm btn-outline-primary">Follow</button>
                                    @endif
                                </form>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- BODY --}}
                <div class="card-body pb-2">
                    <a href="{{ route('post.show', $post->id) }}">
                        {{-- POST CONTENT --}}
                        @if($post->content)
                            <p class="mb-2">{{ $post->content }}</p>
                        @endif

                        {{-- POST PHOTO --}}
                        @if($post->photo)
                            <img src="{{ asset('storage/' . $post->photo) }}" class="img-fluid rounded mb-2">
                        @endif
                    </a>

                    {{-- ACTIONS --}}
                    @php $liked = $post->likes->contains('user_id', auth()->id()); @endphp
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <i class="bi {{ $liked ? 'bi-heart-fill text-danger' : 'bi-heart' }} fs-5 like-icon"
                            data-post-id="{{ $post->id }}" style="cursor:pointer;"></i>
                        <span class="like-count" data-post-id="{{ $post->id }}">{{ $post->likes->count() }}</span>

                        <a href="{{ route('post.show', $post->id) }}">
                            <i class="bi bi-chat fs-5"></i>
                            <span>{{ $post->comments->count() }}</span>
                        </a>
                    </div>

                    {{-- LAST COMMENT --}}
                    @php $lastComment = $post->comments->last(); @endphp
                    @if($lastComment)
                        <div class="mb-2">
                            <strong>
                                <a href="{{ route('profile.viewprofile', $lastComment->user->id) }}" class="text-decoration-none">
                                    {{ $lastComment->user->username }}
                                </a>
                            </strong>
                            {{ $lastComment->comment }}
                            <div class="text-muted small">{{ $lastComment->created_at->diffForHumans() }}</div>
                        </div>
                    @endif
                </div>

            </div>

            {{-- LIKES MODAL --}}
            <div class="modal fade" id="likesModal{{ $post->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Liked by</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @if($post->likes->isEmpty())
                                <p class="text-muted">No likes yet</p>
                            @else
                                <ul class="list-group">
                                    @foreach($post->likes as $like)
                                        <li class="list-group-item">
                                            <a href="{{ route('profile.viewprofile', $like->user->id) }}">
                                                {{ $like->user->username }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    </div>

    {{-- JS for image preview --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('photoInput');
            const previewWrapper = document.getElementById('photoPreviewWrapper');
            const previewImage = document.getElementById('photoPreview');

            input.addEventListener('change', function () {
                if (!this.files || !this.files[0]) return;
                const file = this.files[0];
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewWrapper.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            });
        });
    </script>

    {{-- JS for like icon --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.like-icon').forEach(icon => {
                icon.addEventListener('click', function () {
                    const postId = this.dataset.postId;
                    const iconEl = this;
                    const countEl = document.querySelector(`.like-count[data-post-id="${postId}"]`);

                    axios.post(`/post/${postId}/like`, {}, {
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                        .then(res => {
                            if (res.data.liked) {
                                iconEl.classList.remove('bi-heart');
                                iconEl.classList.add('bi-heart-fill', 'text-danger');
                            } else {
                                iconEl.classList.remove('bi-heart-fill', 'text-danger');
                                iconEl.classList.add('bi-heart');
                            }
                            countEl.textContent = res.data.count;
                        });
                });
            });
        });
    </script>

    {{-- Styles --}}
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

            .post-username {
                font-weight: 600;
            }

            .post-time {
                font-size: 0.8rem;
                color: #6c757d;
            }
        </style>
    @endpush

@endsection