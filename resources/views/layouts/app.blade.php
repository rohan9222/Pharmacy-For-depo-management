<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ImpexPharma') }}</title>
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css','resources/sass/app.scss', 'resources/js/app.js'])

        <!-- Styles -->

        <style>
            #toggleSidebar {
                position: absolute;
                left: 15rem;
                font-size: larger;
                font-weight: bolder;
            }

            .collapsed #toggleSidebar {
                left: 3.7rem !important;
            }

            .sidebar {
                height: 100vh;
                width: 250px;
                position: fixed;
                top: 0;
                left: 0;
                padding-top: 1rem;
                transition: width 0.3s;
                background-color: #343a40;
                color: #fff;
            }

            .sidebar.collapsed {
                width: 70px;
            }

            .sidebar.collapsed:hover {
                width: 250px;
            }

            .sidebar a {
                padding: 10px 15px;
                display: block;
                text-decoration: none;
                color: #fff;
                transition: color 0.3s;
            }

            .nav .nav-link:hover, .nav .nav-link.active {
                background: #228cf7;
            }

            .sidebar.collapsed a {
                text-align: center;
                padding: 10px 0;
            }

            .sidebar.collapsed:hover a {
                text-align: left;
                padding: 10px 15px;
            }

            .nav-item:hover .collapse:not(.show) {
                display: block;
            }

            .sidebar.collapsed .sidebar-text {
                display: none;
            }
            .sidebar.collapsed .sidebar-logo {
                width: 100% !important;
            }

            .sidebar.collapsed:hover .sidebar-logo {
                width: 25% !important;
            }

            .sidebar.collapsed:hover .sidebar-text {
                display: inline;
            }

            .content {
                padding: 20px;
                transition: margin-left 0.3s;
            }

            @media (min-width: 992px) {
                .content {
                    margin-left: 250px;
                }

                .content.collapsed {
                    margin-left: 70px;
                }
            }

            #sidebar,
            #navbar {
                background-color: #fff !important;
            }
            .sidebar.collapsed:hover + .navbar {
                z-index: -1; 
            }
        </style>
        @stack('styles')
        @livewireStyles
    </head>
    <body class="font-sans antialiased"></body>
        @include('layouts.partials.sidebar')
        @livewire('navigation-menu')
        @include('layouts.partials.navbar')

        <div id="content" class="content pt-5 mt-5">
            <main>
                {{ $slot }}
            </main>
        </div>
        @stack('modals')

        @livewireScripts

        @stack('scripts')
    </body>
</html>
