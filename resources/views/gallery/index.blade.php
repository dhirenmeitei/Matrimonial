@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Photo Gallery</h4>

    <div class="row g-3">
        @foreach($posts as $post)
        <div class="col-md-3 col-sm-4 col-6">
            <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none">
                <div class="position-relative gallery-item">
                    <img src="{{ asset('storage/'.$post->photo) }}"
                        class="img-fluid rounded w-100"
                        style="height:220px;object-fit:cover;">

                    {{-- Hover Overlay --}}
                    <div class="gallery-overlay d-flex justify-content-center align-items-center gap-4">
                        <span class="text-white fw-semibold">
                            ❤️ {{ $post->likes_count }}
                        </span>
                        <span class="text-white fw-semibold">
                            💬 {{ $post->comments_count }}
                        </span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

</div>

{{-- CSS --}}
<style>
    .gallery-item {
        overflow: hidden;
    }

    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        opacity: 0;
        transition: .3s;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
</style>
@endsection