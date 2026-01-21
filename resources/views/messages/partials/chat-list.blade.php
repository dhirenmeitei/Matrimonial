<!-- <h5 class="mb-3">Friends</h5> -->

<div class="input-group mb-3">
    <input type="text" id="friendSearch" class="form-control" placeholder="Search friends...">
    <button class="btn btn-primary" type="button" id="searchBtn">Search</button>
</div>

{{-- FRIEND LIST --}}
@include('messages.partials.friends-list', ['users' => $users])
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('friendSearch');
    const friendsList = document.getElementById('friendsList');
    const searchBtn = document.getElementById('searchBtn');

    let typingTimer;
    const delay = 300;

    function fetchFriends(query) {
        axios.get("{{ route('messages.ajax.friends') }}", { params: { search: query } })
            .then(res => {
                friendsList.innerHTML = res.data; // replace HTML with partial from server
            })
            .catch(err => console.error(err));
    }

    // Search while typing
    searchInput.addEventListener('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            fetchFriends(searchInput.value);
        }, delay);
    });

    // Search on button click
    searchBtn.addEventListener('click', function() {
        fetchFriends(searchInput.value);
    });

    // Optional: Enter triggers search
    searchInput.addEventListener('keypress', function(e) {
        if(e.key === 'Enter'){
            e.preventDefault();
            fetchFriends(searchInput.value);
        }
    });
});
</script>
