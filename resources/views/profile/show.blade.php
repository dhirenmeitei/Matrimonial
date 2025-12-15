@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">

            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h4>My Profile</h4>
                </div>

                <div class="card-body text-center">

                    {{-- User Photo --}}
                    @if($user->photo)
                    <img src="data:image/jpeg;base64,{{ $user->photo }}"
                        alt="User Photo"
                        class="rounded-circle mb-3"
                        width="120"
                        height="120"
                        style="object-fit: cover;">
                    @else
                    <img src="{{ asset('images/default-user.png') }}"
                        alt="Default Photo"
                        class="rounded-circle mb-3"
                        width="120"
                        height="120"
                        style="object-fit: cover;">
                    @endif


                    <h5>{{ $user->username }}</h5>
                    <!-- <p class="text-muted">{{ $user->username }}</p> -->

                    <ul class="list-group text-start mt-4">
                        <li class="list-group-item"><strong>Name:</strong> {{ $user->name }}</li>
                        <li class="list-group-item"><strong>Surname:</strong> {{ $user->surname }}</li>
                        <li class="list-group-item"><strong>Gender:</strong> {{ $user->gender }}</li>
                        <li class="list-group-item"><strong>Date of Birth:</strong> {{ $user->dob }}</li>
                        <li class="list-group-item"><strong>Mobile:</strong> {{ $user->mobile_no }}</li>
                        <li class="list-group-item"><strong>District:</strong> {{ $user->district }}</li>
                        <li class="list-group-item"><strong>Community:</strong> {{ $user->community }}</li>
                        <li class="list-group-item"><strong>Yek Salai:</strong> {{ $user->yek_salai }}</li>
                        <li class="list-group-item"><strong>Educational Qualification:</strong> {{ $user->educational_qualification }}</li>
                    </ul>

                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection