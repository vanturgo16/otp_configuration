@extends('historystock.main')

@section('content')

<table class="table table-bordered table-striped table-hover dt-responsive w-100" id="ssTable" style="font-size: small">
    <thead>
        <tr>
            <th rowspan="2" class="align-middle text-center">#</th>
            <th rowspan="2" class="align-middle text-center">Type</th>
            <th rowspan="2" class="align-middle text-center">Code / Description</th>
            <th colspan="4" class="align-middle text-center">Stock</th>
            <th rowspan="2" class="align-middle text-center">Barcode</th>
            <th rowspan="2" class="align-middle text-center">Department</th>
            <th rowspan="2" class="align-middle text-center">Action</th>
        </tr>
        <tr>
            <th class="align-middle text-center">Stock Master</th>
            <th class="align-middle text-center">Datang</th>
            <th class="align-middle text-center">Pakai</th>
            <th class="align-middle text-center">Total</th>
        </tr>
    </thead>
</table>

<script>
    $(function() {
        var url = '{!! route('historystock.ta') !!}';
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
                    data: 'typeTA',
                    name: 'typeTA',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
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
                    data: 'total_in',
                    name: 'total_in',
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
                    data: 'total_out',
                    name: 'total_out',
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
                    data: 'total_stock',
                    name: 'total_stock',
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
                    data: 'barcode',
                    name: 'barcode',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: 'departement_name',
                    name: 'departement_name',
                    orderable: false,
                    searchable: false,
                    className: 'align-top',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
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
