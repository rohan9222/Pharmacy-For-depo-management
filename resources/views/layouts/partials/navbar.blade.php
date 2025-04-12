<nav id="navbar" class="d-lg-none navbar navbar-expand-lg fixed-bottom bg-body-tertiary shadow-sm z-1">
    <div class="container-fluid">
        <div class="row w-100 text-center">
            <div class="col-3">
                <a href="{{ route('dashboard.index') }}" class="btn btn-light w-100"><i class="bi bi-house-door"></i></a>
            </div>
            <div class="col-3">
                <a href="{{ route('pos') }}" class="btn btn-light w-100"><i class="bi bi-pc-display-horizontal"></i></a>
            </div>
            <div class="col-3">
                <a href="{{ route('customers') }}" class="btn btn-light w-100"><i class="bi bi-people-fill"></i></a>
            </div>
            <div class="col-3">
                <a href="{{ route('customer-due-list') }}" class="btn btn-light w-100"><i class="bi bi-graph-up"></i></a>
            </div>
        </div>
    </div>
</nav>
