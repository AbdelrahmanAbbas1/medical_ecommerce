@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')
    <div class="mb-3 text-muted small">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Password'), 'for' => 'password'])
            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control">
            @if($errors->has('password'))
                @include('partials.input-error', ['errors' => $errors->get('password')])
            @endif
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                {{ __('Confirm') }}
            </button>
        </div>
    </form>
@endsection
