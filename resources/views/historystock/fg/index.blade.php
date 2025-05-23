@extends('historystock.main')
@section('content')

<table class="table table-bordered table-hover dt-responsive w-100" id="ssTable" style="font-size: small">
    <thead>
        <tr>
            <th class="align-middle text-center">#</th>
            <th class="align-middle text-center">Product Code</th>
            <th class="align-middle text-center">Description</th>
            <th class="align-middle text-center">Thickness</th>
            <th class="align-middle text-center">Perforasi</th>
            <th class="align-middle text-center">Stock</th>
            <th class="align-middle text-center">Unit</th>
            <th class="align-middle text-center">Weight Stock</th>
            <th class="align-middle text-center">Sub Group</th>
            <th class="align-middle text-center">Action</th>
        </tr>
    </thead>
</table>

<script>
    $(function() {
        var url = '{!! route('historystock.fg') !!}';
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
                    d.product_code = "{{ $product_code }}";
                    d.description = "{{ $description }}";
                    d.type = "{{ $type }}";
                    d.thickness = "{{ $thickness }}";
                    d.id_master_group_subs = "{{ $id_master_group_subs }}";
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
                    data: 'product_code',
                    name: 'product_code',
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
                    data: 'thickness',
                    name: 'thickness',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        return data ? data + ' Mic' : '-';
                    }
                },
                {
                    data: 'perforasi',
                    name: 'perforasi',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
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
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: 'weight_stock',
                    name: 'weight_stock',
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
                    data: 'sub_groupname',
                    name: 'sub_groupname',
                    orderable: false,
                    searchable: false,
                    className: 'align-top',
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><i class="mdi mdi-filter label-icon"></i> Sort & Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="formLoad" action="{{ route('historystock.fg') }}" method="GET" enctype="multipart/form-data">
                @csrf
                
                <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label">Code</label>
                            <input class="form-control" name="product_code" type="text" value="{{ $product_code }}" placeholder="Filter Code..">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Description</label>
                            <input class="form-control" name="description" type="text" value="{{ $description }}" placeholder="Filter Description..">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Type</label>
                            <select class="form-select js-example-basic-single" style="width: 100%" name="type">
                                <option value="" selected>--All--</option>
                                <option value="PP" @if($type == 'PP') selected @endif>PP</option>
                                <option value="POF" @if($type == 'Additif') selected @endif>POF</option>
                                <option value="CROSSLINK" @if($type == 'CROSSLINK') selected @endif>CROSSLINK</option>
                                <option value="SOFTSHRINK" @if($type == 'SOFTSHRINK') selected @endif>SOFTSHRINK</option>
                                <option value="HOT PERFORATION" @if($type == 'HOT PERFORATION') selected @endif>HOT PERFORATION</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label">Tickness</label>
                            <input class="form-control" name="thickness" type="number" value="{{ $thickness }}" placeholder="Filter Thickness (Mic)..">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Group Sub</label>
                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_group_subs">
                                <option value="" selected>--Select Group Sub--</option>
                                @foreach($group_subs as $gs)
                                    <option value="{{ $gs->id }}" @if($gs->id == $id_master_group_subs) selected @endif>{{ $gs->name }}</option>
                                @endforeach
                            </select>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Export Data</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="exportForm" action="{{ route('historystock.fg.export') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label class="form-label">Code / Description</label>
                            <input class="form-control" name="keyword" type="text" placeholder="Input Keyword..">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Type</label>
                            <select class="form-select js-example-basic-single" style="width: 100%" name="type">
                                <option value="" selected>--All--</option>
                                <option value="PP">PP</option>
                                <option value="POF">POF</option>
                                <option value="CROSSLINK">CROSSLINK</option>
                                <option value="SOFTSHRINK">SOFTSHRINK</option>
                                <option value="HOT PERFORATION">HOT PERFORATION</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label">Tickness</label>
                            <input class="form-control" name="thickness" type="number" placeholder="Filter Thickness (Mic)..">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Group Sub</label>
                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_group_subs">
                                <option value="" selected>--All--</option>
                                @foreach($group_subs as $gs)
                                    <option value="{{ $gs->id }}">{{ $gs->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label">Select Period</label>
                        <input type="month" name="month" class="form-control" required>
                    </div>

                    {{-- <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label">Date From</label>
                            <input type="date" name="dateFrom" class="form-control" required>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label">Date To</label>
                            <input type="date" name="dateTo" class="form-control" required>
                            <small class="text-danger d-none" id="dateToError"><b>Date To</b> cannot be before <b>Date From</b></small>
                        </div>
                    </div> --}}
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
                    const exportButtons = exportForm.querySelectorAll("button[type='submit']");
            
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
                            this.innerHTML = `<i class="mdi mdi-loading mdi-spin label-icon"></i> Exporting...`;
            
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
                                const fileName = `${fileTypeName}_Stock_FG_${formattedDate}.${fileExtension}`;
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

@endsection
