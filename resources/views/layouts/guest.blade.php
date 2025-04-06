<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])

        <!-- Styles -->
        <style>
            .divider:after,
            .divider:before {
                content: "";
                flex: 1;
                height: 1px;
                background: #eee;
            }
            .h-custom {
                height: calc(100% - 73px);
            }
            @media (max-width: 450px) {
                .h-custom {
                    height: 100%;
                }
            }
            :root {
  --clr-primary: #10a37f;
  --clr-neutral-200: #c2c8d0;
  --clr-neutral-300: #999;
  --transition: 0.2s ease;
  --br: 3px;
}

* {
  box-sizing: border-box;
}

.form {
  --spacing-default: 16px;
  --spacing-top: var(--spacing-default);
  --spacing-left: var(--spacing-default);
  --spacing-right: var(--spacing-default);
  --spacing-bottom: var(--spacing-default);
  --spacing-top-offset: -0.7;
  --spacing-left-offset: 0.5;
  display: grid;
  gap: 1rem;
  margin-block: 4rem;
}

.form-group {
  position: relative;
}

input,
button[type="submit"] {
  border-radius: var(--br);
  padding-block: var(--spacing-top) var(--spacing-bottom);
  padding-inline: var(--spacing-right) var(--spacing-left);
  font: inherit;
}

input {
  display: block;
  width: 300px;
  border: 1px solid var(--clr-neutral-200);
  transition: var(--transition);
}

input:focus {
  outline: none;
  border-color: var(--clr-primary);
}

label {
  position: absolute;
  top: var(--spacing-top);
  left: var(--spacing-left);
  color: var(--clr-neutral-300);
  font-size: 16px;
  pointer-events: none;
  transition: var(--transition);
}

input:focus ~ label,
input:valid ~ label {
  top: calc(var(--spacing-top) * var(--spacing-top-offset));
  left: calc(var(--spacing-left) * var(--spacing-left-offset));
  font-size: 15px;
  background-color: #fff;
  color: var(--clr-primary);
  padding-inline: 4px;
}

button[type="submit"] {
  display: inline-block;
  background-color: var(--clr-primary);
  color: #fff;
  border: 0;
  cursor: pointer;
}

.link {
  color: var(--clr-primary);
  text-decoration: none;
}

/* general styling */
body {
  display: grid;
  place-items: center;
  min-height: 100vh;
  margin: 0;
  font-family: system-ui, sans-serif;
}

p {
  margin: 0;
}

.text-center {
  text-align: center;
}
        </style>
        @livewireStyles
    </head>
    <body>
        <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
            {{ $slot }}
        </div>
        @livewireScripts
    </body>
</html>
