@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Messages</h4>

    {{-- SEARCH FRIENDS --}}
    <form method="GET" action="{{ route('messages.index') }}" class="mb-3 d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Search friends..."
            value="{{ request('search') }}">
        <button class="btn btn-primary" type="submit">Search</button>
    </form>

    {{-- FRIEND LIST HORIZONTAL SCROLL --}}
    <div class="d-flex overflow-auto mb-3">
        @forelse($users as $user)
        <div class="text-center position-relative me-3">
            <a href="{{ route('messages.show', $user->id) }}" class="btn btn-sm">
            <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/default-user.png') }}"
                class="rounded-circle" width="60" height="60">

            <div class="mt-1">{{ $user->username }}
                
                    <!-- <i class="bi bi-chat-quote-fill text-success"></i> -->
                
            </div>
            </a>

            @php
            $unread = $user->unreadMessagesCount(auth()->id());
            @endphp
            @if($unread)
            <span class="badge bg-primary position-absolute top-0 start-100 translate-middle">
                {{ $unread }}
            </span>
            @endif
        </div>
        @empty
        <p class="text-muted">No users found</p>
        @endforelse
    </div>
</div>
@endsection