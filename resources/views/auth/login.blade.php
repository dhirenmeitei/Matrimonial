@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container">

    {{-- Alerts --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Centered Card --}}
    <div class="row justify-content-center mt-5">
        <div class="col-md-4 col-lg-4">

            <div class="card shadow border-0">
                <div class="card-header text-center">
                    <h4 class="mb-0">Login</h4>
                </div>

                <div class="card-body p-4">

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row">
                            {{-- Username --}}
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text"
                                    name="username"
                                    value="{{ old('username') }}"
                                    class="form-control @error('username') is-invalid @enderror">

                                @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror">

                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn btn-primary px-4">
                                Login
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection