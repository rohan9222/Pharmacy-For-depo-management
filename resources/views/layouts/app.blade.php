<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css','resources/sass/app.scss', 'resources/js/app.js'])

        <!-- Styles -->
        <style>
            body {
                min-height: 100vh;
                overflow-x: hidden;
            }
            .sidebar {
                height: 100vh;
                width: 250px;
                position: fixed;
                top: 0;
                left: 0;
                padding-top: 1rem;
                transition: width 0.3s;
            }
            .sidebar.collapsed {
                width: 70px;
            }
            .sidebar a {
                padding: 10px 15px;
                display: block;
                text-decoration: none;
            }
            .sidebar a:hover {
                background: #495057;
            }
            .sidebar.collapsed a {
                text-align: center;
                padding: 10px 0;
            }
            .sidebar.collapsed .sidebar-text {
                display: none;
            }
            .content {
                margin-left: 250px;
                padding: 20px;
                transition: margin-left 0.3s;
            }
            .content.collapsed {
                margin-left: 70px;
            }
            .navbar {
                left: 250px;
                transition: left 0.3s;
            }
            .navbar.collapsed {
                left: 70px;
            }
            #sidebar, #navbar {
                background-color: #fff !important;
            }
        </style>
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />
        {{-- old code --}}
        {{-- <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <div id="sidebar" class="sidebar">
                <button id="toggleSidebar" class="btn btn-sm btn-light mb-3 ms-2">Toggle</button>
                <a href="#home"><span class="sidebar-text">Home</span></a>
                <a href="#profile"><span class="sidebar-text">Profile</span></a>
                <a href="#settings"><span class="sidebar-text">Settings</span></a>
                <a href="#reports"><span class="sidebar-text">Reports</span></a>
            </div>
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
             <!-- Main Content -->
            <div id="content" class="content pt-5 mt-5">
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div> --}}

        @include('layouts.partial.sidebar')
        @livewire('navigation-menu')

        <div id="content" class="content pt-5 mt-5">
            <main>
                {{ $slot }}
            </main>
        </div>
        @stack('modals')

        @livewireScripts

        <script>
            const toggleButton = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const navbar = document.getElementById('navbar');
            const content = document.getElementById('content');

            toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                navbar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');
            });
        </script>
    </body>
</html>
