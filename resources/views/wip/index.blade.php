@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between py-2">
                    <div class="page-title-left">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop" type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="dropdown"
                                aria-expanded="false"><i class="mdi mdi-plus-box label-icon"></i>
                                Add New Wip | <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu2" aria-labelledby="btnGroupDrop">
                                <li><a class="dropdown-item drpdwn" href="{{ route('wip.create', 'wip') }}"><span class="mdi mdi-menu"></span> | WIP</a></li>
                                <li><a class="dropdown-item drpdwn" href="{{ route('wip.create', 'wipblow') }}"><span class="mdi mdi-menu"></span> | WIP Blow</a></li>
                            </ul>
                        </div>
                        {{-- <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Wip</button> --}}
                        {{-- Modal Add --}}
                        {{-- <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Wip</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('wip.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">WIP Type</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="wip_type" required>
                                                        <option value="" selected>--Select WIP Type--</option>
                                                        <option value="WIP">WIP</option>
                                                        <option value="WIP Blow">WIP Blow</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Description</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="description" type="text" value="" placeholder="Input Description.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Type</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type" required>
                                                        <option value="" selected>--Select Type--</option>
                                                        <option value="PP">PP</option>
                                                        <option value="POF">POF</option>
                                                        <option value="CROSSLINK">CROSSLINK</option>
                                                        <option value="PPNC">PPNC</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Process Production</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_process_productions" required>
                                                        <option value="" selected>--Select Process Production--</option>
                                                        @foreach($process as $pr)
                                                            <option value="{{ $pr->id }}">{{ $pr->process }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Quantity</label>
                                                    <input class="form-control" name="qty" type="number" value="0" placeholder="Input Quantity..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Units</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_units" required>
                                                        <option value="" selected>--Select Unit--</option>
                                                        @foreach($units as $unit)
                                                            <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Group</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_groups" required>
                                                        <option value="" selected>--Select Group--</option>
                                                        @foreach($groups as $group)
                                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Group Sub</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_group_subs" required>
                                                        <option value="" selected>--Select Group Sub--</option>
                                                        @foreach($group_subs as $gs)
                                                            <option value="{{ $gs->id }}">{{ $gs->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Department</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_departements">
                                                        <option value="" selected>--Select Department--</option>
                                                        @foreach($departments as $depart)
                                                            <option value="{{ $depart->id }}">{{ $depart->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Stock</label>
                                                    <input class="form-control" name="stock" type="text" value="" placeholder="Input Stock..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Status</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="status" required>
                                                        <option value="" selected>--Select Status--</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Not Active">Not Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card p-3">
                                                <div class="text-center">
                                                    <label class="form-label">Size</label>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Thickness</label>
                                                        <input class="form-control" name="thickness" type="text" value="" placeholder="Input Thickness..">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Width</label>
                                                        <input class="form-control" name="width" type="text" value="" placeholder="Input Width..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Width Unit</label>
                                                        <input class="form-control" name="width_unit" type="text" value="" placeholder="Input Width Unit..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Lenght</label>
                                                        <input class="form-control" name="length" type="text" value="" placeholder="Input Lenght..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Lenght Unit</label>
                                                        <input class="form-control" name="length_unit" type="text" value="" placeholder="Input Lenght Unit..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Perforasi</label>
                                                        <input class="form-control" name="perforasi" type="text" value="" placeholder="Input Perforasi..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Weight</label>
                                                        <input class="form-control" name="weight" type="text" value="" placeholder="Input Weight..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Code</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="wip_code" type="text" value="" placeholder="Input Code.." required>
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
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Wips</li>
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
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-deleteselected" onclick="bulkDeleted('{{ route('wip.deleteselected') }}')"><i class="mdi mdi-delete label-icon"></i>Delete</button>
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
                    <form action="{{ route('wip.index') }}" id="formfilter" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label">Code</label>
                                    <input class="form-control" name="wip_code" type="text" value="{{ $wip_code }}" placeholder="Filter Code..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Description</label>
                                    <input class="form-control" name="description" type="text" value="{{ $description }}" placeholder="Filter Description..">
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
                                    <label class="form-label">Type</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type">
                                        <option value="" selected>--All--</option>
                                        <option value="PP" @if($type == 'PP') selected @endif>PP</option>
                                        <option value="POF" @if($type == 'POF') selected @endif>POF</option>
                                        <option value="CROSSLINK" @if($type == 'CROSSLINK') selected @endif>CROSSLINK</option>
                                        <option value="PPNC" @if($type == 'PPNC') selected @endif>PPNC</option>
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
                    <div class="card-header text-center py-3">
                        <h5 class="mb-0"><b>Master WIP</b></h5>
                        List of 
                        @if($wip_code != null)
                            (Code<b> - {{ $wip_code }}</b>)
                        @endif
                        @if($description != null)
                            (Description<b> - {{ $description }}</b>)
                        @endif
                        @if($status != null)
                            (Status<b> - {{ $status }}</b>)
                        @endif
                        @if($type != null)
                            (Type<b> - {{ $type }}</b>)
                        @endif
                        @if($searchDate == 'Custom')
                            (Date From<b> {{ $startdate }} </b>Until <b>{{ $enddate }}</b>)
                        @else
                            (<b>All Date</b>)
                        @endif 
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
                                    <th class="align-middle text-center">Description</th>
                                    <th class="align-middle text-center">Type</th>
                                    <th class="align-middle text-center">Process Production</th>
                                    <th class="align-middle text-center">Unit</th>
                                    <th class="align-middle text-center">Group</th>
                                    <th class="align-middle text-center">Group Sub</th>
                                    <th class="align-middle text-center">Status</th>
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
        var url = '{!! route('wip.index') !!}';
        var currentDate = new Date();
        var formattedDate = currentDate.toISOString().split('T')[0];
        var fileName = "Master WIP Export - " + formattedDate + ".xlsx";
        var data = {
            wip_code: '{{ $wip_code }}',
            description: '{{ $description }}',
            type: '{{ $type }}',
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
                    data: 'wip_code',
                    name: 'wip_code',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle',
                    render: function(data, type, row) {
                        return '<b>' + row.wip_code + '</b><br>' + row.description;
                    },
                },
                {
                    data: 'type',
                    name: 'type',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                },
                {
                    data: 'process',
                    name: 'process',
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
                    data: 'groupname',
                    name: 'groupname',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                },
                {
                    data: 'groupsub',
                    name: 'groupsub',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: true,
                    searchable: true,
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