@extends('historystock.main')

@section('content')

<table class="table table-bordered table-hover dt-responsive w-100" id="ssTable" style="font-size: small">
    <thead>
        <tr>
            <th class="align-middle text-center">#</th>
            <th class="align-middle text-center">Product Code</th>
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

        var idUpdated = '{{ $idUpdated }}';
        var pageNumber = '{{ $page_number }}';
        var pageLength = 5;
        var displayStart = (pageNumber - 1) * pageLength;
        var firstReload = true; 

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
                    className: 'align-top text-center text-bold',
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
                $(row).find('.freeze-column').css('background-color', '#FAFAFA');
            },
            drawCallback: function(settings) {
                if (firstReload && idUpdated) {
                    // Reset URL
                    let urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.toString()) {
                        let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                        history.pushState({}, "", newUrl);
                    }
                    var row = dataTable.row(function(idx, data, node) {
                        return data.id == idUpdated;
                    });

                    if (row.length) {
                        var rowNode = row.node();
                        $('html, body').animate({
                            scrollTop: $(rowNode).offset().top - $(window).height() / 2
                        }, 500);
                        // Highlight the row for 5 seconds
                        $(rowNode).addClass('table-success');
                        $(rowNode).find('.freeze-column').css('background-color', '#CFEBE0');
                        setTimeout(function() {
                            $(rowNode).removeClass('table-success');
                            $(rowNode).find('.freeze-column').css('background-color', '#FAFAFA');
                        }, 3000);
                    }
                    firstReload = false;
                }
            }
        });
        $('.dataTables_scrollHeadInner thead th').each(function(index) {
            let $this = $(this);
            let isFrozenColumn = index < 2 || index === $('.dataTables_scrollHeadInner thead th').length - 1;
            if (isFrozenColumn) {
                $this.css({
                    'background-color': '#FAFAFA',
                    'position': 'sticky',
                    'z-index': '3',
                    'left': index < 2 ? ($this.outerWidth() * index) + 'px' : 'auto',
                    'right': index === $('.dataTables_scrollHeadInner thead th').length - 1 ? '0px' : 'auto'
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

@endsection
