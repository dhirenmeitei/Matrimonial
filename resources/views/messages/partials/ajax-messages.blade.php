@foreach($messages as $msg)
    @php
        $me = auth()->id();
        $isDeletedForMe = $msg->is_deleted &&
            (($msg->deleted_by == $msg->sender_id) || ($msg->deleted_by == $me && $me == $msg->receiver_id));
    @endphp

    <div class="mb-2 d-flex {{ $msg->sender_id == $me ? 'justify-content-end' : '' }} message" data-id="{{ $msg->id }}">
        <div class="p-2 rounded" style="max-width:70%; background:{{ $msg->sender_id == $me ? '#dcf8c6' : '#f1f0f0' }}">

            @if($isDeletedForMe)
                <em class="text-muted">Message deleted</em>
            @else
                @if($msg->file_type === 'image' && $msg->file_path)
                    <img src="{{ asset('storage/' . $msg->file_path) }}" style="max-width:100%; cursor:pointer"
                        data-bs-toggle="modal" data-bs-target="#imageModal" data-src="{{ asset('storage/' . $msg->file_path) }}">
                @endif

                @if($msg->file_type === 'pdf' && $msg->file_path)
                    <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank">📄 View PDF</a>
                @endif

                @if($msg->message)
                    <div>{{ $msg->message }}</div>
                @endif
            @endif

            @if($msg->sender_id == $me)
                <div class="text-end small text-muted mt-1">
                    <i class="bi bi-check2-all {{ $msg->is_read ? 'text-primary' : 'text-secondary' }}"></i>
                </div>
            @endif
        </div>
    </div>
@endforeach