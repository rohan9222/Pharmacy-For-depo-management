<x-guest-layout>
    <div class="card mt-5 mx-auto shadow-sm p-4" style="max-width: 400px;">
        <div class="text-center mb-4">
            <x-authentication-card-logo class="w-50" />
        </div>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="alert alert-success" role="alert">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none">{{ __('Forgot your password?') }}</a>
                @endif

                <button type="submit" class="btn btn-primary ms-3 p-2">{{ __('Log in') }}</button>
            </div>
        </form>
    </div>
</x-guest-layout>
