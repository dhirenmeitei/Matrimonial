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
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
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
            @if($post->content)
            <p>{{ $post->content }}</p>
            @endif

            @if($post->photo)
            <img src="{{ asset('storage/'.$post->photo) }}" class="img-fluid mb-2 rounded">
            @endif

            {{-- LIKE --}}
            @php
            $liked = $post->likes->contains('user_id', auth()->id());
            @endphp
            <p>
            <form method="POST" action="{{ route('post.like', $post->id) }}" class="d-inline">
                @csrf
                <button class="btn btn-link fs-4 {{ $liked ? 'text-danger' : 'text-secondary' }}">❤️</button>
            </form>

            {{-- LIKE COUNT (CLICKABLE) --}}
            <span class="text-primary ms-1" style="cursor:pointer"
                data-bs-toggle="modal" data-bs-target="#likesModal{{ $post->id }}">
                {{ $post->likes->count() }} likes
            </span>
            </p>



            {{-- COMMENTS --}}
            <hr>
            @foreach($post->comments as $comment)
            <div class="mb-1">
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
    <div class="modal fade" id="likesModal{{ $post->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Liked by</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($post->likes->isEmpty())
                    <p class="text-muted">0</p>
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