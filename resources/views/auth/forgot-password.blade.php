@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <div class="mb-3 text-muted">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    @include('partials.auth-session-status')

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Email'), 'for' => 'email'])
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control">
            @if($errors->has('email'))
                @include('partials.input-error', ['errors' => $errors->get('email')])
            @endif
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
@endsection
