@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <a href="{{ route('historystock.rm.history', encrypt($product->id)) }}?idUpdated={{ $id }}" class="btn btn-light waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left label-icon"></i> Back To List History Stock RM
                        </a>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('historystock.rm') }}?idUpdated={{ $product->id }}">List Stock RM</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('historystock.rm.history', encrypt($product->id)) }}?idUpdated={{ $id }}">{{ $product->rm_code ?? '' }}</a></li>
                            <li class="breadcrumb-item active">Detail {{ $number ?? '' }}</li>
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
                            <td class="align-middle"><b>RM Code</b></td>
                            <td class="align-middle">: {{ $product->rm_code ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Description </b></td>
                            <td class="align-middle">: {{ $product->description ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle">
                                <b>
                                    @if($tableJoin == 'PL')
                                        Packing Number
                                    @elseif(in_array($tableJoin, ['RB', 'RSL', 'RF', 'RBM']))
                                        Report Number
                                    @else
                                        Lot Number
                                    @endif
                                </b>
                            </td>
                            <td class="align-middle">: {{ $number ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                @if($tableJoin == 'PL')
                    <div class="card">
                        <div class="card-header bg-light p-3">
                            <h6>Detail Packing Number <b>{{ $number ?? '' }}</b></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Date :</span></div>
                                    <span>{{ $datas->date }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Customer :</span></div>
                                    <span>{{ $datas->customer_name ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Status :</span></div>
                                    <span>
                                        @if($datas->status == 'Closed')
                                            <span class="badge bg-success text-white"><i class="fas fa-check"></i> Closed</span>
                                        @else
                                            <span class="badge bg-secondary text-white">{{ $datas->status }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(in_array($tableJoin, ['RB', 'RSLRF', 'RBM']))
                    <div class="card">
                        <div class="card-header bg-light p-3">
                            <div class="row">
                                <div class="col-6">
                                    <h6>Detail Report Number <b>{{ $number ?? '' }}</b></h4>
                                </div>
                                <div class="col-6">
                                    @if($dataHistories->count() > 0)
                                        <div class="text-end">
                                            <button class="btn btn-sm btn-info shadow" data-bs-toggle="modal" data-bs-target="#modalHistory">
                                                <span class="mdi mdi-history"></span> Log History
                                            </button>
                                        </div>
                                        <div class="modal fade" id="modalHistory" tabindex="-1" aria-labelledby="modalHistoryLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalHistoryLabel">Log Store To History</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                                        <table class="table table-bordered table-striped table-hover dt-responsive w-100" id="historyTable">
                                                            <thead>
                                                                <tr>
                                                                    <th class="align-middle text-center">#</th>
                                                                    <th class="align-middle text-center">Report Number</th>
                                                                    <th class="align-middle text-center">Date</th>
                                                                    <th class="align-middle text-center">Qty</th>
                                                                    <th class="align-middle text-center">Type Stock</th>
                                                                    <th class="align-middle text-center">Remarks</th>
                                                                    <th class="align-middle text-center">Store Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($dataHistories as $history)
                                                                    <tr>
                                                                        <td class="align-top text-center text-bold">{{ $loop->iteration }}</td>
                                                                        <td class="align-top text-bold">{{ $history->id_good_receipt_notes_details }}</td>
                                                                        <td class="align-top text-center">{{ $history->date }}</td>
                                                                        <td class="align-top text-end">
                                                                            {{ $history->qty
                                                                                ? (strpos(strval($history->qty), '.') !== false 
                                                                                    ? rtrim(rtrim(number_format($history->qty, 6, ',', '.'), '0'), ',') 
                                                                                    : number_format($history->qty, 0, ',', '.')) 
                                                                                : '0' }}
                                                                        </td>
                                                                        <td class="align-top text-center">{{ $history->type_stock }}</td>
                                                                        <td class="align-top">{{ $history->remarks ?? '-' }}</td>
                                                                        <td class="align-middle text-center">{{ date('d-m-Y H:i:s', strtotime($history->created_at)) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Order Name :</span></div>
                                    <span>{{ $datas->order_name ?? '-' }}</span>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Date :</span></div>
                                    <span>{{ $datas->date }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Customer :</span></div>
                                    <span>{{ $datas->customer_name ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Regu :</span></div>
                                    <span>{{ $datas->regus_name }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Ketua Regu :</span></div>
                                    <span>{{ $datas->kr_name ?? '-' }}</span>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Operator :</span></div>
                                    <span>{{ $datas->op_name ?? '-' }}</span>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Shift :</span></div>
                                    <span>{{ $datas->shift ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Known By :</span></div>
                                    <span>{{ $datas->kb_name ?? '-' }}</span>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <div class="fw-bold"><span>Status :</span></div>
                                    <span>
                                        @if($datas->status == 'Closed')
                                            <span class="badge bg-success text-white"><i class="fas fa-check"></i> Closed</span>
                                        @else
                                            <span class="badge bg-secondary text-white">{{ $datas->status }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover dt-responsive w-100" id="ssTable">
                                <thead>
                                    <tr>
                                        <th class="align-middle text-center">#</th>
                                        <th class="align-middle text-center">Ext. Lot Number</th>
                                        <th class="align-middle text-center">Qty</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <script>
                        $(function() {
                            var url = '{!! route('historystock.rm.detail', [encrypt($id), $tableJoin]) !!}';
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
                                        className: 'align-top text-center text-bold',
                                    },
                                    {
                                        data: 'ext_lot_number',
                                        name: 'ext_lot_number',
                                        orderable: true,
                                        searchable: true,
                                        className: 'align-top text-bold',
                                        render: function (data, type, row) {
                                            return data ? data : '-';
                                        }
                                    },
                                    {
                                        data: 'qty',
                                        name: 'qty',
                                        orderable: true,
                                        searchable: true,
                                        className: 'align-top text-end',
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
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Server Side -->


@endsection