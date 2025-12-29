@extends('layouts.app')

@push('styles')
    <style>
        .blog-item img {
            transition: transform .4s ease;
        }

        .blog-item:hover img {
            transform: scale(1.05);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-5">

        {{-- Heading --}}
        <div class="mb-5 text-center" style="max-width: 700px; margin: auto;">
            <h5 class="section-title">Gallery</h5>
            <h1 class="display-5 mb-0">Latest Photos</h1>
        </div>

        <div class="row g-5">
            @foreach($posts as $post)
                <div class="col-lg-4 col-md-6">
                    <div class="blog-item h-100">

                        {{-- Image --}}
                        <div class="position-relative overflow-hidden rounded-top">
                            <a href="{{ route('post.show', $post->id) }}">
                                <img src="{{ asset('storage/' . $post->photo) }}" class="img-fluid w-100"
                                    style="height:260px;object-fit:cover;" alt="Photo">
                            </a>
                        </div>

                        {{-- Content --}}
                        <div class="bg-dark d-flex align-items-center rounded-bottom p-4">

                            {{-- Likes & Comments --}}
                            <div class="flex-shrink-0 text-center text-secondary border-end border-secondary pe-3 me-3">
                                <span class="d-block fs-4 fw-bold text-light">
                                    {{ $post->created_at->format('d') }}
                                </span>
                                <h6 class="text-primary text-uppercase mb-0">
                                    {{ $post->created_at->format('M') }}
                                </h6>
                                <span class="d-block">
                                    {{ $post->created_at->format('Y') }}
                                </span>
                            </div>


                            {{-- Link --}}
                            <a href="{{ route('post.show', $post->id) }}" class="h5 lh-base text-light text-decoration-none">
                                {{ $post->content }}
                                <br>
                                <span class="d-block">❤️ {{ $post->likes_count }} 💬 {{ $post->comments_count }}</span>
                            </a>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection