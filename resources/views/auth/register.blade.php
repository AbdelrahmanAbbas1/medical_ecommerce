@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Name'), 'for' => 'name'])
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-control">
            @if($errors->has('name'))
                @include('partials.input-error', ['errors' => $errors->get('name')])
            @endif
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Email'), 'for' => 'email'])
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="form-control">
            @if($errors->has('email'))
                @include('partials.input-error', ['errors' => $errors->get('email')])
            @endif
        </div>

        <!-- Password -->
        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Password'), 'for' => 'password'])
            <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control">
            @if($errors->has('password'))
                @include('partials.input-error', ['errors' => $errors->get('password')])
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Confirm Password'), 'for' => 'password_confirmation'])
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control">
            @if($errors->has('password_confirmation'))
                @include('partials.input-error', ['errors' => $errors->get('password_confirmation')])
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a class="text-decoration-none" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
            </button>
        </div>
    </form>
@endsection
