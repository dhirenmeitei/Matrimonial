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
                    <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('img/default-user.png') }}"
                        class="rounded-circle me-2" width="40">
                    <strong>{{ $user->username }}</strong>
                </div>

                {{-- CHAT --}}
                <div id="messages-container" class="flex-grow-1 overflow-auto p-3">
                    @include('messages.partials.ajax-messages', ['messages' => $messages])
                </div>

                {{-- SEND --}}
                <div class="border-top p-3">
                    <form id="messageForm" enctype="multipart/form-data" class="d-flex gap-2">
                        @csrf
                        <input type="text" name="message" class="form-control" placeholder="Type a message">
                        <label class="btn btn-light d-flex align-items-center gap-2 mb-0">
                            <i class="bi bi-image text-success fs-5"></i>
                            <span class="fw-semibold">Files</span>
                            <input type="file" name="file" hidden accept="image/*">
                        </label>
                        <button type="submit" class="btn btn-primary">Send</button>
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
    document.addEventListener('DOMContentLoaded', () => {
        const chatBox = document.getElementById('messages-container');
        const form = document.getElementById('messageForm');
        let lastMessageId = Number({{ $messages->last()->id ?? 0 }});

        // Scroll to bottom on load
        chatBox.scrollTop = chatBox.scrollHeight;

        // Handle sending message via AJAX
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(form);

            axios.post("{{ route('messages.store', $user->id) }}", formData)
                .then(() => {
                    form.reset();
                    fetchMessages(); // fetch latest message immediately
                })
                .catch(err => {
                    console.error(err);
                    alert('Failed to send message');
                });
        });

        // Fetch new messages every 2 seconds
        setInterval(fetchMessages, 2000);

        // Poll read status every 2 seconds
        setInterval(pollReadStatus, 2000);

        function fetchMessages() {
            axios.get("{{ route('messages.ajax', $user->id) }}", {
                params: { after_id: lastMessageId }
            }).then(res => {
                if (res.data.html && res.data.html.trim()) {
                    if (lastMessageId !== res.data.last_id) {
                        chatBox.insertAdjacentHTML('beforeend', res.data.html);
                        lastMessageId = res.data.last_id || lastMessageId;
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                }
            });
        }

        function pollReadStatus() {
            const myMessages = Array.from(document.querySelectorAll('.message.justify-content-end'))
                .map(m => m.dataset.id);

            if (!myMessages.length) return;

            axios.get("{{ route('messages.read-status') }}", { params: { ids: myMessages } })
                .then(res => {
                    const statuses = res.data;
                    Object.keys(statuses).forEach(id => {
                        const msgElem = document.querySelector(`.message[data-id='${id}']`);
                        if (!msgElem) return;

                        const tick = msgElem.querySelector('i.bi-check2-all');
                        if (!tick) return;

                        if (statuses[id]) {
                            tick.classList.remove('text-secondary');
                            tick.classList.add('text-primary');
                        } else {
                            tick.classList.remove('text-primary');
                            tick.classList.add('text-secondary');
                        }
                    });
                });
        }

        // Image modal (optional)
        document.getElementById('imageModal')?.addEventListener('show.bs.modal', e => {
            document.getElementById('modalImage').src =
                e.relatedTarget.getAttribute('data-src');
        });
    });
</script>