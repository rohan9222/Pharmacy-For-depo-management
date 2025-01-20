<div id="sidebar" class="sidebar shadow-sm d-flex flex-column p-3 bg-body-tertiary">
    <a href="/" class="navbar-brand d-flex align-items-center mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <!-- Logo -->
        <x-application-mark class="block h-9 w-auto" />
        {{-- <span class="sidebar-text fs-4">Sidebar</span> --}}
    </a>
    <hr>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link" aria-current="page">
                <i class="bi bi-house-door-fill me-2"></i>
                <span class="sidebar-text">Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('dashboard.index') }}" class="nav-link link-body-emphasis {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-pie-chart-fill me-2"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('suppliers') }}" class="nav-list nav-link link-body-emphasis {{ request()->routeIs('suppliers') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i>
                <span class="sidebar-text">Suppliers/Manufacturer</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('customers') }}" class="nav-link link-body-emphasis {{ request()->routeIs('customers') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i>
                <span class="sidebar-text">Customers</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('delivery-man') }}" class="nav-link link-body-emphasis {{ request()->routeIs('delivery-man') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i>
                <span class="sidebar-text">Delivery Man</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('medicines') }}" class="nav-link link-body-emphasis {{ request()->routeIs('medicines') ? 'active' : '' }}">
                <i class="bi bi-capsule-pill me-2"></i>
                <span class="sidebar-text">Medicine</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('categories') }}" class="nav-link link-body-emphasis {{ request()->routeIs('categories') ? 'active' : '' }}">
                <i class="bi bi-capsule-pill me-2"></i>
                <span class="sidebar-text">Categories</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link link-body-emphasis">
                <i class="bi bi-capsule-pill me-2"></i>
                <span class="sidebar-text">Order</span>
            </a>
        </li>
    </ul>


    <hr>
    <div class="dropdown">
        {{-- <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> --}}
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none">
            <img src="{{ Auth::user()->profile_photo_url }}" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong class="sidebar-text">{{ Auth::user()->name }}</strong>
        </a>
        {{-- <ul class="dropdown-menu text-small shadow sidebar-text">
            <li><a class="dropdown-item" href="#">New project...</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul> --}}
    </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
      $(document).ready(function () {
          const sidebar = $('#sidebar');
          const content = $('#content');
          const navbar = $('#navbar');
          const toggleButton = $('#toggleSidebar');
          const storageKey = 'sidebarCollapsed';

          // Restore the state from localStorage
          const isCollapsed = localStorage.getItem(storageKey) === 'true';
          if (isCollapsed) {
              sidebar.addClass('collapsed');
              content.addClass('collapsed');
              navbar.addClass('collapsed');
              toggleButton.find('i').removeClass('bi-arrow-left-square').addClass('bi-arrow-right-square');
          }

          // Toggle sidebar and save state in localStorage
          toggleButton.click(function () {
              let icon = $(this).find('i');

              if (icon.hasClass('bi-arrow-left-square')) {
                  icon.removeClass('bi-arrow-left-square').addClass('bi-arrow-right-square');
                  localStorage.setItem(storageKey, true); // Save collapsed state
              } else {
                  icon.removeClass('bi-arrow-right-square').addClass('bi-arrow-left-square');
                  localStorage.setItem(storageKey, false); // Save expanded state
              }

              // Toggle classes
              sidebar.toggleClass('collapsed');
              content.toggleClass('collapsed');
              navbar.toggleClass('collapsed');
          });
      });
  });
</script>

@endpush
