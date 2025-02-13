@extends('historystock.main')

@section('content')

<table class="table table-bordered table-striped table-hover dt-responsive w-100" id="ssTable" style="font-size: small">
    <thead>
        <tr>
            <th class="align-middle text-center">#</th>
            <th class="align-middle text-center">WIP Code</th>
            <th class="align-middle text-center">Description</th>
            <th class="align-middle text-center">Stock</th>
            <th class="align-middle text-center">Barcode</th>
            <th class="align-middle text-center">Action</th>
        </tr>
    </thead>
</table>

@endsection
