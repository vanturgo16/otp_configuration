@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List Customer Address of <b class="text-primary">"{{ $customer->name}}"</b></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer</a></li>
                            <li class="breadcrumb-item active">Address</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

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
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-deleteselected" onclick="bulkDeleted('{{ route('customeraddress.deleteselected', ['id' => $customer->id]) }}')"><i class="mdi mdi-delete label-icon"></i>Delete</button>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Modal Search --}}
        <div class="modal fade" id="sort" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"><i class="mdi mdi-filter label-icon"></i> Search & Filter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('customeraddress.index', encrypt($id)) }}" id="formfilter" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" rows="3" placeholder="Input Address..">{{ $address }}</textarea>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Postal Code</label>
                                    <input class="form-control" name="postal_code" type="text" value="{{ $postal_code }}" placeholder="Input Postal Code..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">City</label>
                                    <input class="form-control" name="city" type="text" value="{{ $city }}" placeholder="Input City..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Province</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_provinces">
                                        <option value="" selected>--Select Province--</option>
                                        @foreach($allprovinces as $province)
                                            <option value="{{ $province->id }}" @if($id_master_provinces == $province->id) selected="selected" @endif>{{ $province->province }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Country</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_countries">
                                        <option value="" selected>--Select Country--</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" @if($id_master_countries == $country->id) selected="selected" @endif>{{ $country->country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Telephone</label>
                                    <input class="form-control" name="telephone" type="text" value="{{ $address }}" placeholder="Input Telephone..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Mobile Phone</label>
                                    <input class="form-control" name="mobile_phone" type="text" value="{{ $address }}" placeholder="Input Mobile Phone..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Fax</label>
                                    <input class="form-control" name="fax" type="text" value="{{ $address }}" placeholder="Input Fax..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" name="email" type="email" value="{{ $address }}" placeholder="Input Email..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Type Address</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type_address">
                                        <option value="" selected>--Select Type Address--</option>
                                        <option value="Invoice" @if($type_address == 'Invoice') selected="selected" @endif>Invoice</option>
                                        <option value="Shipping" @if($type_address == 'Shipping') selected="selected" @endif>Shipping</option>
                                        <option value="Same As (Invoice, Shipping)" @if($type_address == 'Same As (Invoice, Shipping)') selected="selected" @endif>Same As (Invoice, Shipping)</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Status</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="status">
                                        <option value="" selected>--All--</option>
                                        <option value="Active" @if($status == 'Active') selected @endif>Active</option>
                                        <option value="0" @if($status == '0') selected @endif>Not Active</option>
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
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-middle"><b>Customer Code</b></td>
                            <td class="align-middle">: {{ $customer->customer_code }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Name</b></td>
                            <td class="align-middle">: {{ $customer->name}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Address</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Address</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('customeraddress.store', encrypt($customer->id)) }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <label class="form-label">Address</label><label style="color: darkred">*</label>
                                                    <textarea class="form-control" name="address" rows="3" placeholder="Input Address.." required></textarea>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Postal Code</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="postal_code" type="text" value="" placeholder="Input Postal Code.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">City</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="city" type="text" value="" placeholder="Input City.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Province</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_provinces" required>
                                                        <option value="" selected>--Select Province--</option>
                                                        @foreach($allprovinces as $province)
                                                            <option value="{{ $province->id }}">{{ $province->province }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Country</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_countries" required>
                                                        <option value="" selected>--Select Country--</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->id }}">{{ $country->country }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Telephone</label>
                                                    <input class="form-control" name="telephone" type="text" value="" placeholder="Input Telephone..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Mobile Phone</label>
                                                    <input class="form-control" name="mobile_phone" type="text" value="" placeholder="Input Mobile Phone..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Fax</label>
                                                    <input class="form-control" name="fax" type="text" value="" placeholder="Input Fax..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Email</label>
                                                    <input class="form-control" name="email" type="email" value="" placeholder="Input Email..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Remark</label>
                                                    <input class="form-control" name="remarks" type="text" value="" placeholder="Input Remark..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Contact Person</label>
                                                    <input class="form-control" name="contact_person" type="text" value="" placeholder="Input Contact Person..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Type Address</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type_address" required>
                                                        <option value="" selected>--Select Type Address--</option>
                                                        <option value="Invoice">Invoice</option>
                                                        <option value="Shipping">Shipping</option>
                                                        <option value="Same As (Invoice, Shipping)">Same As (Invoice, Shipping)</option>
                                                    </select>
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
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <table class="table table-bordered dt-responsive w-100" id="server-side-table" style="font-size: small">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">
                                        <input type="checkbox" id="checkAllRows">
                                    </th>
                                    <th class="align-middle text-center">#</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Address</th>
                                    <th class="align-middle text-center">Postal Code</th>
                                    <th class="align-middle text-center">City</th>
                                    <th class="align-middle text-center">Province</th>
                                    <th class="align-middle text-center">Country</th>
                                    <th class="align-middle text-center">Telephone</th>
                                    <th class="align-middle text-center">Mobile Phone</th>
                                    <th class="align-middle text-center">Type Address</th>
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
        var url = '{!! route('customeraddress.index', encrypt($id) ) !!}';
        var currentDate = new Date();
        var formattedDate = currentDate.toISOString().split('T')[0];
        var fileName = "Master Customer Address Export - " + formattedDate + ".xlsx";
        var data = {
                address: '{{ $address }}',
                postal_code: '{{ $postal_code }}',
                id_master_provinces: '{{ $id_master_provinces }}',
                id_master_countries: '{{ $id_master_countries }}',
                telephone: '{{ $telephone }}',
                mobile_phone: '{{ $mobile_phone }}',
                fax: '{{ $fax }}',
                email: '{{ $email }}',
                type_address: '{{ $type_address }}',
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
                    text: '<i class="fas fa-file-excel"></i> Export to Excel',
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
            pageLength: 5,
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
            columns: [{
                    data: 'bulk-action',
                    name: 'bulk-action',
                    className: 'align-middle text-center',
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
                    className: 'align-middle text-center',
                },
                {
                    data: 'status',
                    orderable: true,
                    className: 'align-middle text-center',
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
                    data: 'address',
                    name: 'address',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle'
                },
                {
                    data: 'postal_code',
                    name: 'postal_code',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.postal_code == null){
                            html = '<span class="badge bg-secondary text-white">Null</span></div>';
                        } else {
                            html = row.postal_code;
                        }
                        return html;
                    },
                },
                {
                    data: 'city',
                    name: 'city',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.city == null){
                            html = '<span class="badge bg-secondary text-white">Null</span></div>';
                        } else {
                            html = row.city;
                        }
                        return html;
                    },
                },
                {
                    data: 'province',
                    name: 'province',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.province == null){
                            html = '<span class="badge bg-secondary text-white">Null</span></div>';
                        } else {
                            html = row.province;
                        }
                        return html;
                    },
                },
                {
                    data: 'country',
                    name: 'country',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.country == null){
                            html = '<span class="badge bg-secondary text-white">Null</span></div>';
                        } else {
                            html = row.country;
                        }
                        return html;
                    },
                },
                {
                    data: 'telephone',
                    name: 'telephone',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.telephone == null){
                            html = '<span class="badge bg-secondary text-white">Null</span></div>';
                        } else {
                            html = row.telephone;
                        }
                        return html;
                    },
                },
                {
                    data: 'mobile_phone',
                    name: 'mobile_phone',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.mobile_phone == null){
                            html = '<span class="badge bg-secondary text-white">Null</span></div>';
                        } else {
                            html = row.mobile_phone;
                        }
                        return html;
                    },
                },
                {
                    data: 'type_address',
                    name: 'type_address',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle',
                    render: function(data, type, row) {
                        var html
                        if(row.type_address == null){
                            html = '<div class="text-center"><span class="badge bg-secondary text-white">Null</span></div>';
                        } else {
                            html = row.type_address;
                        }
                        return html;
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },
            ],
            bAutoWidth: false,
            columnDefs: [{
                width: "1%",
                targets: [0]
            }]
        });

        $(document).on('keyup', '#custom-search-input', function () {
            dataTable.search(this.value).draw();
        });
        $('.dataTables_processing').css('z-index', '9999');
    });
</script>

@endsection