@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        {{-- LEFT: FRIEND LIST --}}
        <div class="col-md-4 border-end">
            @include('messages.partials.chat-list', ['users' => $users]) {{-- Ensure this partial exists --}}
        </div>

        {{-- RIGHT: CHAT --}}
        <div class="col-md-8 d-flex flex-column" style="height:80vh">

            {{-- HEADER --}}
            <div class="border-bottom p-3 d-flex align-items-center">
                <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/default-user.png') }}"
                    class="rounded-circle me-2" width="40">
                <strong>{{ $user->username }}</strong>
            </div>

            {{-- MESSAGES --}}
            <div id="messages-container" class="flex-grow-1 overflow-auto p-3">
                @include('messages.partials.ajax-messages')
            </div>



            {{-- SEND MESSAGE BOX --}}
            <div class="border-top p-3">
                <form id="sendForm"
                    method="POST"
                    action="{{ route('messages.store', $user->id) }}"
                    enctype="multipart/form-data"
                    class="d-flex gap-2">
                    @csrf

                    <input type="text"
                        name="message"
                        id="msgInput"
                        class="form-control"
                        placeholder="Type a message...">

                    <input type="file"
                        name="file"
                        id="fileInput"
                        class="form-control">

                    <button type="submit" class="btn btn-primary">
                        Send
                    </button>
                </form>

            </div>


        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatBox = document.getElementById('messages-container');
        let lastMessageId = Number({{$messages->last()->id ?? 0}});

        function fetchMessages() {
            axios.get("{{ route('messages.show', $user->id) }}", {
                    params: {
                        ajax: 1,
                        after_id: lastMessageId
                    }
                })
                .then(res => {
                    if (res.data.html && res.data.html.trim() !== '') {
                        chatBox.insertAdjacentHTML('beforeend', res.data.html);
                        chatBox.scrollTop = chatBox.scrollHeight;
                        lastMessageId = res.data.last_id || lastMessageId;
                    }
                })
                .catch(err => console.error(err));
        }

        // Fetch new messages every 3 seconds
        setInterval(fetchMessages, 3000);
    });
</script>
@endsection