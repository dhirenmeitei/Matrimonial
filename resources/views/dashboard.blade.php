@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">

    {{-- Top Menu / User Info --}}
    <div class="d-flex justify-content-between align-items-center mt-3 mb-4">

        {{-- Welcome message --}}
        <h3 class="mb-0">Welcome, {{ auth()->user()->username }}</h3>

        {{-- User Dropdown --}}
    </div>

    {{-- Dashboard Content --}}
    <div class="row">
        <div class="col-md-12">

            <div class="card shadow-sm p-4">
               <p>Activ Friend</p>
               <p>New Friend</p>
               <p>Post</p>
               <p>Message</p>

            </div>

        </div>
    </div>
</div>
@endsection
