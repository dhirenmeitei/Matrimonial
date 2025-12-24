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
            <div id="chat-messages" class="flex-grow-1 overflow-auto p-3">
                @include('messages.partials.ajax-messages')
            </div>


            {{-- SEND MESSAGE BOX --}}
            <div class="border-top p-3">
                <form id="send-message-form" method="POST" action="{{ route('messages.store', $user->id) }}" enctype="multipart/form-data" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="message" class="form-control" placeholder="Message...">
                    <input type="file" name="file" class="form-control">
                    <button class="btn btn-primary">Send</button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto refresh messages every 3 seconds
    function fetchMessages() {
        axios.get("{{ route('messages.show', $user->id) }}?ajax=1")
            .then(res => {
                document.getElementById('messages-container').innerHTML = res.data.html;
                // Scroll to bottom
                let container = document.getElementById('messages-container');
                container.scrollTop = container.scrollHeight;
            });
    }

    setInterval(fetchMessages, 3000);

    // Scroll to bottom on first load
    window.onload = function() {
        let container = document.getElementById('messages-container');
        container.scrollTop = container.scrollHeight;
    };
</script>
@endsection