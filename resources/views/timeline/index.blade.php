@extends('layouts.app')

@section('content')
<div class="container">

    {{-- CREATE POST --}}
   {{-- CREATE POST --}}
<div class="card mb-4">
    <div class="card-body">

        <form method="POST"
              action="{{ route('post.store') }}"
              enctype="multipart/form-data">
            @csrf

            <div class="d-flex gap-3">

                {{-- USER PHOTO --}}
                <img
                    src="{{ auth()->user()->photo
                        ? asset('storage/'.auth()->user()->photo)
                        : asset('img/default-user.png') }}"
                    class="rounded-circle"
                    width="45"
                    height="45"
                    style="object-fit:cover"
                >

                {{-- TEXTAREA --}}
                <textarea
                    name="content"
                    class="form-control border-0 shadow-none"
                    rows="2"
                    placeholder="What's on your mind, {{ auth()->user()->username }}?"
                ></textarea>

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
    <div class="card mb-4">

        {{-- HEADER --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>
                <a href="{{ route('profile.viewprofile', $post->user->id) }}" class="text-decoration-none">
                    {{ $post->user->username }}
                </a>
            </strong>

            <div class="d-flex gap-2 align-items-center">

                {{-- DELETE BUTTON (ONLY OWNER) --}}
                @if(auth()->id() === $post->user_id)
                <form method="POST" action="{{ route('post.destroy', $post->id) }}"
                    onsubmit="return confirm('Are you sure you want to delete this post?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm">❌</button>

                </form>
                @else
                {{-- FOLLOW / ACCEPT --}}
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
        <div class="card-body">
            <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none">
            @if($post->content)
            <p class="text-dark">{{ $post->content }}</p>
            @endif

            @if($post->photo)
            <img src="{{ asset('storage/'.$post->photo) }}" class="img-fluid mb-2 rounded">
            @endif
            </a>
            {{-- LIKE --}}
            @php
            $liked = $post->likes->contains('user_id', auth()->id());
            @endphp
            <br>
<!-- like section -->
            <!-- <form method="POST" action="{{ route('post.like', $post->id) }}" class="d-inline">
                @csrf
                <button class="btn btn-link fs-4 {{ $liked ? 'text-danger' : 'text-secondary' }}">❤️</button>
            </form>

            {{-- LIKE COUNT --}}
            <span class="text-primary ms-1" style="cursor:pointer"
                data-bs-toggle="modal" data-bs-target="#likesModal{{ $post->id }}">
                {{ $post->likes->count() }} likes
            </span> -->
            <button
    type="button"
    class="btn btn-link fs-4 like-btn"
    data-post-id="{{ $post->id }}"
    aria-label="Like">

    <i class="bi {{ $liked ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>

</button>

<span
    class="text-primary ms-1 like-count"
    data-post-id="{{ $post->id }}"
    style="cursor:pointer"
    data-bs-toggle="modal"
    data-bs-target="#likesModal{{ $post->id }}">
    {{ $post->likes->count() }} likes
</span>


            {{-- COMMENTS --}}
            <hr>
            <hr>
            <!-- show last comment -->
             @php
    $comment = $post->comments->last();
@endphp

@if($comment)
<div class="d-flex justify-content-between align-items-start mb-2">

    <div>
        <strong>
            <a href="{{ route('profile.viewprofile', $comment->user->id) }}"
               class="text-decoration-none">
                {{ $comment->user->username }}
            </a>
        </strong>
        {{ $comment->comment }}
        <br>
        <small class="text-muted">
            {{ $comment->created_at }}
        </small>
    </div>

    {{-- DELETE COMMENT (ONLY OWNER) --}}
    @if(auth()->id() === $comment->user_id)
    <form method="POST"
          action="{{ route('comment.delete', $comment->id) }}"
          onsubmit="return confirm('Delete this comment?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-xl btn-link text-danger p-0">
            <i class="bi bi-trash"></i>
        </button>
    </form>
    @endif

</div>
@endif

<!-- full comments section -->
            <!-- @foreach($post->comments as $comment)
            <div class="d-flex justify-content-between align-items-start mb-2">

                <div>
                    <strong>
                        <a href="{{ route('profile.viewprofile', $comment->user->id) }}"
                            class="text-decoration-none">
                            {{ $comment->user->username }}
                        </a>
                    </strong>
                    {{ $comment->comment }}
                    <br>
                    <small class="text-muted">
                        {{ $comment->created_at ?? '' }}
                    </small>
                </div>

                {{-- DELETE COMMENT (ONLY OWNER) --}}
                @if(auth()->id() === $comment->user_id)
                <form method="POST"
                    action="{{ route('comment.delete', $comment->id) }}"
                    onsubmit="return confirm('Delete this comment?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-xl btn-link text-danger p-0">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
                @endif

            </div>
            @endforeach -->


            {{-- COMMENT FORM (FIXED: BUTTON ADDED) --}}
            <form method="POST" action="{{ route('post.comment', $post->id) }}" class="mt-2 d-flex gap-2">
                @csrf
                <input type="text" name="comment" class="form-control" placeholder="Write a comment">
                <button class="btn btn-primary btn-sm">Post</button>
            </form>


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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('photoInput');
    const previewWrapper = document.getElementById('photoPreviewWrapper');
    const previewImage = document.getElementById('photoPreview');

    input.addEventListener('change', function () {
        if (!this.files || !this.files[0]) return;

        const file = this.files[0];

        // Only images
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
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function () {

            const postId = this.dataset.postId;
            const icon = this.querySelector('i');
            const countEl = document.querySelector(
                `.like-count[data-post-id="${postId}"]`
            );

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

                countEl.textContent = `${res.data.count} likes`;
            });
        });
    });

});
</script>


@endsection
