@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        {{-- LEFT: FRIEND LIST --}}
        <div class="col-md-4 border-end">
            @include('messages.partials.chat-list') {{-- Make sure this partial exists --}}
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
            <div class="flex-grow-1 overflow-auto p-3">
                @foreach($messages as $msg)
                <div class="mb-3 d-flex {{ $msg->sender_id == auth()->id() ? 'justify-content-end' : '' }}">

                    {{-- MESSAGE BUBBLE --}}
                    <div class="p-2 rounded"
                        style="max-width:70%; 
                                background-color: {{ $msg->sender_id == auth()->id() ? '#dcf8c6' : '#f1f0f0' }};">

                        {{-- DELETED MESSAGE --}}
                        @if($msg->is_deleted)
                        <em class="text-muted">Message deleted</em>
                        @else
                        {{-- TEXT --}}
                        @if($msg->message)
                        <div>{{ $msg->message }}</div>
                        @endif

                        {{-- ATTACHMENT --}}
                        @if($msg->file_path)
                        @if(in_array(strtolower(pathinfo($msg->file_path, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp']))
                        <img src="{{ asset('storage/'.$msg->file_path) }}" class="img-fluid rounded mt-1">
                        @else
                        <a href="{{ asset('storage/'.$msg->file_path) }}" target="_blank" class="d-block mt-1">
                            📄 Download file
                        </a>
                        @endif
                        @endif

                        {{-- READ STATUS --}}
                        @if($msg->sender_id == auth()->id())
                        <div class="text-end small mt-1">
                            @if($msg->reads->where('user_id', $user->id)->count())
                            <span class="text-info">✔✔</span>
                            @else
                            <span class="text-secondary">✔✔</span>
                            @endif
                        </div>
                        @endif
                        @endif
                    </div>

                </div>

                {{-- ACTIONS BELOW MESSAGE BUBBLE --}}
                @if(!$msg->is_deleted)
                <div class="d-flex gap-2 mb-3 {{ $msg->sender_id == auth()->id() ? 'justify-content-end' : '' }}">

                    {{-- EDIT & DELETE for sender --}}
                    @if($msg->sender_id == auth()->id())
                    <!-- <form method="POST" action="{{ route('messages.update', $msg->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="message" value="{{ $msg->message }}">
                        <button class="btn btn-sm btn-outline-secondary">Edit</button>
                    </form> -->

                    <form method="POST" action="{{ route('messages.destroy', $msg->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                    <!-- 
                    {{-- LIKE (everyone can like) --}}
                    <form method="POST" action="{{ route('messages.like', $msg->id) }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-primary">❤️ {{ $msg->likes->count() }}</button>
                    </form> -->

                </div>
                @endif

                @endforeach
            </div>

            {{-- SEND MESSAGE BOX --}}
            <div class="border-top p-3">
                <form method="POST" action="{{ route('messages.store', $user->id) }}" enctype="multipart/form-data" class="d-flex gap-2">
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