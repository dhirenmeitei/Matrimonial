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

        <div class="card-header d-flex justify-content-between">
            <strong>{{ $post->user->username }}</strong>

            <form method="POST" action="{{ route('follow.toggle', $post->user->id) }}">
                @csrf
                <button class="btn btn-sm btn-outline-primary">
                    Follow
                </button>
            </form>
        </div>

        <div class="card-body">
            @if($post->content)
            <p>{{ $post->content }}</p>
            @endif

            @if($post->photo)
            <img src="{{ asset('storage/'.$post->photo) }}" class="img-fluid mb-2">
            @endif

            {{-- ❤️ LIKE --}}
            <form method="POST" action="{{ route('post.like', $post->id) }}">
                @csrf
                <button class="btn btn-link text-danger fs-4">❤️</button>
                {{ $post->likes->count() }}
            </form>

            {{-- COMMENTS --}}
            @foreach($post->comments as $comment)
            <div>
                <strong>{{ $comment->user->username }}</strong>
                {{ $comment->comment }}
                <br>
                <small class="text-muted">
                    {{ $comment->created_at->format('d M Y h:i A') }}
                </small>
            </div>
            @endforeach

            <form method="POST" action="{{ route('post.comment', $post->id) }}" class="mt-2">
                @csrf
                <input type="text" name="comment" class="form-control" placeholder="Write a comment">
            </form>
        </div>

    </div>
    @endforeach

</div>
@endsection