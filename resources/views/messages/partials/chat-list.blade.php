<h5 class="mb-3">Friends</h5>

{{-- FRIEND LIST HORIZONTAL SCROLL --}}
<div class="d-flex flex-column">
    @forelse($users as $user)
    <a href="{{ route('messages.show', $user->id) }}" class="d-flex align-items-center p-2 mb-2 text-decoration-none border rounded">
        <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/default-user.png') }}"
             class="rounded-circle me-2" width="40" height="40">
        <div class="flex-grow-1">
            <strong>{{ $user->username }}</strong>
        </div>

        @php
            $unread = $user->unreadMessagesCount(auth()->id());
        @endphp
        @if($unread)
            <span class="badge bg-primary">{{ $unread }}</span>
        @endif
    </a>
    @empty
    <p class="text-muted">No users found</p>
    @endforelse
</div>
