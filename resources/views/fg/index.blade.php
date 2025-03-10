@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Product FG</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Product FG</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('fg.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Code</label>
                                                    <br>
                                                    <span class="badge bg-info text-white">Auto Generate</span>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Description</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="description" type="text" value="" placeholder="Input Description.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Type Product</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type_product" required>
                                                        <option value="" selected>--Select Type--</option>
                                                        <option value="PP">PP</option>
                                                        <option value="POF">POF</option>
                                                        <option value="CROSSLINK">CROSSLINK</option>
                                                        <option value="SOFTSHRINK">SOFTSHRINK</option>
                                                        <option value="HOT PERFORATION">HOT PERFORATION</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Type Product Code</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type_product_code" required>
                                                        <option value="" selected>--Select Code--</option>
                                                        @foreach ($prodCodes as $code)
                                                        <option value="{{ $code->name_value }}">{{ $code->name_value. " - " .$code->code_format }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Units</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_units" required>
                                                        <option value="" selected>--Select Unit--</option>
                                                        @foreach($units as $unit)
                                                            <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Group</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_groups" required>
                                                        <option value="" selected>--Select Group--</option>
                                                        @foreach($groups as $group)
                                                            <option value="{{ $group->id }}">{{ $group->group_code.' - '.$group->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Group Sub</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_group_subs" required>
                                                        <option value="" selected>--Select Group Sub--</option>
                                                        @foreach($group_subs as $gs)
                                                            <option value="{{ $gs->id }}">{{ $gs->group_sub_code.' - '.$gs->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Group Sub Code</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="group_sub_code" required>
                                                        <option value="" selected>--Select Code--</option>
                                                        @foreach ($subCodes as $code)
                                                        <option value="{{ $code->name_value }}">{{ $code->name_value. " - " .$code->code_format }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Remark</label>
                                                    <input class="form-control" name="remarks" type="text" value="" placeholder="Input Remark..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Status</label><label style="color: darkred">*</label>
                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="status" required>
                                                        <option value="" selected>--Select Status--</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Not Active">Not Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card mt-2" style="background-color:rgb(236, 236, 236)">
                                                <div class="row px-3 mb-2">
                                                    <div class="col-12 text-center mt-2">
                                                        <label>
                                                            Size
                                                            <i class="mdi mdi-information-outline" data-bs-toggle="tooltip" data-bs-placement="top" title="Menghitung Weight = (Thickness/1000) X (Width Ke M) X (Lenght Ke M) X (1 Group Sub Slitting, 2 Jika Group Sub Lainnya) X (0.92)."></i>
                                                        </label>
                                                    </div>
                                                    <hr>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Thickness</label><label style="color: darkred">*</label>
                                                        <div class="input-group">
                                                            <input class="form-control number-format" name="thickness" type="text" value="" placeholder="Input Thickness.." required>
                                                            <div class="input-group-text" style="background-color:rgb(197, 197, 197)">Mic</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Width</label><label style="color: darkred">*</label>
                                                        <input class="form-control number-format" name="width" type="text" value="" placeholder="Input Width.." required>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Width Unit</label><label style="color: darkred">*</label>
                                                        <select class="form-select js-example-basic-single" style="width: 100%" name="width_unit" required>
                                                            <option value="" selected>--Select Unit--</option>
                                                            @foreach($widthunits as $widthunit)
                                                                <option value="{{ $widthunit->id }}" @if($widthunit->unit_code == 'MM') selected="selected" @endif>{{ $widthunit->unit_code }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Length</label><label style="color: darkred">*</label>
                                                        <input class="form-control number-format" name="length" type="text" value="" placeholder="Input Length.." required>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Length Unit</label><label style="color: darkred">*</label>
                                                        <select class="form-select js-example-basic-single" style="width: 100%" name="length_unit" required>
                                                            <option value="" selected>--Select Unit--</option>
                                                            @foreach($lengthunits as $lengthunit)
                                                                <option value="{{ $lengthunit->id }}" @if($lengthunit->unit_code == 'MM') selected="selected" @endif>{{ $lengthunit->unit_code }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Perforasi</label>
                                                        <select class="form-select js-example-basic-single" style="width: 100%" name="perforasi">
                                                            <option value="" selected>--Select Perforasi--</option>
                                                            @foreach($perforasis as $perforasi)
                                                                <option value="{{ $perforasi->name_value }}">{{ $perforasi->name_value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Weight</label><label style="color: darkred">*</label>
                                                        <div class="input-group">
                                                            <input class="form-control number-format" name="weight" type="text" value="" placeholder="Input Weight.." style="background-color:rgb(197, 197, 197)" readonly>
                                                            <div class="input-group-text" style="background-color:rgb(197, 197, 197)">Kg</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mt-2" style="background-color:rgb(236, 236, 236)">
                                                <div class="row px-3 mb-2">
                                                    <div class="col-12 text-center mt-2">
                                                        <label>
                                                            Price
                                                            <i class="mdi mdi-information-outline" data-bs-toggle="tooltip" data-bs-placement="top" title="Menghitung Based Price = (Sales Price) / (Weight) Pastikan Weight Sudah Terkalkulasi."></i>
                                                        </label>
                                                    </div>
                                                    <hr>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Sales Price</label><label style="color: darkred">*</label>
                                                        <input class="form-control number-format" name="sales_price" type="text" value="" placeholder="Input Sales Price.." required>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Sales Price Currency</label><label style="color: darkred">*</label>
                                                        <select class="form-select js-example-basic-single" style="width: 100%" name="sales_price_currency" required>
                                                            <option value="" selected>--Select Currency--</option>
                                                            @foreach($currencies as $cr)
                                                                <option value="{{ $cr->id }}" @if($cr->currency == 'Indonesia Rupiah') selected="selected" @endif>{{ $cr->currency }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Based Price</label><label style="color: darkred">*</label>
                                                        <input class="form-control number-format" name="based_price" type="text" value="" placeholder="Input Based Price.." required>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Based Price Currency</label><label style="color: darkred">*</label>
                                                        <select class="form-select js-example-basic-single" style="width: 100%" name="based_price_currency" required>
                                                            <option value="" selected>--Select Currency--</option>
                                                            @foreach($currencies as $cr)
                                                                <option value="{{ $cr->id }}" @if($cr->currency == 'Indonesia Rupiah') selected="selected" @endif>{{ $cr->currency }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function(){
                                                    function getUnitToMeter(unitName) {
                                                        const unitToMeter = { "M": 1, "CM": 0.01, "INCH": 0.0254, "MM": 0.001 };
                                                        return unitToMeter[unitName] ?? 0;
                                                    }
                                                    function formatNumber(value) {
                                                        let num = parseFloat(value.replace(/\./g, '').replace(',', '.')) || 0; return num;
                                                    }
                                                    function formatNumberDisplay(value) {
                                                        let formatted = value.toFixed(3).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                                        if (formatted.endsWith(',000')) {
                                                            formatted = formatted.slice(0, -4);
                                                        } return formatted;
                                                    }
                                                    function calculateWeight() {
                                                        let thickness = (formatNumber($('[name="thickness"]').val()) || 0) / 1000;
                                                        let width = (formatNumber($('[name="width"]').val()) || 0) * getUnitToMeter($('[name="width_unit"] option:selected').text()) || 0;
                                                        let length = (formatNumber($('[name="length"]').val()) || 0) * getUnitToMeter($('[name="length_unit"] option:selected').text()) || 0;
                                                        let factor = $('[name="id_master_group_subs"] option:selected').text().includes("Slitting") ? 1 : 2;
                                                        let weight = thickness * width * length * factor * 0.92 || 0;
                                                        if (weight <= 0) {
                                                            weight = 0;
                                                        }

                                                        let formattedWeight = new Intl.NumberFormat('de-DE', {
                                                            minimumFractionDigits: 0,
                                                            maximumFractionDigits: 9
                                                        }).format(weight);
                                                        $('[name="weight"]').val(formattedWeight);

                                                        let salesPrice = formatNumber($('[name="sales_price"]').val()) || 0;
                                                        let basedPrice = weight > 0 ? salesPrice / weight : 0;
                                                        basedPrice = Math.round(basedPrice * 1000) / 1000;
                                                        $('[name="based_price"]').val(formatNumberDisplay(basedPrice));
                                                    }
                                                    $('[name="id_master_group_subs"], [name="thickness"], [name="width"], [name="length"], [name="width_unit"], [name="length_unit"], [name="sales_price"]').on('input change', function () {
                                                        calculateWeight();
                                                    });
                                                });
                                            </script>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-plus-box label-icon"></i>Add</button>
                                        </div>
                                    </form>
                                    <script>
                                        document.getElementById('formadd').addEventListener('submit', function(event) {
                                            if (!this.checkValidity()) {
                                                event.preventDefault(); // Prevent form submission if it's not valid
                                                return false;
                                            }
                                            var submitButton = this.querySelector('button[name="sb"]');
                                            submitButton.disabled = true;
                                            submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                            return true; // Allow form submission
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Product FG</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <!-- Modal for bulk delete confirmation -->
        <div class="modal fade" id="deleteselected" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row text-center">
                            <p>Are you sure you want to delete the selected items?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-deleteselected" onclick="bulkDeleted('{{ route('fg.deleteselected') }}')"><i class="mdi mdi-delete label-icon"></i>Delete</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Search --}}
        <div class="modal fade" id="sort" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"><i class="mdi mdi-filter label-icon"></i> Search & Filter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('fg.index') }}" id="formfilter" method="POST" enctype="multipart/form-data">
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
                                    <label class="form-label">Status</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="status">
                                        <option value="" selected>--All--</option>
                                        <option value="Active" @if($status == 'Active') selected @endif>Active</option>
                                        <option value="Not Active" @if($status == 'Not Active') selected @endif>Not Active</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Type Product</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type_product">
                                        <option value="" selected>--All--</option>
                                        <option value="PP" @if($type_product == 'PP') selected @endif>PP</option>
                                        <option value="POF" @if($type_product == 'Additif') selected @endif>POF</option>
                                        <option value="CROSSLINK" @if($type_product == 'CROSSLINK') selected @endif>CROSSLINK</option>
                                        <option value="SOFTSHRINK" @if($type_product == 'SOFTSHRINK') selected @endif>SOFTSHRINK</option>
                                        <option value="HOT PERFORATION" @if($type_product == 'HOT PERFORATION') selected @endif>HOT PERFORATION</option>
                                    </select>
                                </div>
                                <hr class="mt-2">
                                <div class="col-4 mb-2">
                                    <label class="form-label">Filter Date</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="searchDate">
                                        <option value="All" @if($searchDate == 'All') selected @endif>All</option>
                                        <option value="Custom" @if($searchDate == 'Custom') selected @endif>Custom Date</option>
                                    </select>
                                </div>
                                <div class="col-4 mb-2">
                                    <label class="form-label">Date From</label>
                                    <input type="date" name="startdate" id="search1" class="form-control" placeholder="from" value="{{ $startdate }}">
                                </div>
                                <div class="col-4 mb-2">
                                    <label class="form-label">Date To</label>
                                    <input type="Date" name="enddate" id="search2" class="form-control" placeholder="to" value="{{ $enddate }}">
                                </div>
                            </div>
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
                                $('#search2').val(null);
                                $('#search1').attr("required", false);
                                $('#search2').attr("required", false);
                                $('#search1').attr("readonly", true);
                                $('#search2').attr("readonly", true);
                            } else {
                                $('#search1').attr("required", true);
                                $('#search2').attr("required", true);
                                $('#search1').attr("readonly", false);
                                $('#search2').attr("readonly", false);
                            }
                        });
                        var searchDate = $('select[name="searchDate"]').val();
                        if(searchDate == 'All'){
                            $('#search1').attr("required", false);
                            $('#search2').attr("required", false);
                            $('#search1').attr("readonly", true);
                            $('#search2').attr("readonly", true);
                        }

                        document.getElementById('formfilter').addEventListener('submit', function(event) {
                            if (!this.checkValidity()) {
                                event.preventDefault(); // Prevent form submission if it's not valid
                                return false;
                            }
                            var submitButton = this.querySelector('button[name="sbfilter"]');
                            submitButton.disabled = true;
                            submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                            return true; // Allow form submission
                        });
                    </script>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <h5 class="mb-0"><b>Master Product FG</b></h5>
                        List of 
                        @if($product_code != null)
                            (Code<b> - {{ $product_code }}</b>)
                        @endif
                        @if($description != null)
                            (Description<b> - {{ $description }}</b>)
                        @endif
                        @if($status != null)
                            (Status<b> - {{ $status }}</b>)
                        @endif
                        @if($type_product != null)
                            (Type<b> - {{ $type_product }}</b>)
                        @endif
                        @if($searchDate == 'Custom')
                            (Date From<b> {{ $startdate }} </b>Until <b>{{ $enddate }}</b>)
                        @else
                            (<b>All Date</b>)
                        @endif 
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <table class="table table-bordered dt-responsive w-100" id="server-side-table" style="font-size: small">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">
                                        <input type="checkbox" id="checkAllRows">
                                    </th>
                                    <th class="align-middle text-center">#</th>
                                    <th class="align-middle text-center">Description</th>
                                    <th class="align-middle text-center">Unit</th>
                                    <th class="align-middle text-center">Group</th>
                                    <th class="align-middle text-center">Group Sub</th>
                                    <th class="align-middle text-center">Perforasi</th>
                                    <th class="align-middle text-center">Status</th>
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

@php
    $page = session('page', 1);
@endphp

<script>
    $(function() {
        var i = 1;
        var url = '{!! route('fg.index') !!}';
        
        var idUpdated = '{{ $idUpdated }}';
        var pageNumber = '{{ $page_number }}';
        var pageLength = 5;
        var displayStart = (pageNumber - 1) * pageLength;
        var firstReload = true; 

        var currentDate = new Date();
        var formattedDate = currentDate.toISOString().split('T')[0];
        var fileName = "Master Product FG Export - " + formattedDate + ".xlsx";
        var data = {
            product_code: '{{ $product_code }}',
            description: '{{ $description }}',
            type_product: '{{ $type_product }}',
            status: '{{ $status }}',
            searchDate: '{{ $searchDate }}',
            startdate: '{{ $startdate }}',
            enddate: '{{ $enddate }}'
        };
        var requestData = Object.assign({}, data);
        requestData.flag = 1;
        var dataTable = $('#server-side-table').DataTable({
            dom: '<"top d-flex"<"position-absolute top-0 end-0 d-flex"fl><"pull-left col-sm-12 col-md-5"B>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>><"clear:both">',
            initComplete: function(settings, json) {
                $('.dataTables_filter').html('<div class="input-group">' +
                '<button class="btn btn-sm btn-light me-1" type="button" id="custom-button" data-bs-toggle="modal" data-bs-target="#sort"><i class="mdi mdi-filter label-icon"></i> Sort & Filter</button>' +
                '<input class="form-control me-1" id="custom-search-input" type="text" placeholder="Search...">' +
                '</div>');
                $('.top').prepend(
                    `<div class='pull-left'>
                        <div class="btn-group mb-2" style="margin-right: 10px;"> <!-- Added inline style for margin -->
                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-checkbox-multiple-marked-outline"></i> Bulk Actions <i class="fas fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteselected"><i class="mdi mdi-trash-can"></i> Delete Selected</button></li>
                            </ul>
                        </div>
                    </div>`
                );
            },
            buttons: [
                {
                    extend: "excel",
                    text: '<i class="fas fa-file-excel"></i> Export to Excel',
                    action: function (e, dt, button, config) {
                        $.ajax({
                            url: url,
                            method: "GET",
                            data: requestData,
                            success: function (response) {
                                generateExcel(response, fileName);
                            },
                            error: function (error) {
                                console.error(
                                    "Error sending data to server:",
                                    error
                                );
                            },
                        });
                    },
                },
            ],
            language: {
                processing: '<div id="custom-loader" class="dataTables_processing"></div>'
            },
            processing: true,
            serverSide: true,
            
            displayStart: displayStart,
            pageLength: pageLength,

            lengthMenu: [
                [5, 10, 20, 25, 50, 100, 200, -1],
                [5, 10, 20, 25, 50, 100, 200, "All"]
            ],
            language: {
                lengthMenu: '<select class="form-select" style="width: 100%">' +
                            '<option value="5">5</option>' +
                            '<option value="10">10</option>' +
                            '<option value="20">20</option>' +
                            '<option value="25">25</option>' +
                            '<option value="50">50</option>' +
                            '<option value="100">100</option>' +
                            '<option value="200">200</option>' +
                            '<option value="-1">All</option>' +
                            '</select>'
            },
            aaSorting: [],
            ajax: {
                url: url,
                type: 'GET',
                data: data
            },
            columns: [{
                    data: 'bulk-action',
                    name: 'bulk-action',
                    className: 'align-middle text-center',
                    orderable: false,
                    searchable: false
                },
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
                    data: 'product_code',
                    name: 'product_code',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle',
                    render: function(data, type, row) {
                        return '<b>' + row.product_code + '</b><br>' + row.description + '<br> <b>Product Code: </b>' + row.type_product_code;
                    },
                },
                {
                    data: 'unit',
                    name: 'unit',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                },
                {
                    data: 'groupname',
                    name: 'groupname',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                },
                {
                    data: 'groupsub',
                    name: 'groupsub',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        return row.groupsub + '<br> <b>Group Sub Code: </b>' + row.group_sub_code;
                    },
                },
                {
                    data: 'perforasi',
                    name: 'perforasi',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.perforasi == null){
                            html = '<span class="badge bg-secondary text-white">Null</span>';
                        } else {
                            html = row.perforasi;
                        }
                        return html;
                    },
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: true,
                    searchable: true,
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.status == 'Active'){
                            html = '<span class="badge bg-success text-white">Active</span>';
                        } else {
                            html = '<span class="badge bg-danger text-white">Inactive</span>';
                        }
                        return html;
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-middle text-center',
                },
            ],
            bAutoWidth: false,
            columnDefs: [{
                width: "1%",
                targets: [0]
            }],
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
                        $(rowNode).addClass('table-info');
                        setTimeout(function() {
                            $(rowNode).removeClass('table-info');
                        }, 3000);
                    }
                    firstReload = false;
                }
            }
        });

        $(document).on('keyup', '#custom-search-input', function () {
            dataTable.search(this.value).draw();
        });
        $('.dataTables_processing').css('z-index', '9999');
    });
</script>

@endsection