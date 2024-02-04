@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Sparepart</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Sparepart</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('sparepart.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Code</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="code" type="text" value="" placeholder="Input Code.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Description</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="description" type="text" value="" placeholder="Input Description.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Type</label><label style="color: darkred">*</label>
                                                    <select class="form-select" name="type" required>
                                                        <option value="" selected>--Select Type--</option>
                                                        <option value="Auxiliaries">Auxiliaries</option>
                                                        <option value="Sparepart">Sparepart</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Stock</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="stock" type="number" value="" placeholder="Input Stock.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Units</label><label style="color: darkred">*</label>
                                                    <select class="form-select" name="id_master_units" required>
                                                        <option value="" selected>--Select Unit--</option>
                                                        @foreach($units as $unit)
                                                            <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Department</label><label style="color: darkred">*</label>
                                                    <select class="form-select" name="id_master_departements" required>
                                                        <option value="" selected>--Select Department--</option>
                                                        @foreach($departments as $depart)
                                                            <option value="{{ $depart->id }}">{{ $depart->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">In Stock</label><label style="color: darkred">*</label>
                                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                        <input type="checkbox" name="status_stock" class="form-check-input" id="customSwitchsizemd" checked>
                                                    </div>
                                                </div>
                                            </div>
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
                        <button type="button" class="btn btn-info waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#sort"><i class="mdi mdi-filter label-icon"></i> Search & Filter</button>
                        {{-- Modal Search --}}
                        <div class="modal fade" id="sort" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel"><i class="mdi mdi-filter label-icon"></i> Search & Filter</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('sparepart.index') }}" id="formfilter" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Code</label>
                                                    <input class="form-control" name="code" type="text" value="{{ $code }}" placeholder="Filter Code..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Description</label>
                                                    <input class="form-control" name="description" type="text" value="{{ $description }}" placeholder="Filter Description..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">In Stock</label>
                                                    <input type="hidden" name="status_stock" value="off">
                                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                        <input type="checkbox" name="status_stock" class="form-check-input" id="customSwitchsizemd" @if($status_stock == null || $status_stock == 'on' || $status_stock == 'Y') checked @endif>
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Type</label>
                                                    <select class="form-select" name="type">
                                                        <option value="" selected>--All--</option>
                                                        <option value="Auxiliaries" @if($type == 'Auxiliaries') selected @endif>Auxiliaries</option>
                                                        <option value="Sparepart" @if($type == 'Sparepart') selected @endif>Sparepart</option>
                                                        <option value="Other" @if($type == 'Other') selected @endif>Other</option>
                                                    </select>
                                                </div>
                                                <hr class="mt-2">
                                                <div class="col-4 mb-2">
                                                    <label class="form-label">Filter Date</label>
                                                    <select class="form-select" name="searchDate">
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
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Sparepart</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <h5 class="mb-0"><b>Master Sparepart & Auxiliaries</b></h5>
                        List of 
                        @if($code != null)
                            (Code<b> - {{ $code }}</b>)
                        @endif
                        @if($description != null)
                            (Description<b> - {{ $description }}</b>)
                        @endif
                        @if($status_stock != null)
                            (Status<b> - {{ $status_stock }}</b>)
                        @endif
                        @if($type != null)
                            (Type<b> - {{ $type }}</b>)
                        @endif
                        @if($searchDate == 'Custom')
                            (Date From<b> {{ $startdate }} </b>Until <b>{{ $enddate }}</b>)
                        @else
                            (<b>All Date</b>)
                        @endif 
                    </div>
                    <div class="card-body">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Description</th>
                                    <th class="align-middle text-center">Type</th>
                                    <th class="align-middle text-center">Status Stock</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td class="align-middle text-center">{{ $data->no }}</td>
                                        <td class="align-middle">
                                            <b>{{ $data->code }}</b>
                                            <br>
                                            {{ $data->description }}
                                        </td>
                                        <td class="align-middle text-center"><b>{{ $data->type }}</b></td>
                                        <td class="align-middle text-center">
                                            @if($data->status_stock == 'Y')
                                                <span class="badge bg-success text-white">Yes</span>
                                            @else
                                                <span class="badge bg-danger text-white">No</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Action <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu2" aria-labelledby="btnGroupDrop{{ $data->id }}">
                                                    <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-information"></span> | Info</a></li>
                                                    <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#update{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
                                                    @if($data->status_stock == 'Y')
                                                        <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#deactivate{{ $data->id }}"><span class="mdi mdi-close-circle"></span> | Out Stock</a></li>
                                                    @else
                                                        <li><a class="dropdown-item drpdwn-scs" href="#" data-bs-toggle="modal" data-bs-target="#activate{{ $data->id }}"><span class="mdi mdi-check-circle"></span> | In Stock</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>

                                        {{-- Modal Info --}}
                                        <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Info Sparepart</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 mb-2">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">In Stock :</span></div>
                                                                    <span>
                                                                        @if($data->status_stock == 'Y')
                                                                            <span class="badge bg-success text-white">Yes</span>
                                                                        @else
                                                                            <span class="badge bg-danger text-white">No</span>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 mb-2">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Code :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->code }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Description :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->description }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Type :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->type }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Stock :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->stock }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Unit :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->unit }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Department :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->department }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Created At :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->created_at }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Update --}}
                                        <div class="modal fade" id="update{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Edit Sparepart</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('sparepart.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Code</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="code" type="text" value="{{ $data->code }}" placeholder="Input Code.." required>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Description</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="description" type="text" value="{{ $data->description }}" placeholder="Input Description.." required>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Type</label><label style="color: darkred">*</label>
                                                                    <select class="form-select" name="type" required>
                                                                        <option value="" selected>--Select Type--</option>
                                                                        <option value="Auxiliaries" @if($data->type === "Auxiliaries") selected="selected" @endif>Auxiliaries</option>
                                                                        <option value="Sparepart" @if($data->type === "Sparepart") selected="selected" @endif>Sparepart</option>
                                                                        <option value="Other" @if($data->type === "Other") selected="selected" @endif>Other</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Stock</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="stock" type="number" value="{{ $data->stock }}" placeholder="Input Stock.." required>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Units</label><label style="color: darkred">*</label>
                                                                    <select class="form-select" name="id_master_units" required>
                                                                        <option value="" selected>--Select Unit--</option>
                                                                        @foreach($allunits as $unit)
                                                                            <option value="{{ $unit->id }}" @if($data->id_master_units === $unit->id) selected="selected" @endif>{{ $unit->unit }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Department</label><label style="color: darkred">*</label>
                                                                    <select class="form-select" name="id_master_departements" required>
                                                                        <option value="" selected>--Select Department--</option>
                                                                        @foreach($alldepartments as $depart)
                                                                            <option value="{{ $depart->id }}" @if($data->id_master_departements === $depart->id) selected="selected" @endif>{{ $depart->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">In Stock</label><label style="color: darkred">*</label>
                                                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                                        <input type="checkbox" name="status_stock" class="form-check-input" id="customSwitchsizemd" @if($data->status_stock === "Y") checked="checked" @endif>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary waves-effect btn-label waves-light" id="sb-update{{ $data->id }}"><i class="mdi mdi-update label-icon"></i>Update</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        $(document).ready(function() {
                                                            let idList = "{{ $data->id }}";
                                                            $('#formedit' + idList).submit(function(e) {
                                                                if (!$('#formedit' + idList).valid()){
                                                                    e.preventDefault();
                                                                } else {
                                                                    $('#sb-update' + idList).attr("disabled", "disabled");
                                                                    $('#sb-update' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Activate --}}
                                        <div class="modal fade" id="activate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">In Stock Sparepart</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('sparepart.activate', encrypt($data->id)) }}" id="formactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                Are You Sure to Change <b>In Stock</b> This Sparepart?
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light" id="sb-activate{{ $data->id }}"><i class="mdi mdi-check-circle label-icon"></i>In Stock</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        $(document).ready(function() {
                                                            let idList = "{{ $data->id }}";
                                                            $('#formactivate' + idList).submit(function(e) {
                                                                if (!$('#formactivate' + idList).valid()){
                                                                    e.preventDefault();
                                                                } else {
                                                                    $('#sb-activate' + idList).attr("disabled", "disabled");
                                                                    $('#sb-activate' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Modal Deactivate --}}
                                        <div class="modal fade" id="deactivate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Out Stock Sparepart</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('sparepart.deactivate', encrypt($data->id)) }}" id="formdeactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                Are You Sure to <b>Out Stock</b> This Sparepart?
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-deactivate{{ $data->id }}"><i class="mdi mdi-close-circle label-icon"></i>Out Stock</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        $(document).ready(function() {
                                                            let idList = "{{ $data->id }}";
                                                            $('#formdeactivate' + idList).submit(function(e) {
                                                                if (!$('#formdeactivate' + idList).valid()){
                                                                    e.preventDefault();
                                                                } else {
                                                                    $('#sb-deactivate' + idList).attr("disabled", "disabled");
                                                                    $('#sb-deactivate' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                                                }
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $datas->appends([
                            'code' => $code,
                            'description' => $description,
                            'status_stock' => $status_stock,
                            'type' => $type,
                            'startdate' => $startdate,
                            'enddate' => $enddate])
                            ->links('vendor.pagination.bootstrap-5')
                        }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Export Action --}}
        <script>
            $(document).ready(function () {
                var requestData = {
                    code: {!! json_encode($code) !!},
                    description: {!! json_encode($description) !!},
                    status_stock: {!! json_encode($status_stock) !!},
                    type: {!! json_encode($type) !!},
                    searchDate: {!! json_encode($searchDate) !!},
                    startdate: {!! json_encode($startdate) !!},
                    enddate: {!! json_encode($enddate) !!},
                    flag: 1,
                };

                var currentDate = new Date();
                var formattedDate = currentDate.toISOString().split('T')[0];
                var fileName = "Master Sparepart & Aux Export - " + formattedDate + ".xlsx";

                exportToExcel("{{ route('sparepart.index') }}", fileName, requestData);
            });
        </script>
    </div>
</div>

@endsection