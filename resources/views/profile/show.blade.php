@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <!-- Profile Start -->
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
                        <h3 class="display-3 mb-2">{{ $user->username }}</h3>

                        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                            {{-- FOLLOWERS BUTTON --}}
                            <a href="{{ route('followers.list', $user->id) }}" class="btn btn-success btn-sm fw-semibold">
                                Followers ({{ $user->followers()->count() }})
                            </a>

                            {{-- ONLY SHOW FOLLOW / UNFOLLOW IF THIS IS NOT THE LOGGED-IN USER --}}
                            @if(auth()->id() !== $user->id)
                            @php
                            $status = auth()->user()->followStatus($user->id);
                            @endphp

                            <form method="POST" action="{{ route('follow.toggle', $user->id) }}">
                                @csrf
                                @if($status === 'accepted')
                                <button class="btn btn-danger btn-sm">Unfollow</button>
                                @elseif($status === 'pending')
                                <button class="btn btn-secondary btn-sm" disabled>Requested</button>
                                @else
                                <button class="btn btn-outline-primary btn-sm">Follow</button>
                                @endif
                            </form>
                            @endif

                            {{-- FOLLOW REQUESTS (for logged-in user only) --}}
                            @if(auth()->id() === $user->id)
                            <a href="{{ route('follow.requests') }}" class="btn btn-warning btn-sm">
                                Follow Requests ({{ auth()->user()->followRequests()->count() }})
                            </a>
                            @endif
                        </div>



                    </div>
                    <p class="mb-4 wow fadeIn" data-wow-delay="0.3s"> {{ $user->introduction }}</p>
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
                                <h6>Yek-Salai: {{ $user->yek_salai }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>Gender: {{ $user->gender }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>Mobile No.: {{ $user->mobile_no }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>Nationality: {{ $user->nationality }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>State: {{ $user->state }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>Locality: {{ $user->locality }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>Qualification: {{ $user->educational_qualification }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <h6>Occupation: {{ $user->occupation }}</h6>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="rounded p-2">
                                <a href="{{ route('profile.edit') }}" class="btn-sm btn-primary">
                                    Edit Profile
                                </a>
                            </div>
                        </div>
                        <!-- <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-2">
                                <a href="{{ route('profile.edit') }}" class="btn-sm btn-primary">
                                    Edit Profile
                                </a>
                            </div>
                        </div> -->

                        <!-- <div class="col-sm-12 wow fadeIn mt-4" data-wow-delay="0.5s">
                            <div class="bg-light rounded p-4">
                                <img class="img-fluid bg-primary rounded-circle mb-3" src="img/feature-3.png" style="width: 80px; height: 80px;">
                                <h4>Quality Food</h4>
                                <p class="mb-0">Tempor erat elitr at rebum at at clita aliquyam consetetur. Diam dolor diam
                                    ipsum et, tempor voluptua sit consetetur sit. Aliquyam diam amet diam et eos sadipscing
                                    labore.</p>
                            </div>
                        </div> -->
                    </div>
                    <!-- {{-- Identity Proof Preview --}}
                    <div class="col-sm-6 wow fadeIn mb-2" data-wow-delay="0.4s">
                        <div class="bg-light rounded p-2">
                            <h6>Identity Proof:</h6>

                            @if($user->identity_proof)
                            <img
                                src="data:image/png;base64,{{ $user->identity_proof }}"
                                class="img-fluid rounded mt-2"
                                style="max-height: 120px;">
                            @else
                            <span class="text-muted">Not uploaded</span>
                            @endif
                        </div>
                    </div> -->

                </div>
            </div>
        </div>
        <!-- Profile End -->
    </div>
</div>
@endsection