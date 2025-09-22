<section>
    <header class="mb-4">
        <h3 class="h5 mb-1">
            {{ __('Profile Information') }}
        </h3>

        <p class="text-muted small">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Name'), 'for' => 'name'])
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="form-control">
            @if($errors->has('name'))
                @include('partials.input-error', ['errors' => $errors->get('name')])
            @endif
        </div>

        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Email'), 'for' => 'email'])
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="form-control">
            @if($errors->has('email'))
                @include('partials.input-error', ['errors' => $errors->get('email')])
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link p-0 text-decoration-none">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success small mt-2">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-muted small mb-0"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
