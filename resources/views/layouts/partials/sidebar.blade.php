<div id="sidebar" class="z-3 sidebar shadow-sm d-flex flex-column p-3 bg-body-tertiary">
    <a href="/" class="navbar-brand d-flex align-items-center mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <!-- Logo -->
        <x-application-mark class="block h-9 w-auto" />
        {{-- <span class="sidebar-text fs-4">Sidebar</span> --}}
    </a>
    <hr>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('dashboard.index') }}" class="nav-link link-body-emphasis {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-pie-chart-fill me-2"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>

        {{-- Supporters --}}
        <li class="nav-item">
            <a href="#" class="nav-link link-body-emphasis {{ in_array(request()->route()->getName(), ['suppliers', 'customers', 'delivery-man']) ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#supporter-collapse" aria-expanded="false">
                <i class="bi bi-people me-2"></i>
                <span class="sidebar-text">Supporters</span>
                <i class="bi bi-chevron-down ms-auto toggle-icon sidebar-text"></i>
            </a>

            <div class="collapse" id="supporter-collapse">
                <ul class="ms-4 btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    @canany(['create-customer', 'edit-customer'])
                        <li>
                            <a href="{{ route('customers') }}" class="nav-link link-body-emphasis {{ request()->routeIs('customers') ? 'active' : '' }}">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Customers</span>
                            </a>
                        </li>
                    @endcanany
                    @canany(['create-supplier', 'edit-supplier'])
                        <li>
                            <a href="{{ route('suppliers') }}" class="nav-list nav-link link-body-emphasis {{ request()->routeIs('suppliers') ? 'active' : '' }}">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Suppliers/Manufacturer</span>
                            </a>
                        </li>
                    @endcanany
                    @canany(['create-delivery-man', 'edit-delivery-man'])
                        <li>
                            <a href="{{ route('delivery-man') }}" class="nav-link link-body-emphasis {{ request()->routeIs('delivery-man') ? 'active' : '' }}">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Delivery Man</span>
                            </a>
                        </li>
                    @endcanany
                </ul>
            </div>
        </li>

        {{-- Medicines --}}
        <li class="nav-item">
            <a href="#" class="nav-link link-body-emphasis {{ in_array(request()->route()->getName(), ['categories', 'medicines']) ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#medicines-collapse" aria-expanded="false">
                <i class="bi bi-capsule-pill me-2"></i>
                <span class="sidebar-text">Medicines</span>
                <i class="bi bi-chevron-down ms-auto toggle-icon sidebar-text"></i>
            </a>

            <div class="collapse" id="medicines-collapse">
                <ul class="ms-4 btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    @canany(['create-category', 'edit-category'])
                        <li class="nav-item">
                            <a href="{{ route('categories') }}" class="nav-link link-body-emphasis {{ request()->routeIs('categories') ? 'active' : '' }}">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Categories</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pack-size') }}" class="nav-link link-body-emphasis {{ request()->routeIs('pack-size') ? 'active' : '' }}">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Pack Size</span>
                            </a>
                        </li>
                    @endcanany
                    @canany(['create-medicine', 'edit-medicine', 'view-medicine'])
                        <li class="nav-item">
                            <a href="{{ route('medicines') }}" class="nav-link link-body-emphasis {{ request()->routeIs('medicines') ? 'active' : '' }}">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Medicine List</span>
                            </a>
                        </li>
                    @endcanany
                </ul>
            </div>
        </li>

        {{-- Stock Medicines --}}
        <li class="nav-item">
            <a href="#" class="nav-link link-body-emphasis {{ in_array(request()->route()->getName(), ['stock-medicines', 'stock-medicines-list']) ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#stock-medicines-collapse" aria-expanded="false">
                <i class="bi bi-prescription2"></i>
                <span class="sidebar-text">Stock Medicines</span>
                <i class="bi bi-chevron-down ms-auto toggle-icon sidebar-text"></i>
            </a>

            <div class="collapse" id="stock-medicines-collapse">
                <ul class="ms-4 btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    @canany(['create-medicine-stock', 'edit-medicine-stock'])
                        <li class="nav-item">
                            <a href="{{ route('stock-medicines') }}" class="nav-link link-body-emphasis {{ request()->routeIs('stock-medicines') ? 'active' : '' }}">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Stock IN</span>
                            </a>
                        </li>
                    @endcanany
                    @canany(['view-medicine-stock'])
                        <li class="nav-item">
                            <a href="{{ route('stock-medicines-list') }}" class="nav-link link-body-emphasis {{ request()->routeIs('stock-medicines-list') ? 'active' : '' }}">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Stock List</span>
                            </a>
                        </li>
                    @endcanany
                </ul>
            </div>
        </li>

        {{-- sales Medicines --}}
        <li class="nav-item">
            <a href="#" class="nav-link link-body-emphasis {{ in_array(request()->route()->getName(), ['sales-medicines', 'sales-medicines-list']) ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#sales-medicines-collapse" aria-expanded="false">
                <i class="bi bi-receipt"></i>
                <span class="sidebar-text">Sales Medicines</span>
                <i class="bi bi-chevron-down ms-auto toggle-icon sidebar-text"></i>
            </a>

            <div class="collapse" id="pos-collapse">
                <ul class="ms-4 btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    @canany('invoice')
                        <li class="nav-item">
                            <a href="{{ route('pos') }}" class="nav-link link-body-emphasis {{ request()->routeIs('pos') ? 'active' : '' }}">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">New Sales</span>
                            </a>
                        </li>
                    @endcanany

                    @canany('view-invoice')
                        <li class="nav-item">
                            <a href="{{ route('sales-medicines-list') }}" class="nav-link link-body-emphasis {{ request()->routeIs('sales-medicines-list') ? 'active' : '' }}">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Invoice History</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('return-medicines-list') }}" class="nav-link link-body-emphasis {{ request()->routeIs('return-medicines-list') ? 'active' : '' }}">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Return History</span>
                            </a>
                        </li>
                    @endcanany
                </ul>
            </div>
        </li>
{{-- all summary and reports --}}
        <li class="nav-item">
            <a href="#" class="nav-link link-body-emphasis {{ in_array(request()->route()->getName(), ['summary.list']) ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#report-collapse" aria-expanded="false">
                <i class="bi bi-graph-up"></i>
                <span class="sidebar-text">Reports</span>
                <i class="bi bi-chevron-down ms-auto toggle-icon sidebar-text"></i>
            </a>
            <div class="collapse" id="report-collapse">
                <ul class="ms-4 btn-toggle-nav list-unstyled fw-normal pb-1 small">
                    @can('view-report')
                        <li class="nav-item">
                            <a href="{{ route('summary.list') }}" class="nav-link link-body-emphasis">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Summary</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link link-body-emphasis">
                                <i class="bi bi-caret-right-fill me-2"></i>
                                <span class="sidebar-text">Reports</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>

{{-- site-settings --}}
        @if (Auth::user()->hasRole('Super Admin'))
            <li class="nav-item">
                <a href="{{ route('site-settings') }}" class="nav-link link-body-emphasis">
                    <i class="bi bi-gear me-2"></i>
                    <span class="sidebar-text">Site Settings</span>
                </a>
            </li>
        @endif

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
            $('[data-bs-target="#home-collapse"]').on('click', function () {
                    $(this).find('.toggle-icon').toggleClass('bi-chevron-down bi-chevron-up');
            });
        });
  });
</script>

@endpush
