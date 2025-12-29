@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Messages</h4>

    {{-- SEARCH FRIENDS --}}
    <div class="input-group mb-3">
        <input type="text" id="friendSearch" class="form-control" placeholder="Search friends...">
        <button class="btn btn-primary" type="button" id="searchBtn">Search</button>
    </div>

    {{-- FRIEND LIST --}}
    <div id="friendsList" class="d-flex overflow-auto mb-3 p-4">
        @include('messages.partials.friends-list', ['users' => $users])
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('friendSearch');
    const friendsList = document.getElementById('friendsList');
    const searchBtn = document.getElementById('searchBtn');
    let typingTimer;
    const delay = 300; // ms

    function fetchFriends(query) {
        axios.get("{{ route('messages.ajax.friends') }}", { params: { search: query } })
            .then(res => {
                friendsList.innerHTML = res.data; // replace friend list with partial
            })
            .catch(err => console.error(err));
    }

    // Search while typing
    searchInput.addEventListener('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => fetchFriends(searchInput.value), delay);
    });

    // Search on button click
    searchBtn.addEventListener('click', function() {
        fetchFriends(searchInput.value);
    });

    // Enter key triggers search
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            fetchFriends(searchInput.value);
        }
    });
});
</script>
@endsection
