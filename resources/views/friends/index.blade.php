@extends('layouts.app')

@section('content')
<div class="container">

    {{-- SEARCH FRIENDS --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Find by occupation</label>
                        <select name="occupation" class="form-select">
                            <option value="all">All</option>
                            @foreach($occupations as $optValue => $optText)
                            <option value="{{ $optValue }}" {{ request('occupation') == $optValue ? 'selected' : '' }}>
                                {{ $optText }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- FRIEND LIST --}}
    <div class="row">
        @forelse($users as $user)
        <div class="col-md-4 mb-4">
            <div class="card h-100">

                {{-- CARD HEADER --}}
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/default-user.png') }}"
                            class="rounded-circle me-2" style="width:40px;height:40px;object-fit:cover;">
                        <strong>{{ $user->username }}</strong>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="d-flex gap-1">
                        {{-- VIEW PROFILE --}}
                        <a href="{{ route('profile.viewprofile', $user->id) }}" class="btn btn-sm btn-outline-secondary">
                            View
                        </a>

                        @php
                        $authUser = auth()->user();

                        // Check if this user sent a pending follow request to auth user
                        $incomingRequest = \App\Models\Follow::where('follower_id', $user->id)
                        ->where('following_id', $authUser->id)
                        ->where('status', 'pending')
                        ->first();

                        // Check if auth user sent a follow request to this user
                        $outgoingStatus = $authUser->followStatus($user->id);
                        @endphp

                        @if($incomingRequest)
                        {{-- ACCEPT FOLLOW REQUEST --}}
                        <form method="POST" action="{{ route('follow.accept', $incomingRequest->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-success">Accept Request</button>
                        </form>
                        @else
                        {{-- FOLLOW / UNFOLLOW --}}
                        <form method="POST" action="{{ route('follow.toggle', $user->id) }}">
                            @csrf
                            @if($outgoingStatus === 'accepted')
                            <button class="btn btn-sm btn-danger">Unfollow</button>
                            @elseif($outgoingStatus === 'pending')
                            <button class="btn btn-sm btn-secondary" disabled>Requested</button>
                            @else
                            <button class="btn btn-sm btn-outline-primary">Follow</button>
                            @endif
                        </form>
                        @endif
                    </div>
                </div>

                {{-- CARD BODY --}}
                <div class="card-body">
                    <p class="mb-1"><strong>Occupation:</strong> {{ $user->occupation ?? '—' }}</p>
                    <p class="mb-0 text-muted">
                        {{ $user->locality ?? '' }}{{ $user->state ? ', '.$user->state : '' }}
                    </p>
                </div>

            </div>
        </div>
        @empty
        <div class="col-12">
            <p class="text-center text-muted">No users found</p>
        </div>
        @endforelse
    </div>

</div>
@endsection