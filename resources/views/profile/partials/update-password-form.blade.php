<section>
    <header class="mb-4">
        <h3 class="h5 mb-1">
            {{ __('Update Password') }}
        </h3>

        <p class="text-muted small">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Current Password'), 'for' => 'update_password_current_password'])
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="form-control">
            @if($errors->updatePassword->has('current_password'))
                @include('partials.input-error', ['errors' => $errors->updatePassword->get('current_password')])
            @endif
        </div>

        <div class="mb-3">
            @include('partials.input-label', ['label' => __('New Password'), 'for' => 'update_password_password'])
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="form-control">
            @if($errors->updatePassword->has('password'))
                @include('partials.input-error', ['errors' => $errors->updatePassword->get('password')])
            @endif
        </div>

        <div class="mb-3">
            @include('partials.input-label', ['label' => __('Confirm Password'), 'for' => 'update_password_password_confirmation'])
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="form-control">
            @if($errors->updatePassword->has('password_confirmation'))
                @include('partials.input-error', ['errors' => $errors->updatePassword->get('password_confirmation')])
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
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
