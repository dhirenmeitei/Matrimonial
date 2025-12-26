@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card mb-4">

        {{-- HEADER --}}
        <div class="card-header d-flex justify-content-between">
            <strong>{{ $post->user->username }}</strong>

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

            @if($post->content)
            <p>{{ $post->content }}</p>
            @endif

            @if($post->photo)
            <img src="{{ asset('storage/'.$post->photo) }}" class="img-fluid rounded mb-3">
            @endif

            {{-- LIKE --}}
            @php
            $liked = $post->likes->contains('user_id', auth()->id());
            @endphp
            <p>
            <form method="POST" action="{{ route('post.like', $post->id) }}" class="d-inline">
                @csrf
                <button class="btn btn-link fs-4 {{ $liked ? 'text-danger' : 'text-secondary' }}">
                    ❤️
                </button>
            </form>

            <span class="fw-semibold">{{ $post->likes->count() }} likes</span></p>


            <hr>

            {{-- COMMENTS --}}
            @foreach($post->comments as $comment)
            <div class="mb-2">
                <strong>{{ $comment->user->username }}</strong>
                {{ $comment->comment }}

                @if($comment->user_id === auth()->id())
                <form method="POST" action="{{ route('comment.delete', $comment->id) }}"
                    class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm text-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
                @endif
            </div>
            @endforeach

            {{-- ADD COMMENT --}}
            <form method="POST" action="{{ route('post.comment', $post->id) }}" class="mt-2">
                @csrf
                <div class="input-group">
                    <input type="text" name="comment" class="form-control" placeholder="Write a comment">
                    <button class="btn btn-primary">Post</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection