@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">

        @include('layouts.alert')

        <!-- Modal for bulk delete confirmation -->
        <div class="modal fade" id="deleteselected" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row text-center">
                            <p>Are you sure you want to delete the selected items?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-deleteselected" onclick="bulkDeleted('{{ route('customer.deleteselected') }}')"><i class="mdi mdi-delete label-icon"></i>Delete</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Search -->
        <div class="modal fade" id="sort" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"><i class="mdi mdi-filter label-icon"></i> Advance Sort & Filter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('customer.index') }}" id="formfilter" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label">Customer Code</label>
                                    <input class="form-control" name="customer_code" type="text" value="{{ $customer_code }}" placeholder="Input Customer Code..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Status</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="status">
                                        <option value="" selected>--All--</option>
                                        <option value="Active" @if($status == 'Active') selected @endif>Active</option>
                                        <option value="Not Active" @if($status == 'Not Active') selected @endif>Not Active</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Customer Name</label>
                                    <input class="form-control" name="name" type="text" value="{{ $name }}" placeholder="Input Customer Name..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Remarks</label>
                                    <input class="form-control" name="remark" type="text" value="{{ $remark }}" placeholder="Input Remarks..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Tax Number</label>
                                    <input class="form-control" name="tax_number" type="text" value="{{ $tax_number }}" placeholder="Input Tax Number..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Tax Code</label>
                                    <input class="form-control" name="tax_number" type="text" value="{{ $tax_number }}" placeholder="Input Tax Code..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Salesman</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_salesmen">
                                        <option value="" selected>--Select Salesman--</option>
                                        @foreach($salesmans as $data)
                                            <option value="{{ $data->id }}" @if($id_master_salesmen == $data->id) selected="selected" @endif>{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Currency</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_currencies">
                                        <option value="" selected>--Select Currency--</option>
                                        @foreach($currencies as $data)
                                            <option value="{{ $data->id }}" @if($id_master_currencies == $data->id) selected="selected" @endif>{{ $data->currency }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Term Payment</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_term_payments">
                                        <option value="" selected>--Select Term Payment--</option>
                                        @foreach($terms as $data)
                                            <option value="{{ $data->id }}" @if($id_master_term_payments == $data->id) selected="selected" @endif>{{ $data->term_payment }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Ppn</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="ppn">
                                        <option value="" selected>--Select Ppn--</option>
                                        <option value="Include" @if($ppn == 'Include') selected="selected" @endif>Include</option>
                                        <option value="Exclude" @if($ppn == 'Exclude') selected="selected" @endif>Exclude</option>
                                    </select>
                                </div>
                                <hr class="mt-2">
                                <div class="col-4 mb-2">
                                    <label class="form-label">Filter Date</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="searchDate">
                                        <option value="All" @if($searchDate == 'All') selected @endif>All</option>
                                        <option value="Custom" @if($searchDate == 'Custom') selected @endif>Custom Date</option>
                                    </select>
                                </div>
                                <div class="col-4 mb-2">
                                    <label class="form-label">Date From</label>
                                    <input type="date" name="startdate" id="search1" class="form-control" placeholder="from" value="{{ $startdate }}">
                                </div>
                                <div class="col-4 mb-2">
                                    <label class="form-label">Date To</label>
                                    <input type="Date" name="enddate" id="search2" class="form-control" placeholder="to" value="{{ $enddate }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-info waves-effect btn-label waves-light" name="sbfilter"><i class="mdi mdi-filter label-icon"></i> Filter</button>
                        </div>
                    </form>
                    <script>
                        $('select[name="searchDate"]').on('change', function() {
                            var date = $(this).val();
                            if(date == 'All'){
                                $('#search1').val(null);
                                $('#search2').val(null);
                                $('#search1').attr("required", false);
                                $('#search2').attr("required", false);
                                $('#search1').attr("readonly", true);
                                $('#search2').attr("readonly", true);
                            } else {
                                $('#search1').attr("required", true);
                                $('#search2').attr("required", true);
                                $('#search1').attr("readonly", false);
                                $('#search2').attr("readonly", false);
                            }
                        });
                        var searchDate = $('select[name="searchDate"]').val();
                        if(searchDate == 'All'){
                            $('#search1').attr("required", false);
                            $('#search2').attr("required", false);
                            $('#search1').attr("readonly", true);
                            $('#search2').attr("readonly", true);
                        }

                        document.getElementById('formfilter').addEventListener('submit', function(event) {
                            if (!this.checkValidity()) {
                                event.preventDefault(); // Prevent form submission if it's not valid
                                return false;
                            }
                            var submitButton = this.querySelector('button[name="sbfilter"]');
                            submitButton.disabled = true;
                            submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                            return true; // Allow form submission
                        });
                    </script>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col-lg-4">
                                <a href="{{ route('customer.create') }}" type="button" class="btn btn-primary waves-effect btn-label waves-light"><i class="mdi mdi-plus-box label-icon"></i> Add New Customer</a>
                                {{-- <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Customer</button> --}}
                                {{-- Modal Add --}}
                                {{-- <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Add New Customer</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('customer.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                                    <div class="row">
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Customer Code</label>
                                                            <br>
                                                            <span class="badge bg-info text-white">Auto Generate</span>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Status</label><label style="color: darkred">*</label>
                                                            <select class="form-select js-example-basic-single" style="width: 100%" name="status" required>
                                                                <option value="" selected>--Select Status--</option>
                                                                <option value="Active">Active</option>
                                                                <option value="Not Active">Not Active</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Customer Name</label><label style="color: darkred">*</label>
                                                            <input class="form-control" name="name" type="text" value="" placeholder="Input Customer Name.." required>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Remarks</label>
                                                            <input class="form-control" name="remark" type="text" value="" placeholder="Input Remarks..">
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Tax Number</label>
                                                            <input class="form-control" name="tax_number" type="text" value="" placeholder="Input Tax Number..">
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Tax Code</label>
                                                            <input class="form-control" name="tax_number" type="text" value="" placeholder="Input Tax Code..">
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Salesman</label><label style="color: darkred">*</label>
                                                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_salesmen" required>
                                                                <option value="" selected>--Select Salesman--</option>
                                                                @foreach($salesmans as $data)
                                                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Currency</label>
                                                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_currencies">
                                                                <option value="" selected>--Select Currency--</option>
                                                                @foreach($currencies as $data)
                                                                    <option value="{{ $data->id }}">{{ $data->currency }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Term Payment</label><label style="color: darkred">*</label>
                                                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_term_payments" required>
                                                                <option value="" selected>--Select Term Payment--</option>
                                                                @foreach($terms as $data)
                                                                    <option value="{{ $data->id }}">{{ $data->term_payment }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Ppn</label><label style="color: darkred">*</label>
                                                            <select class="form-select js-example-basic-single" style="width: 100%" name="ppn" required>
                                                                <option value="" selected>--Select Ppn--</option>
                                                                <option value="Include">Include</option>
                                                                <option value="Exclude">Exclude</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">CBC</label><label style="color: darkred">*</label>
                                                            <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                                <input type="checkbox" name="cbc" class="form-check-input" id="customSwitchsizemd">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-plus-box label-icon"></i>Add</button>
                                                </div>
                                            </form>
                                            <script>
                                                document.getElementById('formadd').addEventListener('submit', function(event) {
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
                                </div> --}}
                            </div>
                            <div class="col-lg-4">
                                <div class="text-center">
                                    <h5 class="fw-bold">Master Customer</h5>
                                </div>
                            </div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-12">
                                <div class="text-center">
                                    List of 
                                    @if($customer_code)
                                        (Code<b> - {{ $customer_code }}</b>)
                                    @endif
                                    @if($name)
                                        (Name<b> - {{ $name }}</b>)
                                    @endif
                                    @if($name)
                                        (Name<b> - {{ $name }}</b>)
                                    @endif
                                    @if($remark)
                                        (Remark<b> - {{ $remark }}</b>)
                                    @endif
                                    @if($ppn)
                                        (PPN<b> - {{ $ppn }}</b>)
                                    @endif
                                    @if($searchDate == 'Custom')
                                        (Date From<b> {{ $startdate }} </b>Until <b>{{ $enddate }}</b>)
                                    @else
                                        (<b>All Date</b>)
                                    @endif 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <table class="table table-bordered dt-responsive w-100" id="server-side-table" style="font-size: small">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle text-center">
                                        <input type="checkbox" id="checkAllRows">
                                    </th>
                                    <th class="align-middle text-center">#</th>
                                    <th class="align-middle text-center">Customer Name</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Remark</th>
                                    <th class="align-middle text-center">Tax Number</th>
                                    <th class="align-middle text-center">Tax Code</th>
                                    <th class="align-middle text-center">Salesman</th>
                                    <th class="align-middle text-center">Currency</th>
                                    <th class="align-middle text-center">Term Payment</th>
                                    <th class="align-middle text-center">CBC</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var i = 1;
        var url = '{!! route('customer.index') !!}';
        
        var idUpdated = '{{ $idUpdated }}';
        var pageNumber = '{{ $page_number }}';
        var pageLength = 5;
        var displayStart = (pageNumber - 1) * pageLength;
        var firstReload = true; 

        var currentDate = new Date();
        var formattedDate = currentDate.toISOString().split('T')[0];
        var fileName = "Master Customer Export - " + formattedDate + ".xlsx";
        var data = {
            customer_code: '{{ $customer_code }}',
            name: '{{ $name }}',
            remark: '{{ $remark }}',
            tax_number: '{{ $tax_number }}',
            tax_code: '{{ $tax_code }}',
            id_master_salesmen: '{{ $id_master_salesmen }}',
            id_master_currencies: '{{ $id_master_currencies }}',
            id_master_term_payments: '{{ $id_master_term_payments }}',
            ppn: '{{ $ppn }}',
            status: '{{ $status }}',
            searchDate: '{{ $searchDate }}',
            startdate: '{{ $startdate }}',
            enddate: '{{ $enddate }}'
        };
        var requestData = Object.assign({}, data);
        requestData.flag = 1;

        var dataTable = $('#server-side-table').DataTable({
            dom: '<"top d-flex"<"position-absolute top-0 end-0 d-flex"fl><"pull-left col-sm-12 col-md-5"B>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>><"clear:both">',
            initComplete: function(settings, json) {
                $('.dataTables_filter').html('<div class="input-group">' +
                '<button class="btn btn-sm btn-light me-1" type="button" id="custom-button" data-bs-toggle="modal" data-bs-target="#sort"><i class="mdi mdi-filter label-icon"></i> Sort & Filter</button>' +
                '<input class="form-control me-1" id="custom-search-input" type="text" placeholder="Search...">' +
                '</div>');
                $('.top').prepend(
                    `<div class='pull-left'>
                        <div class="btn-group mb-2" style="margin-right: 10px;"> <!-- Added inline style for margin -->
                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-checkbox-multiple-marked-outline"></i> Bulk Actions <i class="fas fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteselected"><i class="mdi mdi-trash-can"></i> Delete Selected</button></li>
                            </ul>
                        </div>
                    </div>`
                );
            },
            buttons: [
                {
                    extend: "excel",
                    text: '<i class="mdi mdi-file-excel label-icon"></i> Export to Excel',
                    className: 'btn btn-light waves-effect btn-label waves-light mb-2',
                    action: function (e, dt, button, config) {
                        $.ajax({
                            url: url,
                            method: "GET",
                            data: requestData,
                            success: function (response) {
                                generateExcel(response, fileName);
                            },
                            error: function (error) {
                                console.error(
                                    "Error sending data to server:",
                                    error
                                );
                            },
                        });
                    },
                },
            ],
            language: {
                processing: '<div id="custom-loader" class="dataTables_processing"></div>'
            },
            processing: true,
            serverSide: true,
            
            displayStart: displayStart,
            pageLength: pageLength,

            lengthMenu: [
                [5, 10, 20, 25, 50, 100, 200, -1],
                [5, 10, 20, 25, 50, 100, 200, "All"]
            ],
            language: {
                lengthMenu: '<select class="form-select" style="width: 100%">' +
                            '<option value="5">5</option>' +
                            '<option value="10">10</option>' +
                            '<option value="20">20</option>' +
                            '<option value="25">25</option>' +
                            '<option value="50">50</option>' +
                            '<option value="100">100</option>' +
                            '<option value="200">200</option>' +
                            '<option value="-1">All</option>' +
                            '</select>'
            },
            aaSorting: [],
            ajax: {
                url: url,
                type: 'GET',
                data: data
            },
            columns: [
                {
                    data: 'bulk-action',
                    name: 'bulk-action',
                    className: 'align-top text-center',
                    orderable: false,
                    searchable: false
                },
                {
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: 'customer_code',
                    name: 'customer_code',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        return '<b>' + row.customer_code + '</b><br>' + row.name;
                    },
                },
                {
                    data: 'status',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.status == 'Active'){
                            html = '<span class="badge bg-success text-white">Active</span>';
                        } else {
                            html = '<span class="badge bg-danger text-white">Inactive</span>';
                        }
                        return html;
                    },
                },
                {
                    data: 'remark',
                    name: 'remark',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        var html
                        if(row.remark == null){
                            html = '<div class="text-center"><span class="badge bg-secondary text-white">Null</span></div>';
                        } else {
                            html = row.remark;
                        }
                        return html;
                    },
                },
                {
                    data: 'tax_number',
                    name: 'tax_number',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        var html
                        if(row.tax_number == null){
                            html = '<div class="text-center"><span class="badge bg-secondary text-white">Null</span></div>';
                        } else {
                            html = row.tax_number;
                        }
                        return html;
                    },
                },
                {
                    data: 'tax_code',
                    name: 'tax_code',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        var html
                        if(row.tax_code == null){
                            html = '<div class="text-center"><span class="badge bg-secondary text-white">Null</span></div>';
                        } else {
                            html = row.tax_code;
                        }
                        return html;
                    },
                },
                {
                    data: 'salesmanname',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.salesmanname == null){
                            html = '<span class="badge bg-secondary text-white">Not Set</span>';
                        } else {
                            html = row.salesmanname;
                        }
                        return html;
                    },
                },
                {
                    data: 'currency',
                    name: 'currency',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        var html
                        if(row.currency == null){
                            html = '<div class="text-center"><span class="badge bg-secondary text-white">Null</span></div>';
                        } else {
                            html = row.currency;
                        }
                        return html;
                    },
                },
                {
                    data: 'term_payment',
                    name: 'term_payment',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'cbc',
                    name: 'cbc',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.cbc == 'N'){
                            html = '<span class="badge bg-danger text-white">No</span>';
                        } else {
                            html = '<span class="badge bg-success text-white">Yes</span>';
                        }
                        return html;
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
            ],
            bAutoWidth: false,
            columnDefs: [{
                width: "1%",
                targets: [0]
            }],
            drawCallback: function(settings) {
                if (firstReload && idUpdated) {
                    // Reset URL
                    let urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.toString()) {
                        let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                        history.pushState({}, "", newUrl);
                    }
                    var row = dataTable.row(function(idx, data, node) {
                        return data.id == idUpdated;
                    });

                    if (row.length) {
                        var rowNode = row.node();
                        $('html, body').animate({
                            scrollTop: $(rowNode).offset().top - $(window).height() / 2
                        }, 500);
                        // Highlight the row for 5 seconds
                        $(rowNode).addClass('table-info');
                        setTimeout(function() {
                            $(rowNode).removeClass('table-info');
                        }, 3000);
                    }
                    firstReload = false;
                }
            }
        });

        $(document).on('keyup', '#custom-search-input', function () {
            dataTable.search(this.value).draw();
        });
        $('.dataTables_processing').css('z-index', '9999');
    });
</script>

@endsection