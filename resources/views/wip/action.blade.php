<div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu2" aria-labelledby="btnGroupDrop{{ $data->id }}">
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-information"></span> | Info</a></li>
        {{-- <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#update{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li> --}}
        <li><a class="dropdown-item drpdwn" href="{{ route('wip.edit', ['id' => encrypt($data->id), 'page' => $page]) }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
        @if($data->wip_type == 'WIP')
            <li><a class="dropdown-item drpdwn" href="{{ route('wiprefwip.index', encrypt($data->id)) }}"><span class="mdi mdi-menu"></span> | WIP Ref</a></li>
        @else
            <li><a class="dropdown-item drpdwn" href="{{ route('wipref.index', encrypt($data->id)) }}"><span class="mdi mdi-menu"></span> | WIP Ref Blow</a></li>
        @endif
        @if($data->status == 'Active')
            <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#deactivate{{ $data->id }}"><span class="mdi mdi-close-circle"></span> | Deactivate</a></li>
        @else
            <li><a class="dropdown-item drpdwn-scs" href="#" data-bs-toggle="modal" data-bs-target="#activate{{ $data->id }}"><span class="mdi mdi-check-circle"></span> | Activate</a></li>
        @endif
        <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | Delete</a></li>
    </ul>
</div>

{{-- Modal --}}
<div class="left-align truncate-text">
    {{-- Modal Info --}}
    <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Info Wip</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Status :</span></div>
                                <span>
                                    @if($data->status == 'Active')
                                        <span class="badge bg-success text-white">Active</span>
                                    @else
                                        <span class="badge bg-danger text-white">Inactive</span>
                                    @endif
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
                                <div><span class="fw-bold">Type Product Code :</span></div>
                                <span>
                                    <span>{{ $data->type_product_code ?? '-' }}</span>
                                </span>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Quantity :</span></div>
                                <span>
                                    <span>{{ $data->qty }}</span>
                                </span>
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Process Production :</span></div>
                                <span>
                                    <span>{{ $data->process }}</span>
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
                                <div><span class="fw-bold">Group :</span></div>
                                <span>
                                    <span>{{ $data->groupname }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Group Sub :</span></div>
                                <span>
                                    <span>{{ $data->groupsub }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Group Sub Code :</span></div>
                                <span>
                                    <span>{{ $data->group_sub_code ?? '-' }}</span>
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
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Stock :</span></div>
                                <span>
                                    <span>{{ $data->stock }}</span>
                                </span>
                            </div>
                        </div> --}}
                        
                        <div class="card p-3">
                            <div class="text-center">
                                <label class="form-label">Size</label>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Thickness :</span></div>
                                        <span>
                                            <span>{{ $data->thickness }} Mic</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Width :</span></div>
                                        <span>
                                            <span>{{ $data->width }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Width Unit :</span></div>
                                        <span>
                                            <span>{{ $data->width_unt }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Length :</span></div>
                                        <span>
                                            <span>{{ $data->length }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Length Unit :</span></div>
                                        <span>
                                            <span>{{ $data->length_unt }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Perforasi :</span></div>
                                        <span>
                                            <span>{{ $data->perforasi }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Weight :</span></div>
                                        <span>
                                            <span>{{ $data->weight }} Kg</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="form-group">
                                        <div><span class="fw-bold">Code :</span></div>
                                        <span>
                                            <span>{{ $data->wip_code }}</span>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Update --}}
    {{-- <div class="modal fade" id="update{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Wip</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('wip.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="form-label">Description</label><label style="color: darkred">*</label>
                                <input class="form-control" name="description" type="text" value="{{ $data->description }}" placeholder="Input Description.." required>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Type</label><label style="color: darkred">*</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="type" required>
                                    <option value="" selected>--Select Type--</option>
                                    <option value="PP" @if($data->type === "PP") selected="selected" @endif>PP</option>
                                    <option value="POF" @if($data->type === "POF") selected="selected" @endif>POF</option>
                                    <option value="CROSSLINK" @if($data->type === "CROSSLINK") selected="selected" @endif>CROSSLINK</option>
                                    <option value="PPNC" @if($data->type === "PPNC") selected="selected" @endif>PPNC</option>
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
                                <label class="form-label">Quantity</label>
                                <input class="form-control" name="qty" type="number" value="{{ $data->qty }}" placeholder="Input Quantity..">
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
                                        <option value="{{ $group->id }}" @if($data->id_master_groups === $group->id) selected="selected" @endif>{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Group Sub</label><label style="color: darkred">*</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_group_subs" required>
                                    <option value="" selected>--Select Unit--</option>
                                    @foreach($allgroup_subs as $gs)
                                        <option value="{{ $gs->id }}" @if($data->id_master_group_subs === $gs->id) selected="selected" @endif>{{ $gs->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Department</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_departements">
                                    <option value="" selected>--Select Department--</option>
                                    @foreach($alldepartments as $depart)
                                        <option value="{{ $depart->id }}" @if($data->id_master_departements === $depart->id) selected="selected" @endif>{{ $depart->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Stock</label>
                                <input class="form-control" name="stock" type="text" value="{{ $data->stock }}" placeholder="Input Stock..">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Status</label><label style="color: darkred">*</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="status" required>
                                    <option value="" selected>--Select Status--</option>
                                    <option value="Active" @if($data->status === "Active") selected="selected" @endif>Active</option>
                                    <option value="Not Active" @if($data->status === "Not Active") selected="selected" @endif>Not Active</option>
                                </select>
                            </div>
                            <div class="card p-3">
                                <div class="text-center">
                                    <label class="form-label">Size</label>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Thickness</label>
                                        <input class="form-control" name="thickness" type="text" value="{{ $data->thickness }}" placeholder="Input Thickness..">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Width</label>
                                        <input class="form-control" name="width" type="text" value="{{ $data->width }}" placeholder="Input Width..">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Width Unit</label>
                                        <input class="form-control" name="width_unit" type="text" value="{{ $data->width_unit }}" placeholder="Input Width Unit..">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Lenght</label>
                                        <input class="form-control" name="length" type="text" value="{{ $data->length }}" placeholder="Input Lenght..">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Lenght Unit</label>
                                        <input class="form-control" name="length_unit" type="text" value="{{ $data->length_unit }}" placeholder="Input Lenght Unit..">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Perforasi</label>
                                        <input class="form-control" name="perforasi" type="text" value="{{ $data->perforasi }}" placeholder="Input Perforasi..">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Weight</label>
                                        <input class="form-control" name="weight" type="text" value="{{ $data->weight }}" placeholder="Input Weight..">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label">Code</label><label style="color: darkred">*</label>
                                        <input class="form-control" name="wip_code" type="text" value="{{ $data->wip_code }}" placeholder="Input Code.." required>
                                    </div>
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
    </div> --}}

    {{-- Modal Activate --}}
    <div class="modal fade" id="activate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Activate Wip</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('wip.activate', encrypt($data->id)) }}" id="formactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Activate</b> This Wip?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light" id="sb-activate{{ $data->id }}"><i class="mdi mdi-check-circle label-icon"></i>Activate</button>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Deactivate Wip</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('wip.deactivate', encrypt($data->id)) }}" id="formdeactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Deactivate</b> This Wip?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-deactivate{{ $data->id }}"><i class="mdi mdi-close-circle label-icon"></i>Deactivate</button>
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

    {{-- Modal Delete --}}
    <div class="modal fade" id="delete{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('wip.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <p class="text-center">Are You Sure To Delete This Data?</p>
                            <p class="text-center"><b>{{ $data->description }}</b></p>
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
</div>