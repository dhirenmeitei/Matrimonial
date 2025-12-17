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
                            <option value="{{ $optValue }}"
                                {{ request('occupation') == $optValue ? 'selected' : '' }}>
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
                        <img
                            src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/default-user.png') }}"
                            class="rounded-circle me-2"
                            style="width:40px;height:40px;object-fit:cover;">
                        <strong>{{ $user->username }}</strong>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="d-flex gap-1">

                        {{-- VIEW PROFILE --}}
                        <a href="{{ route('profile.viewprofile', $user->id) }}"
                            class="btn btn-sm btn-outline-secondary">
                            View
                        </a>

                        {{-- FOLLOW --}}
                        <form method="POST" action="{{ route('follow.toggle', $user->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">
                                Follow
                            </button>
                        </form>

                    </div>

                </div>

                {{-- CARD BODY --}}
                <div class="card-body">
                    <p class="mb-1">
                        <strong>Occupation:</strong>
                        {{ $user->occupation ?? '—' }}
                    </p>

                    <p class="mb-0 text-muted">
                        {{ $user->locality ?? '' }}
                        {{ $user->state ? ', '.$user->state : '' }}
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