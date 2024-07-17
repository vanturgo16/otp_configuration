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

{{-- Modal --}}
<div class="left-align truncate-text">
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
                                <select class="form-select js-example-basic-single" style="width: 100%" id="type_ref{{ $data->id }}" name="type_ref" required>
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
</div>