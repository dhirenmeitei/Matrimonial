@extends('layouts.app')

@section('content')
<div class="container">

    {{-- CREATE POST --}}
    <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data" class="mb-4">
        @csrf
        <textarea name="content" class="form-control mb-2" placeholder="What's on your mind?"></textarea>
        <input type="file" name="photo" class="form-control mb-2">
        <button class="btn btn-primary">Post</button>
    </form>

    {{-- POSTS --}}
    @foreach($posts as $post)
    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">
            {{-- USERNAME WITH LINK TO PROFILE --}}
            <strong>
                <a href="{{ route('profile.viewprofile', $post->user->id) }}" class="text-decoration-none">
                    {{ $post->user->username }}
                </a>
            </strong>

            @if(auth()->id() !== $post->user->id)
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
                <button class="btn btn-sm btn-success">Accept Request</button>
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

        <div class="card-body">
            @if($post->content)
            <p>{{ $post->content }}</p>
            @endif

            @if($post->photo)
            <img src="{{ asset('storage/'.$post->photo) }}" class="img-fluid mb-2">
            @endif

            {{-- LIKE --}}
            @php
            $liked = $post->likes->contains('user_id', auth()->id());
            @endphp
            <form method="POST" action="{{ route('post.like', $post->id) }}" class="d-inline">
                @csrf
                <button class="btn btn-link fs-4 {{ $liked ? 'text-danger' : 'text-secondary' }}">❤️</button>
            </form>

            {{-- CLICKABLE LIKE COUNT --}}
            <span class="ms-2 text-primary" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#likesModal{{ $post->id }}">
                {{ $post->likes->count() }} likes
            </span>

            {{-- COMMENTS --}}
            @foreach($post->comments as $comment)
            <div>
                <strong>
                    <a href="{{ route('profile.viewprofile', $comment->user->id) }}" class="text-decoration-none">
                        {{ $comment->user->username }}
                    </a>
                </strong>
                {{ $comment->comment }}
                <br>
                <small class="text-muted">{{ $comment->created_at->format('d M Y h:i A') }}</small>
            </div>
            @endforeach

            <form method="POST" action="{{ route('post.comment', $post->id) }}" class="mt-2">
                @csrf
                <input type="text" name="comment" class="form-control" placeholder="Write a comment">
            </form>
        </div>
    </div>

    {{-- LIKES MODAL --}}
    <div class="modal fade" id="likesModal{{ $post->id }}" tabindex="-1" aria-labelledby="likesModalLabel{{ $post->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="likesModalLabel{{ $post->id }}">Liked by</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
@endsection