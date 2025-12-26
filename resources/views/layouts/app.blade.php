<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'My App')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Emblema+One&family=Poppins:wght@400;600&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Laravel Mix -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> -->

</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <!-- Spinner End -->

    <!-- Header Start -->
    <div class="container-fluid bg-dark px-0">
        <div class="row gx-0 wow fadeIn" data-wow-delay="0.1s">
            <div class="col-lg-3 bg-primary d-none d-md-block">
                <a href="{{ auth()->check() ? route('dashboard') : url('/') }}"
                    class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                    <h1 class="m-0 display-4 text-white text-uppercase">SoulMate</h1>
                </a>
            </div>

            <div class="col-lg-9">
                <!-- <div class="row gx-0 d-none d-lg-flex bg-dark">
                    <div class="col-6 px-5 text-start">
                        <div class="h-100 d-inline-flex align-items-center py-2 me-4">
                            <i class="fa fa-envelope text-primary me-2"></i>
                            <p class="mb-0">info@example.com</p>
                        </div>
                    </div>
                    <div class="col-6 px-5 text-end">
                        <div class="h-100 d-inline-flex align-items-center py-2">
                            <i class="fa fa-phone-alt text-primary me-2"></i>
                            <p class="mb-0">+012 345 6789</p>
                        </div>
                    </div>
                </div> -->

                <nav class="navbar navbar-expand-lg navbar-dark p-3 p-lg-0 px-lg-5" style="background: #111;">
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        @auth
                        <div class="navbar-nav me-auto py-0">
                            <!-- <a href="{{ route('dashboard') }}" class="nav-item nav-link">Dashboard</a> -->
                            <a href="{{ route('timeline') }}" class="nav-item nav-link">Timeline</a>
                            <a href="{{ route('profile.show') }}" class="nav-item nav-link">Profile</a>
                            <a href="{{ route('friends.find') }}" class="nav-item nav-link">Friends</a>
                            <a href="{{ route('gallery.index') }}" class="nav-item nav-link">Gallary</a>
                            <a href="{{ route('messages.index') }}" class="nav-item nav-link">Message</a>
                        </div>
                        @endauth

                        <div class="navbar-nav ms-auto">
                            @auth

                            <a href="{{ route('logout') }}"
                                class="nav-item nav-link"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="nav-item nav-link">Login</a>
                            <a href="{{ route('register') }}" class="nav-item nav-link">Register</a>
                            @endauth
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Header End -->

    {{-- Page Content --}}
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-secondary px-5 mt-5">
        <div class="row gx-5 py-5">
            <div class="col-lg-6">
                <h3 class="text-light mb-3">Get In Touch</h3>
                <p><i class="bi bi-geo-alt text-primary me-2"></i>123 Street, India</p>
                <p><i class="bi bi-envelope text-primary me-2"></i>info@example.com</p>
                <p><i class="bi bi-phone text-primary me-2"></i>+91 9876543210</p>
            </div>
            <div class="col-lg-6 text-end">
                <p class="mb-0">&copy; {{ date('Y') }} <strong>SoulMade</strong>. All Rights Reserved.</p>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-dark py-3 fs-4 back-to-top">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- JS Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

</body>

</html>