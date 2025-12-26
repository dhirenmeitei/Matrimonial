@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        {{-- LEFT --}}
        <div class="col-md-4 border-end">
            @include('messages.partials.chat-list', ['users' => $users])
        </div>

        {{-- RIGHT --}}
        <div class="col-md-8 d-flex flex-column" style="height:80vh">

            {{-- HEADER --}}
            <div class="border-bottom p-3 d-flex align-items-center">
                <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/default-user.png') }}"
                     class="rounded-circle me-2" width="40">
                <strong>{{ $user->username }}</strong>
            </div>

            {{-- CHAT --}}
            <div id="messages-container" class="flex-grow-1 overflow-auto p-3">
                @include('messages.partials.ajax-messages', ['messages' => $messages])
            </div>

            {{-- SEND --}}
            <div class="border-top p-3">
                <form method="POST"
                      action="{{ route('messages.store', $user->id) }}"
                      enctype="multipart/form-data"
                      class="d-flex gap-2">
                    @csrf
                    <input type="text" name="message" class="form-control" placeholder="Type a message">
                    <input type="file" name="file" class="form-control">
                    <button class="btn btn-primary">Send</button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- IMAGE MODAL --}}
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <img id="modalImage" class="w-100 rounded">
        </div>
    </div>
</div>
@endsection

<script>
let chatBox;
let lastMessageId = 0;

document.addEventListener('DOMContentLoaded', () => {
    chatBox = document.getElementById('messages-container');
    chatBox.scrollTop = chatBox.scrollHeight;
    lastMessageId = Number({{ $messages->last()->id ?? 0 }});

    document.getElementById('imageModal')
        .addEventListener('show.bs.modal', e => {
            document.getElementById('modalImage').src =
                e.relatedTarget.getAttribute('data-src');
        });

    setInterval(fetchMessages, 3000);
});

function fetchMessages() {
    axios.get("{{ route('messages.ajax', $user->id) }}", {
        params: { after_id: lastMessageId }
    }).then(res => {
        if (res.data.html && res.data.html.trim()) {
            chatBox.insertAdjacentHTML('beforeend', res.data.html);
            lastMessageId = res.data.last_id || lastMessageId;
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });
}

function deleteMessage(id) {
    if (!confirm('Delete this message?')) return;

    axios.delete(`/messages/${id}`, {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }).then(() => reloadMessages());
}

function reloadMessages() {
    axios.get("{{ route('messages.show', $user->id) }}", {
        params: { ajax: 1 }
    }).then(res => {
        chatBox.innerHTML = res.data.html;
        chatBox.scrollTop = chatBox.scrollHeight;
    });
}
</script>
