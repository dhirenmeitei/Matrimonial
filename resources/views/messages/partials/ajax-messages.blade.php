@foreach($messages as $msg)
<div class="mb-3 d-flex {{ $msg->sender_id == auth()->id() ? 'justify-content-end' : '' }}">
    <div class="p-2 rounded"
        style="max-width:70%; background-color: {{ $msg->sender_id == auth()->id() ? '#dcf8c6' : '#f1f0f0' }};">
        @if($msg->is_deleted)
        <em class="text-muted">Message deleted</em>
        @else
        @if($msg->message)
        <div>{{ $msg->message }}</div>
        @endif
        @if($msg->file_path)
        @if(in_array(strtolower(pathinfo($msg->file_path, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp']))
        <img src="{{ asset('storage/'.$msg->file_path) }}" class="img-fluid rounded mt-1">
        @else
        <a href="{{ asset('storage/'.$msg->file_path) }}" target="_blank" class="d-block mt-1">📄 Download file</a>
        @endif
        @endif
        @endif
    </div>
</div>
@endforeach