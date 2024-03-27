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
                            <li class="breadcrumb-item active">
                                Add 
                                @if($flag == 'wip')
                                    WIP
                                @else
                                    WIP Blow
                                @endif
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.alert')

        <!-- Modal for required fields -->
        <div class="modal fade" id="requiredModal" data-bs-backdrop="static" role="dialog" aria-labelledby="staticBackdropLabel">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Required Fields</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="text-center">
                                <h1>
                                    <span class="mdi mdi-alert"></span>
                                </h1>
                            </div>
                            <p class="text-center">Please fill in all required fields before adding to the table.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('wip.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="wip_type" value="{{ $flag }}" readonly>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center py-3">
                            <h5 class="mb-0">
                                <b>Add New WIP </b>
                                @if($flag == 'wip')
                                @else
                                    (WIP BLOW)
                                @endif
                            </h5>
                        </div>
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label">Description</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="description" type="text" value="" placeholder="Input Description.." required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Type</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="type" required>
                                        <option value="" selected>--Select Type--</option>
                                        <option value="PP">PP</option>
                                        <option value="POF">POF</option>
                                        <option value="CROSSLINK">CROSSLINK</option>
                                        <option value="PPNC">PPNC</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Process Production</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_process_productions" required>
                                        <option value="" selected>--Select Process Production--</option>
                                        @foreach($process as $pr)
                                            <option value="{{ $pr->id }}">{{ $pr->process }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-6 mb-2">
                                    <label class="form-label">Quantity</label>
                                    <input class="form-control" name="qty" type="number" value="0" placeholder="Input Quantity..">
                                </div> --}}
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
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Group Sub</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_group_subs" required>
                                        <option value="" selected>--Select Group Sub--</option>
                                        @foreach($group_subs as $gs)
                                            <option value="{{ $gs->id }}">{{ $gs->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-6 mb-2">
                                    <label class="form-label">Department</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_departements">
                                        <option value="" selected>--Select Department--</option>
                                        @foreach($departments as $depart)
                                            <option value="{{ $depart->id }}">{{ $depart->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                {{-- <div class="col-6 mb-2">
                                    <label class="form-label">Stock</label>
                                    <input class="form-control" name="stock" type="text" value="" placeholder="Input Stock..">
                                </div> --}}
                                <div class="col-6 mb-2">
                                    <label class="form-label">Status</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="status" required>
                                        <option value="" selected>--Select Status--</option>
                                        <option value="Active">Active</option>
                                        <option value="Not Active">Not Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card p-3">
                                <div class="text-center">
                                    <label class="form-label">Size</label>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Thickness</label><label style="color: darkred">*</label>
                                        <div class="input-group">
                                            <input class="form-control" name="thickness" type="text" value="" placeholder="Input Thickness.." required>
                                            <div class="input-group-text" style="background-color:rgb(197, 197, 197)">Mic</div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-2">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Width</label><label style="color: darkred">*</label>
                                        <input class="form-control" name="width" type="text" value="" placeholder="Input Width.." required>
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
                                        <input class="form-control" name="length" type="text" value="" placeholder="Input Length.." required>
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
                                        <input class="form-control" name="weight" type="text" value="" placeholder="Input Weight.." style="background-color:rgb(197, 197, 197)" readonly>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Code</label><label style="color: darkred">*</label>
                                        <input class="form-control" name="wip_code" type="text" value="" placeholder="Input Code.." required>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                                $(document).ready(function(){
                                    var unitToMeter = {
                                        "CM": 0.01,
                                        "INCH": 0.0254,
                                        "MM": 0.001,
                                        "M": 1
                                    };
                                    function calculateWeight() {
                                        var thickness = parseFloat($('[name="thickness"]').val()) || 0 / 1000;
                                        var width = (parseFloat($('[name="width"]').val()) || 0) * unitToMeter[$('[name="width_unit"] option:selected').text()];
                                        var length = (parseFloat($('[name="length"]').val()) || 0) * unitToMeter[$('[name="length_unit"] option:selected').text()];
                                        var id_master_group_subs = $('[name="id_master_group_subs"]').find(":selected").text();
                                        // var factor = (id_master_group_subs.includes("Slitting")) ? 1 : 2;
                                        var factor = 2;
                                        var weight = thickness * width * length * factor * 0.92;
                                        if (isNaN(weight)) {
                                            weight = 0;
                                        } else {
                                            weight = weight/1000;
                                            // weight = Math.ceil(weight * 100) / 100;
                                        }
                                        $('[name="weight"]').val(weight);
                                        // $('[name="weight"]').val(weight.toFixed(2));
                                    }
                                    $('[name="id_master_group_subs"], [name="thickness"], [name="width"], [name="length"], [name="width_unit"], [name="length_unit"]').change(function(){
                                        calculateWeight();
                                    });
                                    
                                    calculateWeight();

                                });
                            </script>

                            {{-- Additional Table --}}
                            @if($flag == 'wip')
                                @include('wip.wip')
                            @else
                                @include('wip.wipblow')
                            @endif
                            

                            <div class="row">
                                <div class="col-12 align-center">
                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb">
                                        <i class="mdi mdi-plus-box label-icon"></i>Add New WIP
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
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
@endsection