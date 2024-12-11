@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List History Stock</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">History Stock</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#rm" role="tab" id="rmBtn">
                                    <span class="d-block d-sm-none"><i class="fas fa-history"></i></span>
                                    <span class="d-none d-sm-block">Raw Material</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#wip" role="tab" id="wipBtn">
                                    <span class="d-block d-sm-none"><i class="far fa-history"></i></span>
                                    <span class="d-none d-sm-block">WIP</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#fg" role="tab" id="fgBtn">
                                    <span class="d-block d-sm-none"><i class="far fa-history"></i></span>
                                    <span class="d-none d-sm-block">Finish Good</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#ta" role="tab" id="taBtn">
                                    <span class="d-block d-sm-none"><i class="fas fa-history"></i></span>
                                    <span class="d-none d-sm-block">Sparepart & Aux</span>    
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="rm" role="tabpanel">
                                <!-- Table -->
                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100" id="ssTableRM" style="font-size: small">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">#</th>
                                            <th class="align-middle text-center">Code / Description</th>
                                            <th class="align-middle text-center">Stock Saat Ini</th>
                                            <th class="align-middle text-center">Datang</th>
                                            <th class="align-middle text-center">Pakai</th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!-- Server Side -->
                                <script>
                                    $(function() {
                                        var url = '{!! route('historystock.rm') !!}';
                                        var dataTable = $('#ssTableRM').DataTable({
                                            language: {
                                                processing: '<div id="custom-loader" class="dataTables_processing"></div>'
                                            },
                                            processing: true,
                                            serverSide: true,
                                            scrollX: true,
                                            pageLength: 5,
                                            lengthMenu: [
                                                [5, 10, 20, 25, 50, 100, 200, -1],
                                                [5, 10, 20, 25, 50, 100, 200, "All"]
                                            ],
                                            ajax: {
                                                url: url,
                                                type: 'GET',
                                            },
                                            columns: [
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
                                                    data: 'rm_code',
                                                    name: 'rm_code',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top',
                                                    render: function(data, type, row) {
                                                        var html
                                                        if(row.rm_code || row.description){
                                                            html = '<b>' + row.rm_code + '</b><br>' + row.description;
                                                        } else {
                                                            html = '<span class="badge bg-secondary text-white">Null</span>';
                                                        }
                                                        return html;
                                                    },
                                                },
                                                {
                                                    data: 'stock',
                                                    name: 'stock',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                                {
                                                    data: 'total_in',
                                                    name: 'total_in',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                                {
                                                    data: 'total_out',
                                                    name: 'total_out',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                                {
                                                    data: 'action',
                                                    name: 'action',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                                {
                                                    data: 'description',
                                                    name: 'description',
                                                    searchable: true,
                                                    visible: false
                                                },
                                            ]
                                        });
                                        $('.dataTables_processing').css('z-index', '9999');
                                    });
                                </script>
                            </div>
                            <div class="tab-pane" id="wip" role="tabpanel">
                                <!-- Table -->
                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100" id="ssTableWIP" style="font-size: small">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">#</th>
                                            <th class="align-middle text-center">WIP Code</th>
                                            <th class="align-middle text-center">Description</th>
                                            <th class="align-middle text-center">Stock</th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!-- Server Side -->
                                <script>
                                    $(function() {
                                        var url = '{!! route('historystock.wip') !!}';
                                        var dataTable = $('#ssTableWIP').DataTable({
                                            language: {
                                                processing: '<div id="custom-loader" class="dataTablesLoad2"></div>'
                                            },
                                            processing: true,
                                            serverSide: true,
                                            scrollX: true,
                                            pageLength: 5,
                                            lengthMenu: [
                                                [5, 10, 20, 25, 50, 100, 200, -1],
                                                [5, 10, 20, 25, 50, 100, 200, "All"]
                                            ],
                                            ajax: {
                                                url: url,
                                                type: 'GET',
                                            },
                                            columns: [
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
                                                    className: 'align-top text-bold',
                                                },
                                                {
                                                    data: 'description',
                                                    name: 'description',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top',
                                                    render: function(data, type, row) {
                                                        var perfVal;
                                                        if(row.perforasi){
                                                            perfVal = row.perforasi;
                                                        } else {
                                                            perfVal = '-';
                                                        }
                                                        return row.description + '<br><b>Perforasi: </b>' + perfVal;
                                                    },
                                                },
                                                {
                                                    data: 'stock',
                                                    name: 'stock',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                                {
                                                    data: 'action',
                                                    name: 'action',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                            ]
                                        });
                                        $('.dataTablesLoad2').css('z-index', '9999');
                                    });
                                </script>
                            </div>
                            <div class="tab-pane" id="fg" role="tabpanel">
                                <!-- Table -->
                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100" id="ssTableFG" style="font-size: small">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">#</th>
                                            <th class="align-middle text-center">Product Code</th>
                                            <th class="align-middle text-center">Description</th>
                                            <th class="align-middle text-center">Stock</th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!-- Server Side -->
                                <script>
                                    $(function() {
                                        var url = '{!! route('historystock.fg') !!}';
                                        var dataTable = $('#ssTableFG').DataTable({
                                            language: {
                                                processing: '<div id="custom-loader" class="dataTablesLoad3"></div>'
                                            },
                                            processing: true,
                                            serverSide: true,
                                            scrollX: true,
                                            pageLength: 5,
                                            lengthMenu: [
                                                [5, 10, 20, 25, 50, 100, 200, -1],
                                                [5, 10, 20, 25, 50, 100, 200, "All"]
                                            ],
                                            ajax: {
                                                url: url,
                                                type: 'GET',
                                            },
                                            columns: [
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
                                                    data: 'product_code',
                                                    name: 'product_code',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-bold',
                                                },
                                                {
                                                    data: 'description',
                                                    name: 'description',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top',
                                                    render: function(data, type, row) {
                                                        var perfVal;
                                                        if(row.perforasi){
                                                            perfVal = row.perforasi;
                                                        } else {
                                                            perfVal = '-';
                                                        }
                                                        return row.description + '<br><b>Perforasi: </b>' + perfVal;
                                                    },
                                                },
                                                {
                                                    data: 'stock',
                                                    name: 'stock',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                                {
                                                    data: 'action',
                                                    name: 'action',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                            ]
                                        });
                                        $('.dataTablesLoad3').css('z-index', '9999');
                                    });
                                </script>
                            </div>
                            <div class="tab-pane" id="ta" role="tabpanel">
                                <!-- Table -->
                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100" id="ssTableTA" style="font-size: small">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">#</th>
                                            <th class="align-middle text-center">Code / Description</th>
                                            <th class="align-middle text-center">Stock Saat Ini</th>
                                            <th class="align-middle text-center">Datang</th>
                                            <th class="align-middle text-center">Pakai</th>
                                            <th class="align-middle text-center">Department</th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!-- Server Side -->
                                <script>
                                    $(function() {
                                        var url = '{!! route('historystock.ta') !!}';
                                        var dataTable = $('#ssTableTA').DataTable({
                                            language: {
                                                processing: '<div id="custom-loader" class="dataTablesLoad4"></div>'
                                            },
                                            processing: true,
                                            serverSide: true,
                                            scrollX: true,
                                            pageLength: 5,
                                            lengthMenu: [
                                                [5, 10, 20, 25, 50, 100, 200, -1],
                                                [5, 10, 20, 25, 50, 100, 200, "All"]
                                            ],
                                            ajax: {
                                                url: url,
                                                type: 'GET',
                                            },
                                            columns: [
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
                                                    data: 'code',
                                                    name: 'code',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top',
                                                    render: function(data, type, row) {
                                                        var html
                                                        if(row.code || row.description){
                                                            html = '<b>' + row.code + '</b><br>' + row.description;
                                                        } else {
                                                            html = '<span class="badge bg-secondary text-white">Null</span>';
                                                        }
                                                        return html;
                                                    },
                                                },
                                                {
                                                    data: 'stock',
                                                    name: 'stock',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                                {
                                                    data: 'total_in',
                                                    name: 'total_in',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                                {
                                                    data: 'total_out',
                                                    name: 'total_out',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                                {
                                                    data: 'departement_name',
                                                    name: 'departement_name',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                                {
                                                    data: 'action',
                                                    name: 'action',
                                                    orderable: true,
                                                    searchable: true,
                                                    className: 'align-top text-center text-bold',
                                                },
                                                {
                                                    data: 'description',
                                                    name: 'description',
                                                    searchable: true,
                                                    visible: false
                                                },
                                            ]
                                        });
                                        $('.dataTablesLoad4').css('z-index', '9999');
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('#rmBtn').click(function() {
                $("#ssTableRM").DataTable().ajax.reload();
            });
            $('#wipBtn').click(function() {
                $("#ssTableWIP").DataTable().ajax.reload();
            });
            $('#fgBtn').click(function() {
                $("#ssTableFG").DataTable().ajax.reload();
            });
            $('#taBtn').click(function() {
                $("#ssTableTA").DataTable().ajax.reload();
            });
        </script>
    </div>
</div>

@endsection