<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'My App')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Vite --}}
    @vite(['resources/js/app.js'])

</head>

<body>

    {{-- Navbar (optional) --}}
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <!-- <a class="navbar-brand" href="/dashboard">SoulMade</a> -->
        <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : url('/') }}">
            SoulMade
        </a>


        <div class="ms-auto">
            <div class="dropdown">
                <!-- Navbar toggle: only username -->
                <a class="text-decoration-none dropdown-toggle text-white" href="#" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ auth()->user()->username }}
                </a>

                <!-- Dropdown menu styled like a profile card -->
                <ul class="dropdown-menu dropdown-menu-end p-3 shadow" aria-labelledby="userMenu" style="min-width: 200px;">
                    <li class="text-center mb-2">
                        {{-- User Photo --}}
                        @if(auth()->user()->photo)
                        <img src="data:image/jpeg;base64,{{ auth()->user()->photo }}"
                            alt="User Photo"
                            class="rounded-circle mb-2 border border-secondary"
                            width="80"
                            height="80"
                            style="object-fit: cover;">
                        @else
                        <img src="{{ asset('images/default-user.png') }}"
                            alt="Default Photo"
                            class="rounded-circle mb-2 border border-secondary"
                            width="80"
                            height="80"
                            style="object-fit: cover;">
                        @endif


                        {{-- Username --}}
                        <div class="fw-bold">{{ auth()->user()->username }}</div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-center" href="{{ route('profile.show') }}">View Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-center" type="submit">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </nav>

    @endauth

    {{-- Page Content --}}
    <main class="py-4">
        @yield('content')
    </main>

</body>

</html>