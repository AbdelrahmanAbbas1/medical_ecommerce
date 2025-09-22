@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <!-- Session Status -->
    @include('partials.auth-session-status')

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Email'), 'for' => 'email'])
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control">
            @if($errors->has('email'))
                @include('partials.input-error', ['errors' => $errors->get('email')])
            @endif
        </div>

        <!-- Password -->
        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Password'), 'for' => 'password'])
            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control">
            @if($errors->has('password'))
                @include('partials.input-error', ['errors' => $errors->get('password')])
            @endif
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">
                {{ __('Remember me') }}
            </label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
                <a class="text-decoration-none" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <button type="submit" class="btn btn-primary">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
@endsection
