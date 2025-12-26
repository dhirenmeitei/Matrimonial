{{-- Friends List: Horizontal on mobile, Vertical on desktop --}}
<div id="friendsList" class="d-flex flex-row flex-md-column overflow-auto mb-3" style="gap: 0.75rem;">

    @forelse($users as $user)
        <div class="text-center position-relative flex-shrink-0" style="width: 70px;">
            {{-- Profile Image --}}
            <a href="{{ route('messages.show', $user->id) }}">
                <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/default-user.png') }}"
                     class="rounded-circle border" width="60" height="60">
            </a>

            {{-- Username --}}
            <div class="mt-1 small text-truncate" style="width: 60px;">
                {{ $user->username }}
            </div>

            {{-- Unread Badge --}}
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
