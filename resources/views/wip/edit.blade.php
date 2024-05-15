@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <a href="{{ route('wip.index') }}" class="btn btn-primary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left label-icon"></i> Back To List Master WIP
                        </a>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('wip.index') }}">WIP</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.alert')

        <form action="{{ route('wip.update', encrypt($data->id)) }}" id="formedit" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center py-3">
                            <h5 class="mb-0">Edit</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label">Description</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="description" type="text" value="{{ $data->description }}" placeholder="Input Description.." required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Type Product</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type" required>
                                        <option value="" selected>--Select Type--</option>
                                        <option value="PP" @if($data->type === "PP") selected="selected" @endif>PP</option>
                                        <option value="POF" @if($data->type === "POF") selected="selected" @endif>POF</option>
                                        <option value="CROSSLINK" @if($data->type === "CROSSLINK") selected="selected" @endif>CROSSLINK</option>
                                        <option value="SOFTSHRINK" @if($data->type === "SOFTSHRINK") selected="selected" @endif>SOFTSHRINK</option>
                                        <option value="HOT PERFORATION" @if($data->type === "HOT PERFORATION") selected="selected" @endif>HOT PERFORATION</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Process Production</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_process_productions" required>
                                        <option value="" selected>--Select Process Production--</option>
                                        @foreach($allprocess as $pr)
                                            <option value="{{ $pr->id }}" @if($data->id_master_process_productions === $pr->id) selected="selected" @endif>{{ $pr->process }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Code</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="wip_code" id="wip_code" type="text" value="{{ $data->wip_code }}" placeholder="Input Code.." required>
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
                                    <label class="form-label">Status</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="status" required>
                                        <option value="" selected>--Select Status--</option>
                                        <option value="Active" @if($data->status === "Active") selected="selected" @endif>Active</option>
                                        <option value="Not Active" @if($data->status === "Not Active") selected="selected" @endif>Not Active</option>
                                    </select>
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
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Length</label><label style="color: darkred">*</label>
                                        <input class="form-control" name="length" type="text" value="{{ $data->length }}" placeholder="Input Length.." required>
                                    </div>
                                    <div class="col-6 mb-2">
                                        {{ $data->length_unit }}
                                        <label class="form-label">Length Unit</label><label style="color: darkred">*</label>
                                        <select class="form-select js-example-basic-single" style="width: 100%" name="length_unit" required>
                                            <option value="" selected>--Select Unit--</option>
                                            @foreach($lengthunits as $lengthunit)
                                                <option value="{{ $lengthunit->id }}" @if($data->length_unit == $lengthunit->id) selected="selected" @endif>{{ $lengthunit->unit_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Perforasi</label><label style="color: darkred">*</label>
                                        <select class="form-select js-example-basic-single" style="width: 100%" name="perforasi" required>
                                            <option value="" selected>--Select Perforasi--</option>
                                            @foreach($perforasis as $perforasi)
                                                <option value="{{ $perforasi->name_value }}" @if($data->perforasi == $perforasi->name_value) selected="selected" @endif>{{ $perforasi->name_value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Weight</label><label style="color: darkred">*</label>
                                        <input class="form-control" name="weight" type="text" value="{{ $data->weight }}" placeholder="Input Weight.." style="background-color:rgb(197, 197, 197)" readonly>
                                    </div>
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
                                        "CM": 0.01,
                                        "INCH": 0.0254,
                                        "MM": 0.001
                                    };
                                    function calculateWeight() {
                                        var thickness = parseFloat($('[name="thickness"]').val()) || 0 / 1000;
                                        var width = (parseFloat($('[name="width"]').val()) || 0) * unitToMeter[$('[name="width_unit"] option:selected').text()];
                                        var length = (parseFloat($('[name="length"]').val()) || 0) * unitToMeter[$('[name="length_unit"] option:selected').text()];
                                        var id_master_group_subs = $('[name="id_master_group_subs"]').find(":selected").text();
                                        var factor = (id_master_group_subs.includes("Slitting")) ? 1 : 2;
                                        var weight = thickness * width * length * factor * 0.92;
                                        if (isNaN(weight)) {
                                            weight = 0;
                                        } else {
                                            weight = Math.ceil(weight * 100) / 100;
                                        }
                                        $('[name="weight"]').val(weight.toFixed(2));
                                    }
                                    $('[name="id_master_group_subs"], [name="thickness"], [name="width"], [name="length"], [name="width_unit"], [name="length_unit"]').change(function(){
                                        calculateWeight();
                                    });
                                    
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
</script>
@endsection