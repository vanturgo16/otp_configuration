@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <a href="{{ route('fg.index') }}" class="btn btn-primary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left label-icon"></i> Back To List Product FG
                        </a>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fg.index') }}">Product FG</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.alert')

        <form action="{{ route('fg.update', encrypt($data->id)) }}" id="formedit" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="page" value="{{ $page }}">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center py-3">
                            <h5 class="mb-0">Edit</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label">Code</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="product_code" id="product_code" type="text" value="{{ $data->product_code }}" placeholder="Input Code.." style="background-color:rgb(197, 197, 197)" required readonly>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Description</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="description" type="text" value="{{ $data->description }}" placeholder="Input Description.." required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Type Product</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type_product" required>
                                        <option value="" selected>--Select Type--</option>
                                        <option value="PP" @if($data->type_product === "PP") selected="selected" @endif>PP</option>
                                        <option value="POF" @if($data->type_product === "POF") selected="selected" @endif>POF</option>
                                        <option value="CROSSLINK" @if($data->type_product === "CROSSLINK") selected="selected" @endif>CROSSLINK</option>
                                        <option value="SOFTSHRINK" @if($data->type_product === "SOFTSHRINK") selected="selected" @endif>SOFTSHRINK</option>
                                        <option value="HOT PERFORATION" @if($data->type_product === "HOT PERFORATION") selected="selected" @endif>HOT PERFORATION</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Type Product Code</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type_product_code" required>
                                        <option value="" selected>--Select Code--</option>
                                        @foreach ($prodCodes as $code)
                                        <option value="{{ $code->name_value }}" @if ($code->name_value == $data->type_product_code) selected @endif>{{ $code->name_value. " - " .$code->code_format }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Units</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_units" required>
                                        <option value="" selected>--Select Unit--</option>
                                        @foreach($allunits as $unit)
                                            <option value="{{ $unit->id }}" @if($data->id_master_units === $unit->id) selected="selected" @endif>{{ $unit->unit }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Group</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_groups" required>
                                        <option value="" selected>--Select Group--</option>
                                        @foreach($allgroups as $group)
                                            <option value="{{ $group->id }}" @if($data->id_master_groups === $group->id) selected="selected" @endif>{{ $group->group_code.' - '.$group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Group Sub</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_group_subs" required>
                                        <option value="" selected>--Select Group Sub--</option>
                                        @foreach($allgroup_subs as $gs)
                                            <option value="{{ $gs->id }}" @if($data->id_master_group_subs === $gs->id) selected="selected" @endif>{{ $gs->group_sub_code.' - '.$gs->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Group Sub Code</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="group_sub_code" required>
                                        <option value="" selected>--Select Code--</option>
                                        @foreach ($subCodes as $code)
                                        <option value="{{ $code->name_value }}" @if ($code->name_value == $data->group_sub_code) selected @endif>{{ $code->name_value. " - " .$code->code_format }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Status</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="status" required>
                                        <option value="" selected>--Select Status--</option>
                                        <option value="Active" @if($data->status === "Active") selected="selected" @endif>Active</option>
                                        <option value="Not Active" @if($data->status === "Not Active") selected="selected" @endif>Not Active</option>
                                    </select>
                                </div>
                                {{-- <div class="col-6 mb-2">
                                    <label class="form-label">Department</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_departements">
                                        <option value="" selected>--Select Department--</option>
                                        @foreach($alldepartments as $depart)
                                            <option value="{{ $depart->id }}" @if($data->id_master_departements === $depart->id) selected="selected" @endif>{{ $depart->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-6 mb-2">
                                    <label class="form-label">Remark</label>
                                    <input class="form-control" name="remarks" type="text" value="{{ $data->remarks }}" placeholder="Input Remark..">
                                </div>
                            </div>
                            <div class="card mt-2" style="background-color:rgb(236, 236, 236)">
                                <div class="row px-3 mb-2">
                                    <div class="col-12 text-center mt-2">
                                        <label>Size</label>
                                    </div>
                                    <hr>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Thickness</label><label style="color: darkred">*</label>
                                        <div class="input-group">
                                            <input class="form-control" name="thickness" type="text" value="{{ $data->thickness }}" placeholder="Input Thickness.." required>
                                            <div class="input-group-text" style="background-color:rgb(197, 197, 197)">Mic</div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-2">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Width</label><label style="color: darkred">*</label>
                                        <input class="form-control" name="width" type="text" value="{{ $data->width }}" placeholder="Input Width.." required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Width Unit</label><label style="color: darkred">*</label>
                                        <select class="form-select js-example-basic-single" style="width: 100%" name="width_unit" required>
                                            <option value="" selected>--Select Unit--</option>
                                            @foreach($widthunits as $widthunit)
                                                <option value="{{ $widthunit->id }}" @if($data->width_unit == $widthunit->id) selected="selected" @endif>{{ $widthunit->unit_code }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <input class="form-control" name="width_unit" type="text" value="{{ $data->width_unit }}" placeholder="Input Width Unit.."> --}}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Length</label><label style="color: darkred">*</label>
                                        <input class="form-control" name="length" type="text" value="{{ $data->height }}" placeholder="Input Length.." required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Length Unit</label><label style="color: darkred">*</label>
                                        <select class="form-select js-example-basic-single" style="width: 100%" name="length_unit" required>
                                            <option value="" selected>--Select Unit--</option>
                                            @foreach($lengthunits as $lengthunit)
                                                <option value="{{ $lengthunit->id }}" @if($data->height_unit == $lengthunit->id) selected="selected" @endif>{{ $lengthunit->unit_code }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <input class="form-control" name="height_unit" type="text" value="{{ $data->height_unit }}" placeholder="Input Length Unit.."> --}}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Perforasi</label>
                                        <select class="form-select js-example-basic-single" style="width: 100%" name="perforasi">
                                            <option value="" selected>--Select Perforasi--</option>
                                            @foreach($perforasis as $perforasi)
                                                <option value="{{ $perforasi->name_value }}" @if($data->perforasi === $perforasi->name_value) selected="selected" @endif>{{ $perforasi->name_value }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <input class="form-control" name="perforasi" type="text" value="{{ $data->perforasi }}" placeholder="Input Perforasi.."> --}}
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Weight</label><label style="color: darkred">*</label>
                                        <input class="form-control" name="weight" type="text" value="{{ $data->weight }}" placeholder="Input Weight.." style="background-color:rgb(197, 197, 197)" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-2" style="background-color:rgb(236, 236, 236)">
                                <div class="row px-3 mb-2">
                                    <div class="col-12 text-center mt-2">
                                        <label>Price</label>
                                    </div>
                                    <hr>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Sales Price</label><label style="color: darkred">*</label>
                                        <input class="form-control rupiah-input" name="sales_price" type="text" value="{{ $data->sales_price }}" placeholder="Input Sales Price.." required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Sales Price Currency</label><label style="color: darkred">*</label>
                                        <select class="form-select js-example-basic-single" style="width: 100%" name="sales_price_currency" required>
                                            <option value="" selected>--Select Currency--</option>
                                            @foreach($allcurrencies as $cr)
                                                <option value="{{ $cr->id }}" @if($data->sales_price_currency === $cr->id) selected="selected" @endif>{{ $cr->currency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Based Price</label><label style="color: darkred">*</label>
                                        <input class="form-control rupiah-input" name="based_price" type="text" value="{{ $data->based_price }}" placeholder="Input Based Price.." style="background-color:rgb(197, 197, 197)" required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Based Price Currency</label><label style="color: darkred">*</label>
                                        <select class="form-select js-example-basic-single" style="width: 100%" name="based_price_currency" required>
                                            <option value="" selected>--Select Currency--</option>
                                            @foreach($allcurrencies as $cr)
                                                <option value="{{ $cr->id }}" @if($data->based_price_currency === $cr->id) selected="selected" @endif>{{ $cr->currency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="col-6 mb-2">
                                        <label class="form-label">Type</label>
                                        <input class="form-control" name="type" type="text" value="{{ $data->type }}" placeholder="Input Type..">
                                    </div> --}}
                                    {{-- <div class="col-6 mb-2">
                                        <label class="form-label">Stock</label>
                                        <input class="form-control" name="stock" type="text" value="{{ $data->stock }}" placeholder="Input Stock..">
                                    </div> --}}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12 align-right">
                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb">
                                        <i class="mdi mdi-update label-icon"></i>Update
                                    </button>
                                </div>
                            </div>
                            
                            <script>
                                $(document).ready(function(){
                                    var unitToMeter = {
                                        "M": 1,
                                        "CM": 0.01,
                                        "INCH": 0.0254,
                                        "MM": 0.001
                                    };
                                    function calculateWeight() {
                                        var thickness = parseFloat($('[name="thickness"]').val()) || 0;
                                        thickness = thickness / 1000;
                                        var width = (parseFloat($('[name="width"]').val()) || 0) * unitToMeter[$('[name="width_unit"] option:selected').text()];
                                        var length = (parseFloat($('[name="length"]').val()) || 0) * unitToMeter[$('[name="length_unit"] option:selected').text()];
                                        var id_master_group_subs = $('[name="id_master_group_subs"]').find(":selected").text();
                                        var factor = (id_master_group_subs.includes("Slitting")) ? 1 : 2;
                                        var weight = thickness * width * length * factor * 0.92;
                                        if (isNaN(weight)) {
                                            weight = 0;
                                        } else {
                                            weight = parseFloat(weight.toFixed(2));
                                        }
                                        $('[name="weight"]').val(weight);

                                        var salesPriceInput = $('[name="sales_price"]').val();
                                        var salesPrice = parseFloat(salesPriceInput.replace(/,/g, ''));
                                        var basedPrice = salesPrice / weight || 0;

                                        // $('[name="based_price"]').val(basedPrice.toFixed(2));
                                        $('[name="based_price"]').val(basedPrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                                    }
                                    $('[name="id_master_group_subs"], [name="thickness"], [name="width"], [name="length"], [name="width_unit"], [name="length_unit"], [name="sales_price"]').change(function(){
                                        calculateWeight();
                                    });
                                    
                                    calculateWeight();

                                    var rupiah_inputs = document.querySelectorAll('.rupiah-input');
                                    rupiah_inputs.forEach(function(inputElement) {
                                        inputElement.addEventListener('keyup', function(e) {
                                            this.value = formatCurrency(this.value, ' ');
                                        });
                                    });
                                    function formatCurrency(number, prefix) {
                                        var number_string = number.replace(/[^.\d]/g, '').toString(),
                                            split = number_string.split('.'),
                                            sisa = split[0].length % 3,
                                            rupiah = split[0].substr(0, sisa),
                                            ribuan = split[0].substr(sisa).match(/\d{1,3}/gi);

                                        if (ribuan) {
                                            separator = sisa ? ',' : '';
                                            rupiah += separator + ribuan.join(',');
                                        }

                                        rupiah = split[1] != undefined ? rupiah + '.' + split[1] : rupiah;
                                        return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    document.getElementById('formedit').addEventListener('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault(); // Prevent form submission if it's not valid
            return false;
        }
        var submitButton = this.querySelector('button[name="sb"]');
        submitButton.disabled = true;
        submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
        return true; // Allow form submission
    });

    $(document).ready(function() {
        $('select[name="type_product"]').on('change', function() {
            var type = $(this).val();
            var id = "{{ $data->id }}";

            var url = '{{ route("fg.generateFormattedId", [":type", ":id"]) }}';
            url = url.replace(':type', type);
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    $('#product_code').val(response);
                }
            });
        });
    });
</script>
@endsection