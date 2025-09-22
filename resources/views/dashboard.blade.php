@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
    <h2 class="h4 mb-0">
        {{ __('Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-4">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
@endsection
