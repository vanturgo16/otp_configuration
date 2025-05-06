@extends('historystock.main')
@section('content')

<table class="table table-bordered table-hover dt-responsive w-100" id="ssTable" style="font-size: small">
    <thead>
        <tr>
            <th class="align-middle text-center">#</th>
            <th class="align-middle text-center">RM Code</th>
            <th class="align-middle text-center">Description</th>
            <th class="align-middle text-center">Stok Saat Ini</th>
            <th class="align-middle text-center">Unit</th>
            <th class="align-middle text-center">Action</th>
        </tr>
    </thead>
</table>

<script>
    $(function() {
        var url = '{!! route('historystock.rm') !!}';
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
                    d.rm_code = "{{ $rm_code }}";
                    d.description = "{{ $description }}";
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
                    data: 'rm_code',
                    name: 'rm_code',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-bold freeze-column'
                },
                {
                    data: 'description',
                    name: 'description',
                    orderable: true,
                    searchable: true,
                    className: 'align-top'
                },
                {
                    data: 'stock',
                    name: 'stock',
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
                    data: 'unit_code',
                    name: 'unit_code',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center text-bold'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center text-bold freeze-column',
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
        }); $('.dataTables_processing').css('z-index', '9999');
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
                    <button class="btn btn-light" type="button" id="custom-button" data-bs-toggle="modal" data-bs-target="#sort">
                        <i class="mdi mdi-filter"></i> Sort & Filter
                    </button>
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
        $('#lengthDT').select2({ minimumResultsForSearch: Infinity, width: '60px' });
    });
</script>


{{-- Modal Search --}}
<div class="modal fade" id="sort" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><i class="mdi mdi-filter label-icon"></i> Sort & Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="formLoad" action="{{ route('historystock.rm') }}" method="GET" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label class="form-label">Code</label>
                            <input class="form-control" name="rm_code" type="text" value="{{ $rm_code }}" placeholder="Filter Code..">
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label">Description</label>
                            <input class="form-control" name="description" type="text" value="{{ $description }}" placeholder="Filter Description..">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info waves-effect btn-label waves-light" name="sbfilter"><i class="mdi mdi-filter label-icon"></i> Filter</button>
                </div>
            </form>
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
            <form id="exportForm" action="{{ route('historystock.rm.export') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label class="form-label">Code / Description</label>
                            <input class="form-control" name="keyword" type="text" placeholder="Input Keyword..">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label">Date From</label>
                            <input type="date" name="dateFrom" class="form-control" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Date To</label>
                            <input type="date" name="dateTo" class="form-control" required>
                            <small class="text-danger d-none" id="dateToError"><b>Date To</b> cannot be before <b>Date From</b></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light">
                        <i class="mdi mdi-file-excel label-icon"></i>Export To Excel
                    </button>
                </div>
            </form>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const exportForm = document.getElementById("exportForm");
                    const exportButton = exportForm.querySelector("button[type='submit']");
            
                    exportForm.addEventListener("submit", function (event) {
                        event.preventDefault(); // Prevent normal form submission
            
                        let formData = new FormData(exportForm);
                        let url = exportForm.action;
            
                        // Disable button to prevent multiple clicks
                        exportButton.disabled = true;
                        exportButton.innerHTML = '<i class="mdi mdi-loading mdi-spin label-icon"></i>Exporting...';
            
                        fetch(url, {
                            method: "POST",
                            body: formData,
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                            }
                        })
                        .then(response => response.blob()) // Expect a file response
                        .then(blob => {
                            let now = new Date();
                            let formattedDate = now.getDate().toString().padStart(2, '0') + "_" +
                                                (now.getMonth() + 1).toString().padStart(2, '0') + "_" +
                                                now.getFullYear() + "_" +
                                                now.getHours().toString().padStart(2, '0') + "_" +
                                                now.getMinutes().toString().padStart(2, '0');
                            let filename = `Export_Stock_RM_${formattedDate}.xlsx`;
            
                            let downloadUrl = window.URL.createObjectURL(blob);
                            let a = document.createElement("a");
                            a.href = downloadUrl;
                            a.download = filename; // Set dynamic filename
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
                            exportButton.disabled = false;
                            exportButton.innerHTML = '<i class="mdi mdi-file-excel label-icon"></i> Export To Excel';
                        });
                    });
                });
            </script>
        </div>
    </div>
</div>

@endsection
