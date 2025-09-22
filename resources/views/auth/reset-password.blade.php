@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Email'), 'for' => 'email'])
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="form-control">
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

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
@endsection
