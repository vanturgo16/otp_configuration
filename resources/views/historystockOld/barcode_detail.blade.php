@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <a href="{{ route('historystock') }}" class="btn btn-light waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left label-icon"></i> Back
                        </a>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('historystock') }}">History Stock</a></li>
                            <li class="breadcrumb-item active">{{ $barcode ?? '' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-middle"><b>Barcode</b></td>
                            <td class="align-middle">: {{ $barcode ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100" id="ssTable" style="font-size: small">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">#</th>
                                    <th class="align-middle text-center">Barcode Number</th>
                                    <th class="align-middle text-center">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Server Side -->
<script>
    $(function() {
        var url = '{!! route('historystock.barcode', $barcode) !!}';
        var dataTable = $('#ssTable').DataTable({
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
                    data: 'barcode_number',
                    name: 'barcode_number',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-bold',
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
            ]
        });
        $('.dataTables_processing').css('z-index', '9999');
    });
</script>

@endsection