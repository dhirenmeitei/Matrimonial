@foreach($messages as $msg)
@php
    $me = auth()->id();

    // WhatsApp-style delete logic
    $isDeletedForMe = false;

    if ($msg->is_deleted) {
        // Sender deleted → deleted for BOTH
        if ($msg->deleted_by == $msg->sender_id) {
            $isDeletedForMe = true;
        }

        // Receiver deleted → deleted ONLY for receiver
        if ($msg->deleted_by == $msg->receiver_id && $me == $msg->receiver_id) {
            $isDeletedForMe = true;
        }
    }
@endphp

<div class="mb-2 d-flex {{ $msg->sender_id == $me ? 'justify-content-end' : '' }} message"
     data-id="{{ $msg->id }}">

    <div class="p-2 rounded"
         style="max-width:70%; background:{{ $msg->sender_id == $me ? '#dcf8c6' : '#f1f0f0' }}">

        {{-- Show deleted text if deleted for me --}}
        @if($isDeletedForMe)
            <em class="text-muted">Message deleted</em>
        @else
            {{-- IMAGE --}}
            @if($msg->file_type === 'image' && $msg->file_path)
                <img src="{{ asset('storage/'.$msg->file_path) }}"
                     style="max-width:100%; cursor:pointer"
                     data-bs-toggle="modal"
                     data-bs-target="#imageModal"
                     data-src="{{ asset('storage/'.$msg->file_path) }}">
            @endif

            {{-- PDF --}}
            @if($msg->file_type === 'pdf' && $msg->file_path)
                <a href="{{ asset('storage/'.$msg->file_path) }}" target="_blank">📄 View PDF</a>
            @endif

            {{-- TEXT --}}
            @if($msg->message)
                <div>{{ $msg->message }}</div>
            @endif
        @endif

        {{-- READ TICKS --}}
        @if($msg->sender_id == $me)
            <div class="text-end">
                <i class="bi bi-check2-all {{ $msg->is_read ? 'text-primary' : 'text-secondary' }}"></i>
            </div>
        @endif

        {{-- DELETE BUTTON --}}
        <!-- <div class="text-end">
            @if(!$isDeletedForMe) {{-- hide delete button if already deleted --}}
                <button class="btn btn-sm text-danger p-0" onclick="deleteMessage({{ $msg->id }})">
                    Delete
                </button>
            @endif
        </div> -->
    </div>
</div>
@endforeach
