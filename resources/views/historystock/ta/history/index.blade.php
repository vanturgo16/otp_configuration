@extends('layouts.master')
@section('konten')
@php use Carbon\Carbon; Carbon::setLocale('id'); @endphp
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <a href="{{ route('historystock.ta') }}?idUpdated={{ $id }}" class="btn btn-light waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left label-icon"></i> Back To List Stock TA
                        </a>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('historystock.ta') }}?idUpdated={{ $id }}">List Stock TA</a></li>
                            <li class="breadcrumb-item active">{{ $detail->code ?? '' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light p-3">
                        <div class="row">
                            <div class="col-6">
                                <h6><b>{{ $detail->code ?? '' }}</b> {{ $detail->description ?? '' }}</h6>
                                <h5>
                                    <span class="badge bg-info text-white"><b>Stock Master :</b>
                                        {{ $detail->stock
                                            ? (strpos(strval($detail->stock), '.') !== false 
                                                ? rtrim(rtrim(number_format($detail->stock, 6, ',', '.'), '0'), ',') 
                                                : number_format($detail->stock, 0, ',', '.')) 
                                            : '0' }}
                                    </span>
                                </h5>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <button class="btn btn-sm btn-primary waves-effect btn-label waves-light" title="Period Stock" disabled>
                                        <i class="mdi mdi-clock label-icon"></i> 
                                        @if($searchDate == 'Custom')
                                            {{ Carbon::parse($startdate)->translatedFormat('d F Y') }} - {{ Carbon::parse($enddate)->translatedFormat('d F Y') }}
                                        @else
                                            <strong>ALL</strong>
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-1">
                        <table class="table table-bordered dt-responsive w-100">
                            <tbody>
                                <tr>
                                    <td class="align-middle fw-bold">Total IN (Closed)</td>
                                    <td class="align-middle fw-bold">Total OUT (Closed)</td>
                                    <td class="align-middle fw-bold">Total (Closed)</td>
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
                                        {{ $total
                                            ? (strpos(strval($total), '.') !== false 
                                                ? rtrim(rtrim(number_format($total, 6, ',', '.'), '0'), ',') 
                                                : number_format($total, 0, ',', '.')) 
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
                        <table class="table table-bordered table-hover dt-responsive w-100" id="ssTable" style="font-size: small">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">#</th>
                                    <th class="align-middle text-center">Number <br>(source)</th>
                                    <th class="align-middle text-center">Type Product</th>
                                    <th class="align-middle text-center">Qty</th>
                                    <th class="align-middle text-center">Weight</th>
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
        var url = '{!! route('historystock.ta.history', encrypt($id)) !!}';

        var idUpdated = '{{ $idUpdated }}'; var pageNumber = '{{ $page_number }}'; var pageLength = 5;
        var displayStart = (pageNumber - 1) * pageLength; var firstReload = true; 

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
            
            displayStart: displayStart,
            pageLength: pageLength,

            scrollX: true,
            lengthMenu: [
                [5, 10, 20, 25, 50, 100, 200, -1],
                [5, 10, 20, 25, 50, 100, 200, "All"]
            ],
            ajax: {
                url: url,
                type: 'GET',
                data: function(d) {
                    d.searchDate = "{{ $searchDate }}";
                    d.month = "{{ $month }}";
                }
            },
            columns: [
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center text-bold freeze-column',
                },
                {
                    data: 'number',
                    name: 'number',
                    orderable: true,
                    searchable: true,
                    className: 'align-top freeze-column',
                    render: function(data, type, row) {
                        return '<b>' + data + '</b><br>(' + row.source + ')';
                    }
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
                    data: 'weight',
                    name: 'weight',
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
                    render: function(data, type, row) {
                        if (data === 'IN') {
                            return `<button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalInfoIN">
                                        <i class="mdi mdi-arrow-down-bold"></i>
                                        <span class="badge bg-success text-white">&nbsp;&nbsp; IN &nbsp;&nbsp;</span>
                                    </button>`;
                        } else if (data === 'OUT') {
                            return `<button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalInfoOUT">
                                        <i class="mdi mdi-arrow-up-bold"></i>
                                        <span class="badge bg-danger text-white">OUT</span>
                                    </button>`;
                        } else {
                            return `<h5><span class="badge bg-light text-dark">` + data + `</span></h5>`;
                        }
                    }
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
                    className: 'align-top text-center freeze-column',
                },
            ],
            createdRow: function(row, data, dataIndex) {
                $(row).find('.freeze-column').css('background-color', '#f8f8f8');
            },
            drawCallback: function(settings) {
                if (firstReload && idUpdated) {
                    // Reset URL
                    let urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.toString()) {
                        let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                        history.pushState({}, "", newUrl);
                    }
                    var row = dataTable.row(function(idx, data, node) { return data.id == idUpdated; });
                    if (row.length) {
                        var rowNode = row.node();
                        $('html, body').animate({ scrollTop: $(rowNode).offset().top - $(window).height() / 2 }, 500);
                        // Highlight the row for 3 seconds
                        $(rowNode).addClass('table-light'); $(rowNode).find('.freeze-column').css('background-color', '#E6E6E6');
                        setTimeout(function() {
                            $(rowNode).removeClass('table-light'); $(rowNode).find('.freeze-column').css('background-color', '#f8f8f8');
                        }, 3000);
                    } firstReload = false;
                }
            }
        });
        $('.dataTables_scrollHeadInner thead th').each(function(index) {
            let $this = $(this);
            let isFrozenColumn = index < 2 || index === $('.dataTables_scrollHeadInner thead th').length - 1;
            if (isFrozenColumn) {
                $this.css({
                    'background-color': '#f8f8f8', 'position': 'sticky', 'z-index': '3',
                    'left': index < 2 ? ($this.outerWidth() * index) + 'px' : 'auto',
                    'right': index === $('.dataTables_scrollHeadInner thead th').length - 1 ? '0px' : 'auto'
                });
            } else {
                $this.css({
                    'background-color': '#FAFAFA',
                });
            }
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

<script>
    $(function () {
        // Hide default DataTables controls
        $('.dataTables_wrapper .dataTables_filter').hide();
        $('.dataTables_wrapper .dataTables_length').hide();

        // Build custom control panel
        const controlPanel = `
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2" id="custom-controls">
                <div class="d-flex align-items-center gap-2">
                    <label class="mb-0">
                        <select id="lengthDT" class="form-select form-select-sm">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="-1">All</option>
                        </select>
                    </label>
                    <button data-bs-toggle="modal" data-bs-target="#exportModal" class="btn btn-light waves-effect btn-label waves-light">
                        <i class="mdi mdi-export label-icon"></i> Export Data
                    </button>
                </div>
                <div class="input-group" style="max-width: 350px;">
                    <span class="btn btn-light" type="button" data-bs-toggle="modal" data-bs-target="#sort">
                        <i class="mdi mdi-filter"></i> Period Filter
                    </span>
                    <input class="form-control" id="custom-search-input" type="text" placeholder="Search...">
                </div>
            </div>
        `;

        // Inject control panel before the DataTable
        $('#ssTable_wrapper').prepend(controlPanel);

        // Bind events
        $('#lengthDT').on('change', function () {
            $('#ssTable').DataTable().page.len(this.value).draw();
        });

        $('#custom-search-input').on('keyup change', function () {
            $('#ssTable').DataTable().search(this.value).draw();
        });

        // Optional: Initialize select2 if you want stylized length dropdown
        $('#lengthDT').select2({ minimumResultsForSearch: Infinity, width: '60px' });
    });
</script>


{{-- Modal Search --}}
<div class="modal fade" id="sort" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><i class="mdi mdi-filter label-icon"></i> Period Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="formLoad" action="{{ route('historystock.ta.history', encrypt($id)) }}" method="GET" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-4 mb-2">
                            <label class="form-label">Filter Period</label>
                            <select class="form-select" style="width: 100%" name="searchDate">
                                <option value="All" @if($searchDate == 'All') selected @endif>All</option>
                                <option value="Custom" @if($searchDate == 'Custom') selected @endif>Custom</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label">Select Period</label>
                        <input type="month" id="search1" name="month" class="form-control" value="{{ $month }}" required>
                    </div>
                    {{-- <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label">Date From</label>
                            <input type="date" name="startdate" id="search1" class="form-control" placeholder="from" value="{{ $startdate }}">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Date To</label>
                            <input type="Date" name="enddate" id="search2" class="form-control" placeholder="to" value="{{ $enddate }}">
                        </div>
                    </div> --}}
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
                        // $('#search2').val(null);
                        $('#search1').attr("required", false);
                        // $('#search2').attr("required", false);
                        $('#search1').attr("readonly", true);
                        // $('#search2').attr("readonly", true);
                    } else {
                        $('#search1').attr("required", true);
                        // $('#search2').attr("required", true);
                        $('#search1').attr("readonly", false);
                        // $('#search2').attr("readonly", false);
                    }
                });
                var searchDate = $('select[name="searchDate"]').val();
                if(searchDate == 'All'){
                    $('#search1').attr("required", false);
                    // $('#search2').attr("required", false);
                    $('#search1').attr("readonly", true);
                    // $('#search2').attr("readonly", true);
                }
            </script>
        </div>
    </div>
</div>

{{-- Modal Export --}}
<div class="modal fade" id="exportModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Export Data</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="exportForm" action="{{ route('historystock.ta.export.prod', encrypt($id)) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                    <div class="mb-2">
                        <label class="form-label">Select Period</label>
                        <input type="month" name="month" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    {{-- Export to PDF --}}
                    <button type="submit" name="export_type" value="pdf" class="btn btn-danger waves-effect btn-label waves-light" id="btnExportPdf">
                        <i class="mdi mdi-file-pdf-box label-icon"></i> Print To PDF
                    </button>
                    {{-- Export to Excel --}}
                    <button type="submit" name="export_type" value="excel" class="btn btn-success waves-effect btn-label waves-light">
                        <i class="mdi mdi-file-excel label-icon"></i>Export To Excel
                    </button>
                </div>
            </form>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const exportForm = document.getElementById("exportForm");
                    const exportButtons = exportForm.querySelectorAll("button[type='submit']"); // ✅ Use querySelectorAll

                    exportButtons.forEach(button => {
                        button.addEventListener("click", function (e) {
                            e.preventDefault(); // Prevent default form submission

                            // Run native HTML validation
                            if (!exportForm.reportValidity()) {
                                return; // If invalid, stop export
                            }

                            const exportType = this.value; // "excel" or "pdf"
                            const formData = new FormData(exportForm);
                            formData.set('export_type', exportType);
                            const exportUrl = exportForm.action;

                            // Button UI feedback
                            this.disabled = true;
                            this.innerHTML = `<i class="mdi mdi-loading mdi-spin label-icon"></i> ${exportType === 'pdf' ? 'Printing...' : 'Exporting...'}`;

                            fetch(exportUrl, {
                                method: "POST",
                                body: formData,
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                                }
                            })
                            .then(response => response.blob())
                            .then(blob => {
                                const now = new Date();
                                const formattedDate = now.toLocaleString('en-GB').replace(/[/:, ]+/g, "_");
                                const fileExtension = exportType === "pdf" ? "pdf" : "xlsx";
                                const fileTypeName = exportType === "pdf" ? "Print" : "Export";
                                const fileName = `${fileTypeName}_Stock_RM_${formattedDate}.${fileExtension}`;
                                const downloadUrl = window.URL.createObjectURL(blob);
                                const a = document.createElement("a");
                                a.href = downloadUrl;
                                a.download = fileName;
                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);
                                window.URL.revokeObjectURL(downloadUrl);
                            })
                            .catch(error => {
                                console.error("Export error:", error);
                                alert("An error occurred while exporting.");
                            })
                            .finally(() => {
                                this.disabled = false;
                                this.innerHTML = exportType === 'pdf'
                                    ? '<i class="mdi mdi-file-pdf-box label-icon"></i> Print To PDF'
                                    : '<i class="mdi mdi-file-excel label-icon"></i> Export To Excel';
                            });
                        });
                    });
                });
            </script>
        </div>
    </div>
</div>

{{-- INFO MODAL --}}
<div class="modal fade" id="modalInfoIN" tabindex="-1" aria-labelledby="modalHistoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHistoryLabel">Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <p>Stok Barang Masuk</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalInfoOUT" tabindex="-1" aria-labelledby="modalHistoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHistoryLabel">Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <p>Stok Barang Keluar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection