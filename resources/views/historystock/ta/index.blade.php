@extends('historystock.main')

@section('content')

<table>
    <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100" id="ssTable" style="font-size: small">
        <thead>
            <tr>
                <th class="align-middle text-center">#</th>
                <th class="align-middle text-center">Code / Description</th>
                <th class="align-middle text-center">Stock Saat Ini</th>
                <th class="align-middle text-center">Datang</th>
                <th class="align-middle text-center">Pakai</th>
                <th class="align-middle text-center">Department</th>
                <th class="align-middle text-center">Barcode</th>
                <th class="align-middle text-center">Action</th>
            </tr>
        </thead>
    </table>
</table>

@endsection
