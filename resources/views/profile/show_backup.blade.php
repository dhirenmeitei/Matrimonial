@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <!-- About Start -->
        <div class="container-fluid p-5">
            <div class="row gx-5">
                <div class="col-lg-5 mb-5 mb-lg-0 wow fadeIn" data-wow-delay="0.1s" style="min-height: 500px;">
                    <div class="position-relative h-100">
                        <div class="position-absolute top-0 start-0 animate-rotate"
                            style="width: 160px; height: 160px;">
                            <img class="img-fluid" src="img/about-round.jpg" alt="">
                        </div>
                        <img class="position-absolute w-100 h-100 rounded-circle rounded-bottom rounded-end"
                            src="img/about.jpg" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="mb-4 wow fadeIn" data-wow-delay="0.2s">
                        <h5 class="section-title">Profile</h5>
                        <h1 class="display-3 mb-0">{{ $user->username }}</h1>
                    </div>
                    <p class="mb-4 wow fadeIn" data-wow-delay="0.3s">Nonumy erat diam duo labore clita. Sit magna ipsum dolor sed ea duo at ut. Tempor sit
                        lorem sit magna ipsum duo. Sit eos dolor ut sea rebum, diam sea rebum lorem kasd ut ipsum dolor est
                        ipsum. Et stet amet justo amet clita erat, ipsum sed at ipsum eirmod labore lorem.</p>
                    <div class="row">
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>Name: {{ $user->name }} </h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>Surname: {{ $user->surname }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>DOB: {{ $user->dob }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>Gender: {{ $user->gender }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>Gender: {{ $user->gender }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>Gender: {{ $user->gender }}</h6>
                            </div>
                        </div>

                        <div class="col-sm-12 wow fadeIn mt-4" data-wow-delay="0.5s">
                            <div class="bg-light rounded p-4">
                                <img class="img-fluid bg-primary rounded-circle mb-3" src="img/feature-3.png" style="width: 80px; height: 80px;">
                                <h4>Quality Food</h4>
                                <p class="mb-0">Tempor erat elitr at rebum at at clita aliquyam consetetur. Diam dolor diam
                                    ipsum et, tempor voluptua sit consetetur sit. Aliquyam diam amet diam et eos sadipscing
                                    labore.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->
        <!-- <div class="col-md-6">

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

        </div> -->
    </div>
</div>
@endsection