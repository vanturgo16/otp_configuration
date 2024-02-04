@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List FG Refs of <b class="text-primary">"{{ $fg->description}}"</b></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fg.index') }}">Master FG</a></li>
                            <li class="breadcrumb-item active">FG Ref</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <table class="table table-bordered dt-responsive nowrap w-100">
                    <tbody>
                        <tr>
                            <td class="align-middle"><b>Product Code</b></td>
                            <td class="align-middle">: {{ $fg->product_code }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Description</b></td>
                            <td class="align-middle">: {{ $fg->description}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New FG Ref</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New FG Ref</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('fgref.store', encrypt($id)) }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Type Ref</label><label style="color: darkred">*</label>
                                                    <select class="form-select" name="type_ref" required>
                                                        <option value="" selected>--Select Type Ref--</option>
                                                        <option value="WIP">WIP</option>
                                                        <option value="FG">FG</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2" id="wipform"><label style="color: darkred">*</label>
                                                    <label class="form-label">WIP</label>
                                                    <select class="form-control js-example-basic-single" name="id_master_wips" style="width: 100%">
                                                        <option value="" selected>--Select WIP--</option>
                                                        @foreach($listwip as $item)
                                                            <option value="{{ $item->id }}">{{ $item->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2" id="fgform"><label style="color: darkred">*</label>
                                                    <label class="form-label">FG</label>
                                                    <select class="form-control js-example-basic-single" name="id_master_fgs" style="width: 100%">
                                                        <option value="" selected>--Select FG--</option>
                                                        @foreach($listfg as $item)
                                                            <option value="{{ $item->id }}">{{ $item->description }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <script>
                                                    $('#fgform').hide();
                                                    $('select[name="type_ref"]').on('change', function() {
                                                        var type_ref = $(this).val();
                                                        if(type_ref == 'WIP'){
                                                            $('#fgform').hide();
                                                            $('#wipform').show();
                                                            $('select[name="id_master_fgs"]').val("");
                                                            $('select[name="id_master_fgs"]').prop('required', false);
                                                            $('select[name="id_master_wips"]').prop('required', true);
                                                        } else {
                                                            $('#fgform').show();
                                                            $('#wipform').hide();
                                                            $('select[name="id_master_wips"]').val("");
                                                            $('select[name="id_master_fgs"]').prop('required', true);
                                                            $('select[name="id_master_wips"]').prop('required', false);
                                                        }
                                                    });
                                                </script>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Qty</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="qty" type="text" value="" placeholder="Input Qty.." required>
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Unit</label><label style="color: darkred">*</label>
                                                    <select class="form-control js-example-basic-single" name="master_units_id" style="width: 100%">
                                                        <option value="" selected>--Select Unit--</option>
                                                        @foreach($listunit as $item)
                                                            <option value="{{ $item->id }}">{{ $item->unit }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Qty Result</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="qty_results" type="text" value="" placeholder="Input Qty Result.." required>
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
                                    <form action="{{ route('fgref.index', encrypt($id)) }}" id="formfilter" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Type Ref</label>
                                                    <select class="form-select" name="type_ref">
                                                        <option value="" selected>--All--</option>
                                                        <option value="WIP" @if($type_ref == 'WIP') selected @endif>WIP</option>
                                                        <option value="FG" @if($type_ref == 'FG') selected @endif>FG</option>
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
                    <div class="card-body">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Type Ref</th>
                                    <th class="align-middle text-center">WIP</th>
                                    <th class="align-middle text-center">FG</th>
                                    <th class="align-middle text-center">Qty</th>
                                    <th class="align-middle text-center">Units</th>
                                    <th class="align-middle text-center">Qty Result</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0;?> 
                                @foreach ($datas as $data)
                                <?php $no++ ;?>
                                    <tr>
                                        <td class="align-middle text-center">{{ $no }}</td>
                                        <td class="align-middle text-center">{{ $data->type_ref }}</td>
                                        @if($data->wp_description == null)
                                            <td class="align-middle text-center">
                                                <span class="badge bg-secondary text-white">Null</span>
                                            </td>
                                        @else
                                            <td class="align-middle">
                                                {{ $data->wp_description }}
                                            </td>
                                        @endif
                                        @if($data->fg_description == null)
                                            <td class="align-middle text-center">
                                                <span class="badge bg-secondary text-white">Null</span>
                                            </td>
                                        @else
                                            <td class="align-middle">
                                                {{ $data->fg_description }}
                                            </td>
                                        @endif
                                        <td class="align-middle text-center">{{ $data->qty }}</td>
                                        <td class="align-middle text-center">{{ $data->unit }}</td>
                                        <td class="align-middle text-center">{{ $data->qty_results }}</td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    Action <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu2" aria-labelledby="btnGroupDrop{{ $data->id }}">
                                                    <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-information"></span> | Info</a></li>
                                                    <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#update{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
                                                    <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>

                                        {{-- Modal Info --}}
                                        <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Info</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-6 mb-2">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Type Ref :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->type_ref }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @if($data->wp_description != null)
                                                            <div class="col-lg-6 mb-2">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">WP :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->wp_description }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            @if($data->fg_description != null)
                                                            <div class="col-lg-6 mb-2">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">FG :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->fg_description }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @endif
                                                            <div class="col-lg-6 mb-2">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Qty :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->qty }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 mb-2">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Unit :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->unit }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 mb-2">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Qty Result :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->qty_results }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Created At :</span></div>
                                                                    <span>
                                                                        @if($data->created_at == null)
                                                                            <span class="badge bg-secondary text-white">Null</span>
                                                                        @else
                                                                            <span>{{ $data->created_at }}</span>
                                                                        @endif
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
                                            <div class="modal-dialog modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('fgref.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Type Ref</label><label style="color: darkred">*</label>
                                                                    <select class="form-select" id="type_ref{{ $data->id }}" name="type_ref" required>
                                                                        <option value="" selected>--Select Type Ref--</option>
                                                                        <option value="WIP" @if($data->type_ref == 'WIP') selected="selected" @endif>WIP</option>
                                                                        <option value="FG" @if($data->type_ref == 'FG') selected="selected" @endif>FG</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2" id="wipform{{ $data->id }}"><label style="color: darkred">*</label>
                                                                    <label class="form-label">WIP</label>
                                                                    <select class="form-control js-example-basic-single" name="id_master_wips" id="id_master_wips{{ $data->id }}" style="width: 100%">
                                                                        <option value="" selected>--Select WIP--</option>
                                                                        @foreach($listwip as $item)
                                                                            <option value="{{ $item->id }}" @if($data->id_master_wips == $item->id) selected="selected" @endif>{{ $item->description }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2" id="fgform{{ $data->id }}"><label style="color: darkred">*</label>
                                                                    <label class="form-label">FG</label>
                                                                    <select class="form-control js-example-basic-single" name="id_master_fgs" id="id_master_fgs{{ $data->id }}" style="width: 100%">
                                                                        <option value="" selected>--Select FG--</option>
                                                                        @foreach($listfg as $item)
                                                                            <option value="{{ $item->id }}" @if($data->id_master_fgs == $item->id) selected="selected" @endif>{{ $item->description }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <script>
                                                                    let idList = "{{ $data->id }}";
                                                                    $('#fgform' + idList).hide();
                                                                    $('#type_ref' + idList).on('change', function() {
                                                                        var type_ref = $(this).val();
                                                                        if(type_ref == 'WIP'){
                                                                            $('#fgform' + idList).hide();
                                                                            $('#wipform' + idList).show();
                                                                            $('#id_master_fgs' + idList).val("");
                                                                            $('#id_master_fgs' + idList).prop('required', false);
                                                                            $('#id_master_wips' + idList).prop('required', true);
                                                                        } else {
                                                                            $('#fgform' + idList).show();
                                                                            $('#wipform' + idList).hide();
                                                                            $('#id_master_wips' + idList).val("");
                                                                            $('#id_master_fgs' + idList).prop('required', true);
                                                                            $('#id_master_wips' + idList).prop('required', false);
                                                                        }
                                                                    });
                                                                </script>
                
                                                                <div class="col-lg-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Qty</label><label style="color: darkred">*</label>
                                                                        <input class="form-control" name="qty" type="text" value="{{ $data->qty }}" placeholder="Input Qty.." required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Unit</label><label style="color: darkred">*</label>
                                                                    <select class="form-control js-example-basic-single" name="master_units_id" style="width: 100%">
                                                                        <option value="" selected>--Select Unit--</option>
                                                                        @foreach($listunit as $item)
                                                                            <option value="{{ $item->id }}" @if($data->master_units_id == $item->id) selected="selected" @endif>{{ $item->unit }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Qty Result</label><label style="color: darkred">*</label>
                                                                        <input class="form-control" name="qty_results" type="text" value="{{ $data->qty_results }}" placeholder="Input Qty Result.." required>
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

                                        {{-- Modal Delete --}}
                                        <div class="modal fade" id="delete{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('fgref.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <p class="text-center">Are You Sure To Delete This FG Ref?</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-delete{{ $data->id }}"><i class="mdi mdi-delete label-icon"></i>Delete</button>
                                                        </div>
                                                    </form>
                                                    <script>
                                                        $(document).ready(function() {
                                                            let userId = "{{ $data->id }}";
                                                            $('#formdelete' + userId).submit(function(e) {
                                                                if (!$('#formdelete' + userId).valid()){
                                                                    e.preventDefault();
                                                                } else {
                                                                    $('#sb-delete' + userId).attr("disabled", "disabled");
                                                                    $('#sb-delete' + userId).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
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
                            'type_ref' => $type_ref,
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
                    type_ref: {!! json_encode($type_ref) !!},
                    searchDate: {!! json_encode($searchDate) !!},
                    startdate: {!! json_encode($startdate) !!},
                    enddate: {!! json_encode($enddate) !!},
                    flag: 1,
                };

                var currentDate = new Date();
                var formattedDate = currentDate.toISOString().split('T')[0];
                var fileName = "Master Product FG Ref - " + formattedDate + ".xlsx";

                exportToExcel("{{ route('fgref.index', encrypt($id)) }}", fileName, requestData);
            });
        </script>
    </div>
</div>
@endsection