<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Configuration | PT. OTP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/icon-otp.png') }}">
    <!-- choices css -->
    <link href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- plugin css -->
    <link href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" /> 
    <!-- preloader css -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" /> --}}
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Fixed Columns Css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">

    <script>
        function formatCurrencyInput(event) {
            let value = event.target.value;
            // Remove any characters that are not digits or commas
            value = value.replace(/[^\d,]/g, '');
            // Split the input value into integer and decimal parts
            let parts = value.split(',');
            // Format the integer part with dots as thousand separators
            let integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            // Reassemble the formatted value
            if (parts.length > 1) {
                let decimalPart = parts[1].slice(0, 3); // Limit to 3 decimal places
                value = `${integerPart},${decimalPart}`;
            } else {
                value = integerPart;
            }
            event.target.value = value;
        }
    </script>
    
    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

    {{-- select 2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Custom --}}
    <link href="{{ asset('assets/css/custom.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom2.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    
    {{-- Datatable CDN --}}
    {{-- <link href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" rel="stylesheet" /> --}}
</head>

<body>
<!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
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
                    {{-- <form class="app-search d-none d-lg-block">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Search...">
                            <button class="btn btn-primary" type="button"><i class="bx bx-search-alt align-middle"></i></button>
                        </div>
                    </form> --}}
                </div>

                <div class="d-flex">

                    {{-- <div class="dropdown d-inline-block d-lg-none ms-2">
                        <button type="button" class="btn header-item" id="page-header-search-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i data-feather="search" class="icon-lg"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                            aria-labelledby="page-header-search-dropdown">
    
                            <form class="p-3">
                                <div class="form-group m-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Search Result">

                                        <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> --}}

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

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li>
                            <a href="{{ route('dashboard') }}">
                                <i data-feather="home"></i>
                                <span data-key="t-dashboard">Dashboard</span>
                            </a>
                        </li>
                        @can('Configuration_menu')
                        <li class="menu-title" data-key="t-menu">Configuration</li>
                        @endcan
                        @can('Configuration_add_user')
                        <li>
                            <a href="{{ route('user.index') }}">
                                <i data-feather="users"></i>
                                <span>Manage User</span>
                            </a>
                        </li>
                        @endcan
                        @can('Configuration_dropdown')
                        <li>
                            <a href="{{ route('dropdown.index') }}">
                                <i class="mdi mdi-chevron-down-box"></i>
                                <span>Manage Dropdown</span>
                            </a>
                        </li>
                        @endcan

                        @can('Configuration_master_data')
                        <li class="menu-title" data-key="t-menu">Master Data</li>
                        @endcan

                        @can('Configuration_Business_Entities')
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i class="mdi mdi-domain"></i>
                                <span>Business Entities</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ route('company.index') }}">
                                        <i class="mdi mdi-office-building"></i>
                                        <span>Master Company</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('department/*') || request()->is('bagian/*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('department.index') }}">
                                        <i class="mdi mdi-graph-outline"></i>
                                        <span>Master Department</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('employee/*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('employee.index') }}">
                                        <i class="mdi mdi-account-group"></i>
                                        <span>Master Employee</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('salesman.index') }}">
                                        <i class="mdi mdi-account-tie"></i>
                                        <span>Master Salesman</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('province.index') }}">
                                        <i class="mdi mdi-map-marker"></i>
                                        <span>Master Province</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('country.index') }}">
                                        <i class="mdi mdi-wan"></i>
                                        <span>Master Country</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('costcenter.index') }}">
                                        <i class="mdi mdi-cash-multiple"></i>
                                        <span>Master Cost Center</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('customer/*') || request()->is('customeraddress/*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('customer.index') }}">
                                        <i class="mdi mdi-account-switch"></i>
                                        <span>Master Customer</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('supplier/*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('supplier.index') }}">
                                        <i class="mdi mdi-inbox-arrow-down"></i>
                                        <span>Master Supplier</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan
                        @can('Configuration_Operational')
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i class="mdi mdi-wrench"></i>
                                <span>Operational / Assets</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li class="{{ request()->is('processproduction/*') || request()->is('workcenter/*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('processproduction.index') }}">
                                        <i class="mdi mdi-cogs"></i>
                                        <span>Process Production</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('group.index') }}">
                                        <i class="mdi mdi-google-circles-group"></i>
                                        <span>Master Group</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('groupsub.index') }}">
                                        <i class="mdi mdi-lan"></i>
                                        <span>Master Group Sub</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('unit.index') }}">
                                        <i class="mdi mdi-camera-control"></i>
                                        <span>Master Unit</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('waste.index') }}">
                                        <i class="mdi mdi-recycle"></i>
                                        <span>Master Waste</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('downtime.index') }}">
                                        <i class="mdi mdi-package-down"></i>
                                        <span>Master Downtime</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('warehouse.index') }}">
                                        <i class="mdi mdi-warehouse"></i>
                                        <span>Master Warehose</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('vehicle.index') }}">
                                        <i class="mdi mdi-rv-truck"></i>
                                        <span>Master Vehicle</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan

                        @can('Configuration_Productions')
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i class="mdi mdi-cog-play"></i>
                                <span>Productions</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ route('rawmaterial.index') }}">
                                        <i class="mdi mdi-apps-box"></i>
                                        <span>Raw Material</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('wip/*') || request()->is('wiprefwip/*') || request()->is('wipref/*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('wip.index') }}">
                                        <i class="mdi mdi-cog-box"></i>
                                        <span>Master WIP</span>
                                    </a>
                                </li>
                                <li class="{{ request()->is('fg/*') || request()->is('fgref/*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('fg.index') }}">
                                        <i class="mdi mdi-check-network"></i>
                                        <span>Master Product FG</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('sparepart.index') }}">
                                        <i class="mdi mdi-archive"></i>
                                        <span>Sparepart & Aux.</span>
                                    </a>
                                </li>
                                {{-- <li>
                                    <a href="{{ route('historystock') }}">
                                        <i class="mdi mdi-clipboard-text-clock"></i>
                                        <span>History Stocks</span>
                                    </a>
                                </li> --}}
                                <li class="{{ request()->is('historystock/*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('historystock.rm') }}">
                                        <i class="mdi mdi-clipboard-text-clock"></i>
                                        <span>History Stocks</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan

                        @can('Configuration_Financial_Aspects')
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">
                                <i class="mdi mdi-cash"></i>
                                <span>Financial Aspects</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li>
                                    <a href="{{ route('termpayment.index') }}">
                                        <i class="mdi mdi-file-alert"></i>
                                        <span>Master Term Payment</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('currency.index') }}">
                                        <i class="mdi mdi-credit-card-marker"></i>
                                        <span>Master Currency</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('reason.index') }}">
                                        <i class="mdi mdi-file-question"></i>
                                        <span>Master Reason</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('approval.index') }}">
                                        <i class="mdi mdi-check-decagram"></i>
                                        <span>Master Approval</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan
                        @can('Configuration_Audit_Logs')
                        <li class="menu-title" data-key="t-menu">Logs</li>
                        <li>
                            <a href="{{ route('auditlog') }}">
                                <i class="mdi mdi-chart-donut"></i>
                                <span>Audit Logs</span>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->

        

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <!-- Start Page-content -->
            @yield('konten')
            <!-- End Page-content -->


            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            © PT Olefina Tifaplas Polikemindo 2024
                        </div>
                        {{-- <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by <a href="#!" class="text-decoration-underline">Themesbrand</a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

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
                        {{-- <form action="{{ url('http://127.0.0.1:8000/logout') }}" id="formlogout" method="POST" enctype="multipart/form-data"> --}}
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
    </div>
    <!-- END layout-wrapper -->

    
    <!-- Right Sidebar -->
    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title d-flex align-items-center p-3">

                <h5 class="m-0 me-2">Theme Customizer</h5>

                <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                    <i class="mdi mdi-close noti-icon"></i>
                </a>
            </div>

            <!-- Settings -->
            <hr class="m-0" />

            <div class="p-4">
                <h6 class="mb-3">Layout</h6>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout"
                        id="layout-vertical" value="vertical">
                    <label class="form-check-label" for="layout-vertical">Vertical</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout"
                        id="layout-horizontal" value="horizontal">
                    <label class="form-check-label" for="layout-horizontal">Horizontal</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Layout Mode</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-mode"
                        id="layout-mode-light" value="light">
                    <label class="form-check-label" for="layout-mode-light">Light</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-mode"
                        id="layout-mode-dark" value="dark">
                    <label class="form-check-label" for="layout-mode-dark">Dark</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Layout Width</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-width"
                        id="layout-width-fuild" value="fuild" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                    <label class="form-check-label" for="layout-width-fuild">Fluid</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-width"
                        id="layout-width-boxed" value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed')">
                    <label class="form-check-label" for="layout-width-boxed">Boxed</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Layout Position</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-position"
                        id="layout-position-fixed" value="fixed" onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                    <label class="form-check-label" for="layout-position-fixed">Fixed</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-position"
                        id="layout-position-scrollable" value="scrollable" onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                    <label class="form-check-label" for="layout-position-scrollable">Scrollable</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Topbar Color</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="topbar-color"
                        id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                    <label class="form-check-label" for="topbar-color-light">Light</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="topbar-color"
                        id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                    <label class="form-check-label" for="topbar-color-dark">Dark</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Size</h6>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-size"
                        id="sidebar-size-default" value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                    <label class="form-check-label" for="sidebar-size-default">Default</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-size"
                        id="sidebar-size-compact" value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                    <label class="form-check-label" for="sidebar-size-compact">Compact</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-size"
                        id="sidebar-size-small" value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                    <label class="form-check-label" for="sidebar-size-small">Small (Icon View)</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Color</h6>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-color"
                        id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                    <label class="form-check-label" for="sidebar-color-light">Light</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-color"
                        id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                    <label class="form-check-label" for="sidebar-color-dark">Dark</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-color"
                        id="sidebar-color-brand" value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                    <label class="form-check-label" for="sidebar-color-brand">Brand</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Direction</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-direction"
                        id="layout-direction-ltr" value="ltr">
                    <label class="form-check-label" for="layout-direction-ltr">LTR</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-direction"
                        id="layout-direction-rtl" value="rtl">
                    <label class="form-check-label" for="layout-direction-rtl">RTL</label>
                </div>

            </div>

        </div> <!-- end slimscroll-menu-->
    </div>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    {{-- <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <!-- pace js -->
    <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Plugins js-->
    <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- dashboard init -->
    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- custom -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/bulkaction.js') }}"></script>

    <!-- choices js -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <!-- init js -->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <!-- Fixed Columns js -->
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>

    <!-- FORM LOAD JS -->
    <script src="{{ asset('assets/js/formLoad.js') }}"></script>

    {{-- Datatable CDN --}}
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script> --}}

    <script>
        // Rupiah Format 
        function formatCurrency(number, prefix) {
            var number_string = number.replace(/[^.\d]/g, '').toString(),
                split = number_string.split('.'),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{1,3}/gi);
            if (ribuan) {
                separator = sisa ? ',' : '';
                rupiah += separator + ribuan.join(',');
            }
            rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }
    </script>

    <script>
        function formatNumberInput(event) {
            let input = event.target;
            let value = input.value.replace(/[^0-9,.]/g, "");
            value = value.replace(/\./g, "");
            let parts = value.split(",");
            let integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            if (parts.length > 1) {
                let decimalPart = parts[1].substring(0, 6);
                input.value = integerPart + "," + decimalPart;
            } else {
                input.value = integerPart;
            }
        }
        document.querySelectorAll(".number-format").forEach(input => {
            input.addEventListener("input", formatNumberInput);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const dateFrom = document.querySelector('input[name="dateFrom"]');
            const dateTo = document.querySelector('input[name="dateTo"]');
            const dateToError = document.getElementById("dateToError");

            function validateDateTo() {
                if (dateFrom.value && dateTo.value && dateTo.value < dateFrom.value) {
                    dateTo.classList.add("is-invalid");
                    dateToError.classList.remove("d-none");
                    dateTo.value = "";
                } else {
                    dateTo.classList.remove("is-invalid");
                    dateToError.classList.add("d-none");
                }
            }

            dateTo.addEventListener("change", validateDateTo);
            dateFrom.addEventListener("change", validateDateTo);
        });
    </script>
</body>

</html>