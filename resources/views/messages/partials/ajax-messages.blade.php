@foreach($messages as $msg)
<div class="mb-2 d-flex {{ $msg->sender_id == auth()->id() ? 'justify-content-end' : '' }}">
    <div class="p-2 rounded" style="max-width:70%;
        background-color: {{ $msg->sender_id == auth()->id() ? '#dcf8c6' : '#f1f0f0' }}">

        @if($msg->is_deleted)
        <em class="text-muted">Message deleted</em>
        @else
        {{ $msg->message }}
        @endif

        {{-- DOUBLE TICKS --}}
        @if($msg->sender_id == auth()->id())
        <div class="text-end mt-1">
            @if($msg->reads->count())
            <i class="bi bi-check2-all text-primary"></i>
            @else
            <i class="bi bi-check2-all text-secondary"></i>
            @endif
        </div>
        @endif
    </div>
</div>
@endforeach