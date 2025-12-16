@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="container-fluid p-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">

            {{-- Alerts --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Login Card (same as Contact form box) -->
            <div class="bg-light rounded p-5 wow fadeIn" data-wow-delay="0.1s">
                <h5 class="section-title text-center">Welcome Back To</h5>
                <h1 class="display-6 text-center mb-4">SOULMADE</h1>
                <hr>
                <!-- <h5 class="section-title text-center">Login to your account</h5> -->

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Username -->
                    <div class="mb-3">
                        <input type="text"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Username"
                            class="form-control bg-white border-0 px-4 @error('username') is-invalid @enderror"
                            style="height: 55px;">
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <input type="password"
                            name="password"
                            placeholder="Password"
                            class="form-control bg-white border-0 px-4 @error('password') is-invalid @enderror"
                            style="height: 55px;">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Button -->
                    <button type="submit" class="btn btn-primary w-100 py-3">
                        Login
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection