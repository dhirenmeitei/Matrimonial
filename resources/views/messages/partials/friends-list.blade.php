@forelse($users as $user)
    <div class="text-center position-relative me-3">
        <a href="{{ route('messages.show', $user->id) }}" class="btn btn-sm">
            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('img/default-user.png') }}"
                class="rounded-circle" width="60" height="60">
            <div class="mt-1">{{ $user->username }}</div>
        </a>

        @php $unread = $user->unreadMessagesCount(auth()->id()); @endphp
        @if($unread)
            <span class="badge bg-primary position-absolute top-0 start-100 translate-middle">
                {{ $unread }}
            </span>
        @endif
    </div>
@empty
    <p class="text-muted">No users found</p>
@endforelse