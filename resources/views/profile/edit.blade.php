@extends('layouts.app')

@section('title', 'Profile')

@section('header')
    <h2 class="h4 mb-0">
        {{ __('Profile') }}
    </h2>
@endsection

@section('content')
    <div class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
