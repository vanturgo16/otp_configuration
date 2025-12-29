<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Configuration | PT. OTP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/icon-otp.png') }}" />
    <!-- choices css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}" />
    <!-- plugin css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" />
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" />
    <!-- Responsive datatable examples -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" /> 
    <!-- preloader css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/preloader.min.css') }}" />
    <!-- Bootstrap Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" />
    <!-- Icons Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icons.min.css') }}" />
    <!-- App Css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.min.css') }}" id="app-style" />
    <!-- Fixed Columns Css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/fixedcolumns/css/fixedColumns.dataTables.min.css') }}" />
    <!-- Select2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/css/select2.min.css') }}" />
    <!-- Custom-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom2.css') }}" />
    
    <!-- Jquery-->
    <script src="{{ asset('assets/libs/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery/xlsx/0.17.4/xlsx.full.min.js') }}"></script>
    <!-- Currency Input Nominal -->
    <script src="{{ asset('assets/js/currencyInput.js') }}"></script>
</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- Header -->
        @include('layouts.header')
        <!-- End Header -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">
            @include('layouts.sidebar')
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <!-- Start Page-content -->
            @yield('konten')
            <!-- End Page-content -->
            
            <footer class="footer" style="position: fixed; z-index: 10;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6 footer-text">
                            © PT Olefina Tifaplas Polikemindo {{ date('Y') }}
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->
    
    <!-- Right Sidebar -->
    <div class="right-bar">
        @include('layouts.right_sidebar')
    </div>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
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
    <!-- Select2 -->
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <!-- custom -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/bulkaction.js') }}"></script>
    <!-- choices js -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <!-- init js -->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <!-- validate js -->
    <script src="{{ asset('assets/libs/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <!-- Fixed Columns js -->
    <script src="{{ asset('assets/libs/fixedcolumns/js/dataTables.fixedColumns.min.js') }}"></script>
    <!-- FORM LOAD JS -->
    <script src="{{ asset('assets/js/formLoad.js') }}"></script>

    <!-- addition function -->
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