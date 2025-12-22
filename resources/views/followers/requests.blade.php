@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Follow Requests</h3>
    <div class="row">
        @forelse($requests as $request)
        <div class="col-md-4 mb-3">
            <div class="card p-2 d-flex align-items-center">
                <img src="{{ $request->follower->photo ? asset('storage/'.$request->follower->photo) : asset('img/default-user.png') }}"
                    class="rounded-circle mb-2" style="width:50px;height:50px;object-fit:cover;">
                <strong>{{ $request->follower->username }}</strong>
                <form method="POST" action="{{ route('follow.accept', $request->id) }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-success">Accept Request</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p class="text-muted">No follow requests.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection