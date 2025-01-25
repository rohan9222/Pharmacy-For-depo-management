<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ $value }}
            </div>
        @endsession

        <form class="form" method="POST" action="{{ route('login') }}">
            @csrf
            <header>
                <h1 class="text-center">Sign In</h1>
            </header>
            <div class="form-group">
                <input type="email" id="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <label for="email">{{ __('Email address') }}</label>
            </div>

            <div class="form-group">
                <input type="password" id="password"  name="password" required autocomplete="current-password" />
                <label for="password">{{ __('Password') }}</label>
            </div>

            <div class="form-group">
                <input type="checkbox" id="remember_me" name="remember" />
                <label for="remember_me">{{ __('Remember me') }}</label>
            </div>

            @if (Route::has('password.request'))
                <p><a class="link"  href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a></p>
            @endif
            <button type="submit">{{ __('Log in') }}</button>
            @if (Route::has('register'))
                <p class="text-center">{{ __("Don't have an account?") }}
                    <a class="link" href="{{ route('register') }}">Sign up</a>
                </p>
            @endif
        </form>
    </x-authentication-card>
</x-guest-layout>
