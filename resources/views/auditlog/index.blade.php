@extends('layouts.master')
@section('konten')

<div class="page-content">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    <span class="badge bg-primary text-white">{{ \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->translatedFormat('F Y') }}</span>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <h4 class="text-bold">List Audit Log</h4>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-secondary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#modalFilter">
                            <i class="mdi mdi-filter label-icon"></i> Filter DateTime
                        </button>
                        {{-- Modal Filter --}}
                        <div class="modal fade" id="modalFilter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Filter Month</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form class="formLoad" action="{{ route('auditlog') }}" method="GET" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body p-4">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">DateTime</label> <label class="text-danger">*</label>
                                                    <input type="month" class="form-control" name="monthYear" value="{{ $monthYear }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light"><i class="mdi mdi-eye label-icon"></i></i>Show</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered dt-responsive w-100" id="ssTable">
                <thead class="table-light">
                    <tr>
                        <th class="align-middle text-center">#</th>
                        <th class="align-middle text-center">Email</th>
                        <th class="align-middle text-center">Access From</th>
                        <th class="align-middle text-center">Activity</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        var i = 1;
        var url = '{!! route('auditlog') !!}';
        var currentDate = new Date();
        var formattedDate = currentDate.toISOString().split('T')[0];
        var fileName = "Audit Log Export - " + formattedDate + ".xlsx";
        var data = { monthYear: '{{ $monthYear }}' };
        var requestData = Object.assign({}, data);
        requestData.flag = 1;

        var dataTable = $('#ssTable').DataTable({

            dom: '<"top d-flex"<"position-absolute top-0 end-0 d-flex"fl><"pull-left col-sm-12 col-md-5"B>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>><"clear:both">',
            initComplete: function(settings, json) {
                $('.dataTables_filter').html('<div class="input-group">' +
                '<button class="btn btn-sm btn-light me-1" type="button" id="custom-button"><i class="mdi mdi-magnify label-icon"></i></button>' +
                '<input class="form-control me-1" id="custom-search-input" type="text" placeholder="Search...">' +
                '</div>');
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
            scrollX: true,
            responsive: false,
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
            columns: [
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
                    orderable: true,
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'ip_address',
                    orderable: true,
                    render: function(data, type, row) {
                        return data + ' - <b>' + row.access_from + '</b><br>' + row.created;
                    },
                },
                {
                    data: 'activity',
                    searchable: true,
                    orderable: true,
                    className: 'align-top'
                },
                {
                    data: 'access_from',
                    searchable: true,
                    visible: false
                },
                {
                    data: 'created',
                    searchable: true,
                    visible: false
                },
            ],
        });

        $(document).on('keyup', '#custom-search-input', function () {
            dataTable.search(this.value).draw();
        });
        $('.dataTables_processing').css('z-index', '9999');

        $('#vertical-menu-btn').on('click', function() {
            setTimeout(function() {
                dataTable.columns.adjust().draw();
                window.dispatchEvent(new Event('resize'));
            }, 10);
        });
    });
</script>

@endsection