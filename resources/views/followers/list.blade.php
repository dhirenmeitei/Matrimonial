@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Followers of {{ $user->username }}</h3>
    <div class="row">
        @forelse($followers as $follower)
        <div class="col-md-4 mb-3">
            <div class="card p-2 d-flex align-items-center">
                <img src="{{ $follower->photo ? asset('storage/'.$follower->photo) : asset('img/default-user.png') }}"
                    class="rounded-circle mb-2" style="width:50px;height:50px;object-fit:cover;">
                <strong>{{ $follower->username }}</strong>
                <a href="{{ route('profile.viewprofile', $follower->id) }}" class="btn btn-sm btn-primary mt-2">
                    View Profile
                </a>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p class="text-muted">No followers yet.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
