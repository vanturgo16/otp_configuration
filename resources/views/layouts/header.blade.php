
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="https://sso.olefinatifaplas.my.id/login" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/icon-otp.png') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="" height="40">
                    </span>
                </a>

                <a href="https://sso.olefinatifaplas.my.id/login" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/icon-otp.png') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="" height="40">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative border rounded">
                    <input type="text" class="form-control" placeholder="Search..." id="searchInput">
                    <button class="btn btn-primary" type="button"><i class="bx bx-search-alt align-middle"></i></button>
                </div>
            </form>
        </div>

        <div class="d-flex">
            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item right-bar-toggle me-2">
                    <i data-feather="settings" class="icon-lg"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/userbg.png') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#logout"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout</a>
                </div>
            </div>

        </div>
    </div>
</header>

{{-- Modal Logout --}}
<div class="modal fade" id="logout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Select "Logout" below if you are ready to end your current session.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <form action="{{ route('logout') }}" id="formlogout" method="POST" enctype="multipart/form-data">
                    @csrf
                    <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-logout label-icon"></i>Logout</button>
                </form>
                <script>
                    document.getElementById('formlogout').addEventListener('submit', function(event) {
                        if (!this.checkValidity()) {
                            event.preventDefault(); // Prevent form submission if it's not valid
                            return false;
                        }
                        var submitButton = this.querySelector('button[name="sb"]');
                        submitButton.disabled = true;
                        submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                        return true; // Allow form submission
                    });
                </script>
            </div>
        </div>
    </div>
</div>