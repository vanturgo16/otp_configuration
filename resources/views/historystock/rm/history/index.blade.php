@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <form action="{{ route('historystock.rm') }}" method="GET" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="idUpdated" value="{{ $id }}">
                            <button type="submit" class="btn btn-light waves-effect btn-label waves-light">
                                <i class="mdi mdi-arrow-left label-icon"></i> Back To List Stock RM
                            </button>
                        </form>

                        {{-- <a href="{{ route('historystock.rm') }}" class="btn btn-light waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left label-icon"></i> Back To List Stock RM
                        </a> --}}
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item">
                                <a href="#" onclick="event.preventDefault(); document.getElementById('backForm').submit();">List Stock RM</a>
                            </li>
                            {{-- <li class="breadcrumb-item"><a href="{{ route('historystock.rm') }}">List Stock RM</a></li> --}}
                            <li class="breadcrumb-item active">{{ $detail->rm_code ?? '' }}</li>
                        </ol>
                    </div>
                    
                    <form action="{{ route('historystock.rm') }}" method="GET" id="backForm" style="display: none;">
                        @csrf <input type="hidden" name="idUpdated" value="{{ $id }}">
                    </form>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light p-3">
                        <h6><b>{{ $detail->rm_code ?? '' }}</b> {{ $detail->description ?? '' }}</h4>
                    </div>
                    <div class="card-body p-1">
                        <table class="table table-bordered dt-responsive w-100">
                            <tbody>
                                <tr>
                                    <td class="align-middle"><b>Total IN (Closed)</b></td>
                                    <td class="align-middle"><b>Total OUT (Closed)</b></td>
                                    <td class="align-middle"><b>Total Stock</b></td>
                                </tr>
                                <tr>
                                    <td class="align-middle">
                                        {{ $total_in
                                            ? (strpos(strval($total_in), '.') !== false 
                                                ? rtrim(rtrim(number_format($total_in, 6, ',', '.'), '0'), ',') 
                                                : number_format($total_in, 0, ',', '.')) 
                                            : '0' }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $total_out
                                            ? (strpos(strval($total_out), '.') !== false 
                                                ? rtrim(rtrim(number_format($total_out, 6, ',', '.'), '0'), ',') 
                                                : number_format($total_out, 0, ',', '.')) 
                                            : '0' }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $detail->stock
                                            ? (strpos(strval($detail->stock), '.') !== false 
                                                ? rtrim(rtrim(number_format($detail->stock, 6, ',', '.'), '0'), ',') 
                                                : number_format($detail->stock, 0, ',', '.')) 
                                            : '0' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover dt-responsive w-100" id="ssTable" style="font-size: small">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">#</th>
                                    <th class="align-middle text-center">(Lot/Report/Packing) Number</th>
                                    <th class="align-middle text-center">Type Product</th>
                                    <th class="align-middle text-center">Qty</th>
                                    <th class="align-middle text-center">Type Stock</th>
                                    <th class="align-middle text-center">Date</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Remark</th>
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

<!-- Server Side -->
<script>
    $(function() {
        var url = '{!! route('historystock.rm.history', encrypt($id)) !!}';
        var dataTable = $('#ssTable').DataTable({
            scrollX: true,
            responsive: false,
            fixedColumns: {
                leftColumns: 2,
                rightColumns: 1
            },
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
                    className: 'align-top text-center text-bold',
                },
                {
                    data: 'number',
                    name: 'number',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-bold',
                },
                {
                    data: 'type_product',
                    name: 'type_product',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                },
                {
                    data: 'qty',
                    name: 'qty',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-end text-bold',
                    render: function(data, type, row) {
                        if (!data || parseFloat(data) === 0) {
                            return '0';
                        }
                        let parts = data.toString().split('.');
                        let integerPart = parts[0];
                        let decimalPart = parts[1] || '';
                        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        return decimalPart ? `${integerPart},${decimalPart}` : integerPart;
                    }
                },
                {
                    data: 'type_stock',
                    name: 'type_stock',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                },
                {
                    data: 'date',
                    name: 'date',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                    render: (data) => {
                        const badgeClass = data === 'Closed' ? 'bg-success' : 'bg-secondary';
                        return `<span class="badge ${badgeClass} text-white">${data}</span>`;
                    }
                },
                {
                    data: 'remarks',
                    name: 'remarks',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                },
            ]
        });
        // **Fix Header and Body Misalignment on Sidebar Toggle**
        $('#vertical-menu-btn').on('click', function() {
            setTimeout(function() {
                dataTable.columns.adjust().draw();
                window.dispatchEvent(new Event('resize'));
            }, 10);
        });
        $('.dataTables_processing').css('z-index', '9999');
    });
</script>

@endsection