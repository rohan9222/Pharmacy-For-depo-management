<div id="sidebar" class="d-none d-lg-block z-1 sidebar d-flex flex-column p-3 bg-body-tertiary">
    {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
     --}}
        <!-- Navigation Links -->
    {{-- <div class="collapse navbar-collapse" id="navbarNav"> --}}
     
        {{-- <a href="/" class="navbar-brand d-flex align-items-center mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <!-- Logo -->
            <x-application-mark class="sidebar-logo w-25" />
            {!! siteUrlSettings('site_name') !!}
        </a> --}}

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard.index') }}" class="nav-link link-body-emphasis {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-pie-chart-fill me-2"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            {{-- Supporters --}}
            @if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasPermissionTo('admin-role'))
                <li class="nav-item">
                    <a href="#" class="nav-link link-body-emphasis {{ in_array(request()->route()->getName(), ['product-target-manage', 'supporter.list']) ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#admin-collapse" aria-expanded="false">
                        <i class="bi bi-people me-2"></i>
                        <span class="sidebar-text">Admin Panel Report</span>
                        <i class="bi bi-chevron-down ms-auto toggle-icon sidebar-text"></i>
                    </a>

                    <div class="collapse" id="admin-collapse">
                        <ul class="ms-4 btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            @if (Auth::user()->role == 'Manager' || Auth::user()->hasRole('Depo Incharge') || Auth::user()->hasRole('Super Admin') )
                                <li>
                                    <a href="{{ route('supporter.list', ['type' => 'manager']) }}" class="nav-link link-body-emphasis {{ request()->routeIs('supporter.list') && request('type') === 'manager' ? 'active' : '' }}">
                                        <i class="bi bi-caret-right-fill me-2"></i>
                                        <span class="sidebar-text">Managers</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('supporter.list', ['type' => 'zse']) }}" class="nav-link link-body-emphasis {{ request()->routeIs('supporter.list') && request('type') === 'zse' ? 'active' : '' }}">
                                        <i class="bi bi-caret-right-fill me-2"></i>
                                        <span class="sidebar-text">Zonal Sales Executives</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('supporter.list', ['type' => 'tse']) }}" class="nav-link link-body-emphasis {{ request()->routeIs('supporter.list') && request('type') === 'tse' ? 'active' : '' }}">
                                        <i class="bi bi-caret-right-fill me-2"></i>
                                        <span class="sidebar-text">Territory Sales Executives</span>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->role == 'Zonal Sales Executive' )
                                <li>
                                    <a href="{{ route('supporter.list', ['type' => 'zse']) }}" class="nav-link link-body-emphasis {{ request()->routeIs('supporter.list') && request('type') === 'zse' ? 'active' : '' }}">
                                        <i class="bi bi-caret-right-fill me-2"></i>
                                        <span class="sidebar-text">Zonal Sales Executives</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('supporter.list', ['type' => 'tse']) }}" class="nav-link link-body-emphasis {{ request()->routeIs('supporter.list') && request('type') === 'tse' ? 'active' : '' }}">
                                        <i class="bi bi-caret-right-fill me-2"></i>
                                        <span class="sidebar-text">Territory Sales Executives</span>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->role == 'Territory Sales Executive' )
                                <li>
                                    <a href="{{ route('supporter.list', ['type' => 'tse']) }}" class="nav-link link-body-emphasis {{ request()->routeIs('supporter.list') && request('type') === 'tse' ? 'active' : '' }}">
                                        <i class="bi bi-caret-right-fill me-2"></i>
                                        <span class="sidebar-text">Territory Sales Executives</span>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->hasRole('Super Admin') || Auth::user()->role == 'Manager' || Auth::user()->hasRole('Depo Incharge') || Auth::user()->hasRole('Zonal Sales Executive'))
                                <li>
                                    <a href="{{ route('product-target-manage') }}" class="nav-link link-body-emphasis {{ request()->routeIs('product-target-manage') ? 'active' : '' }}">
                                        <i class="bi bi-caret-right-fill me-2"></i>
                                        <span class="sidebar-text">Product Target Manage</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

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
                <a href="#" class="nav-link link-body-emphasis {{ in_array(request()->route()->getName(), ['stock-medicines', 'stock-medicines-list', 'stock-invoice-list']) ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#stock-medicines-collapse" aria-expanded="false">
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
                            <li class="nav-item">
                                <a href="{{ route('stock-invoice-list') }}" class="nav-link link-body-emphasis {{ request()->routeIs('stock-invoice-list') ? 'active' : '' }}">
                                    <i class="bi bi-caret-right-fill me-2"></i>
                                    <span class="sidebar-text">Stock Invoice List</span>
                                </a>
                            </li>
                        @endcanany
                        @canany(['view-medicine-stock'])
                            <li class="nav-item">
                                <a href="{{ route('stock-medicines-list') }}" class="nav-link link-body-emphasis {{ request()->routeIs('stock-medicines-list') ? 'active' : '' }}">
                                    <i class="bi bi-caret-right-fill me-2"></i>
                                    <span class="sidebar-text">Stock Medicine List</span>
                                </a>
                            </li>
                            
                        @endcanany
                    </ul>
                </div>
            </li>

            {{-- sales Medicines --}}
            <li class="nav-item">
                <a href="#" class="nav-link link-body-emphasis {{ in_array(request()->route()->getName(), ['pos', 'sales-medicines-list','sales-delivery-history','return-medicines-list']) ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#sales-medicines-collapse" aria-expanded="false">
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
                        @can('delivery-history')
                            <li class="nav-item">
                                <a href="{{ route('sales-delivery-history') }}" class="nav-link link-body-emphasis {{ request()->routeIs('sales-delivery-history') ? 'active' : '' }}">
                                    <i class="bi bi-caret-right-fill me-2"></i>
                                    <span class="sidebar-text">Delivery List</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
    {{-- all target and reports --}}
            <li class="nav-item">
                <a href="#" class="nav-link link-body-emphasis {{ in_array(request()->route()->getName(), ['target-history', 'customer-due-list','due-list', 'collection-list','report.index']) ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#report-collapse" aria-expanded="false">
                    <i class="bi bi-graph-up"></i>
                    <span class="sidebar-text">Reports</span>
                    <i class="bi bi-chevron-down ms-auto toggle-icon sidebar-text"></i>
                </a>
                <div class="collapse" id="report-collapse">
                    <ul class="ms-4 btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        @can('view-report')
                            <li class="nav-item">
                                <a href="{{ route('target-history') }}" class="nav-link link-body-emphasis {{ request()->routeIs('target-history') ? 'active' : '' }}">
                                    <i class="bi bi-caret-right-fill me-2"></i>
                                    <span class="sidebar-text">Target History</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('due-list') }}" class="nav-link link-body-emphasis {{ request()->routeIs('due-list') ? 'active' : '' }}">
                                    <i class="bi bi-caret-right-fill me-2"></i>
                                    <span class="sidebar-text">Due List</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('customer-due-list') }}" class="nav-link link-body-emphasis {{ request()->routeIs('customer-due-list') ? 'active' : '' }}">
                                    <i class="bi bi-caret-right-fill me-2"></i>
                                    <span class="sidebar-text">Customer Due List</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('collection-list') }}" class="nav-link link-body-emphasis {{ request()->routeIs('collection-list') ? 'active' : '' }}">
                                    <i class="bi bi-caret-right-fill me-2"></i>
                                    <span class="sidebar-text">Collection Report</span>
                                </a>
                            </li>
                        @endcan
                        @if (Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Depo Incharge'))
                            <li class="nav-item">
                                <a href="{{ route('report.index') }}" class="nav-link link-body-emphasis {{ request()->routeIs('report.index') ? 'active' : '' }}">
                                    <i class="bi bi-caret-right-fill me-2"></i>
                                    <span class="sidebar-text">Others Report</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>

    {{-- site-settings --}}
            @if (Auth::user()->hasRole('Super Admin'))
                <li class="nav-item">
                    <a href="{{ route('site-settings') }}" class="nav-link link-body-emphasis {{ request()->routeIs('site-settings') ? 'active' : '' }}">
                        <i class="bi bi-gear me-2"></i>
                        <span class="sidebar-text">Site Settings</span>
                    </a>
                </li>
            @endif

            {{-- <li class="nav-item">
                <a href="#" class="nav-link link-body-emphasis">
                    <i class="bi bi-capsule-pill me-2"></i>
                    <span class="sidebar-text">Order</span>
                </a>
            </li> --}}
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
{{-- </div> --}}

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
