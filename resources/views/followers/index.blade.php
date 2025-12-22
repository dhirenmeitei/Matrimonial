@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">
        Followers of {{ $user->username }}
        <span class="text-muted">({{ $followers->count() }})</span>
    </h4>

    <div class="row">

        @forelse($followers as $follower)
        <div class="col-md-4 mb-4">

            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">

                    <div class="d-flex align-items-center">
                        <img src="{{ $follower->photo ? asset('storage/'.$follower->photo) : asset('img/default-user.png') }}"
                             class="rounded-circle me-2"
                             style="width:45px;height:45px;object-fit:cover;">

                        <strong>{{ $follower->username }}</strong>
                    </div>

                    <a href="{{ route('profile.viewprofile', $follower->id) }}"
                       class="btn btn-sm btn-outline-primary">
                        View Profile
                    </a>

                </div>
            </div>

        </div>
        @empty
        <div class="col-12">
            <p class="text-muted text-center">No followers yet</p>
        </div>
        @endforelse

    </div>

</div>
@endsection
