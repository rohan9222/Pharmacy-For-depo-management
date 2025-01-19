{{-- <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top"> --}}
<nav x-data="{ open: false }" id="navbar" class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
    <div class="container-fluid">
        <!-- Hamburger Toggle for Mobile View -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <button id="toggleSidebar" class="btn btn-sm btn-light ms-2"><i class="bi bi-arrow-left-square"></i></button>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard.index') }}">
                        {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <!-- Teams Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="teamDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->currentTeam->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="teamDropdown">
                            <li class="dropdown-header">{{ __('Manage Team') }}</li>
                            <li><a class="dropdown-item" href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">{{ __('Team Settings') }}</a></li>
                            @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                <li><a class="dropdown-item" href="{{ route('teams.create') }}">{{ __('Create New Team') }}</a></li>
                            @endcan
                            @if (Auth::user()->allTeams()->count() > 1)
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header">{{ __('Switch Teams') }}</li>
                                @foreach (Auth::user()->allTeams() as $team)
                                    <li><x-switchable-team :team="$team" /></li>
                                @endforeach
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle" width="30" height="30">
                        @else
                            {{ Auth::user()->name }}
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li class="dropdown-header">{{ __('Manage Account') }}</li>
                        <li><a class="dropdown-item" href="{{ route('user.profile') }}">{{ __('Profile') }}</a></li>
                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <li><a class="dropdown-item" href="{{ route('api-tokens.index') }}">{{ __('API Tokens') }}</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">{{ __('Log Out') }}</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
