@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List FG Refs of <b class="text-primary">"{{ $fg->description}}"</b></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fg.index') }}">Master FG</a></li>
                            <li class="breadcrumb-item active">FG Ref</li>
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
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-deleteselected" onclick="bulkDeleted('{{ route('customeraddress.deleteselected', ['id' => $id]) }}')"><i class="mdi mdi-delete label-icon"></i>Delete</button>
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
                    <form action="{{ route('fgref.index', encrypt($id)) }}" id="formfilter" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label">Type Ref</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type_ref">
                                        <option value="" selected>--All--</option>
                                        <option value="WIP" @if($type_ref == 'WIP') selected @endif>WIP</option>
                                        <option value="FG" @if($type_ref == 'FG') selected @endif>FG</option>
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
                            <td class="align-middle"><b>Product Code</b></td>
                            <td class="align-middle">: {{ $fg->product_code }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Description</b></td>
                            <td class="align-middle">: {{ $fg->description}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New FG Ref</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New FG Ref</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('fgref.store', encrypt($id)) }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Type Ref</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type_ref" required>
                                                        <option value="" selected>--Select Type Ref--</option>
                                                        <option value="WIP">WIP</option>
                                                        <option value="FG">FG</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2" id="wipform"><label style="color: darkred">*</label>
                                                    <label class="form-label">WIP</label>
                                                    <select class="form-control js-example-basic-single" name="id_master_wips" style="width: 100%">
                                                        <option value="" selected>--Select WIP--</option>
                                                        @foreach($listwip as $item)
                                                            <option value="{{ $item->id }}">{{ $item->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2" id="fgform"><label style="color: darkred">*</label>
                                                    <label class="form-label">FG</label>
                                                    <select class="form-control js-example-basic-single" name="id_master_fgs" style="width: 100%">
                                                        <option value="" selected>--Select FG--</option>
                                                        @foreach($listfg as $item)
                                                            <option value="{{ $item->id }}">{{ $item->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <script>
                                                    $('#fgform').hide();
                                                    $('select[name="type_ref"]').on('change', function() {
                                                        var type_ref = $(this).val();
                                                        if(type_ref == 'WIP'){
                                                            $('#fgform').hide();
                                                            $('#wipform').show();
                                                            $('select[name="id_master_fgs"]').val("");
                                                            $('select[name="id_master_fgs"]').prop('required', false);
                                                            $('select[name="id_master_wips"]').prop('required', true);
                                                        } else {
                                                            $('#fgform').show();
                                                            $('#wipform').hide();
                                                            $('select[name="id_master_wips"]').val("");
                                                            $('select[name="id_master_fgs"]').prop('required', true);
                                                            $('select[name="id_master_wips"]').prop('required', false);
                                                        }
                                                    });
                                                </script>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Qty</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="qty" type="text" value="" placeholder="Input Qty.." required>
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Unit</label><label style="color: darkred">*</label>
                                                    <select class="form-control js-example-basic-single" name="master_units_id" style="width: 100%">
                                                        <option value="" selected>--Select Unit--</option>
                                                        @foreach($listunit as $item)
                                                            <option value="{{ $item->id }}">{{ $item->unit }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Qty Result</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="qty_results" type="text" value="" placeholder="Input Qty Result.." required>
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
                                    <th class="align-middle text-center">Type Ref</th>
                                    <th class="align-middle text-center">WIP</th>
                                    <th class="align-middle text-center">FG</th>
                                    <th class="align-middle text-center">Qty</th>
                                    <th class="align-middle text-center">Units</th>
                                    <th class="align-middle text-center">Qty Result</th>
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
        var url = '{!! route('fgref.index', encrypt($id)) !!}';
        var currentDate = new Date();
        var formattedDate = currentDate.toISOString().split('T')[0];
        var fileName = "Master Product FG Ref Export - " + formattedDate + ".xlsx";
        var data = {
            type_ref: '{{ $type_ref }}',
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
                    data: 'type_ref',
                    name: 'type_ref',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                },
                {
                    data: 'wp_description',
                    name: 'wp_description',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.wp_description == null){
                            html = '<span class="badge bg-secondary text-white">Null</span>';
                        } else {
                            html = row.wp_description;
                        }
                        return html;
                    },
                },
                {
                    data: 'fg_description',
                    name: 'fg_description',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.fg_description == null){
                            html = '<span class="badge bg-secondary text-white">Null</span>';
                        } else {
                            html = row.fg_description;
                        }
                        return html;
                    },
                },
                {
                    data: 'qty',
                    name: 'qty',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                },
                {
                    data: 'unit',
                    name: 'unit',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                },
                {
                    data: 'qty_results',
                    name: 'qty_results',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
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