<div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu2" aria-labelledby="btnGroupDrop{{ $data->id }}">
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-information"></span> | Info</a></li>
        <li><a class="dropdown-item drpdwn" href="{{ route('customeraddress.index', encrypt($data->id)) }}"><span class="mdi mdi-menu"></span> | Address</a></li>
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#update{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Info Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4" style="max-height: 67vh; overflow-y: auto;">
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
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Customer Code :</span></div>
                                <span>
                                    <span>{{ $data->customer_code }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Customer Name :</span></div>
                                <span>
                                    <span>{{ $data->name }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Remarks :</span></div>
                                <span>
                                    <span>{{ $data->remark }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Tax Number :</span></div>
                                <span>
                                    <span>{{ $data->tax_number }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Tax Code :</span></div>
                                <span>
                                    <span>{{ $data->tax_code }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Salesman :</span></div>
                                <span>
                                    <span>{{ $data->salesmanname }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Currencies :</span></div>
                                <span>
                                    <span>{{ $data->currency }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Term Payments :</span></div>
                                <span>
                                    <span>{{ $data->term_payment }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Ppn :</span></div>
                                <span>
                                    <span>{{ $data->ppn }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">CBC :</span></div>
                                <span>
                                    @if($data->cbc == 'N')
                                        <span>No</span>
                                    @else
                                        <span>Yes</span>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Update Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.update', encrypt($data->id)) }}" id="formupdate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="form-label">Customer Code</label>
                                <br>
                                <span class="badge bg-info text-white">Auto Generate</span>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Status</label><label style="color: darkred">*</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="status" required>
                                    <option value="" selected>--Select Status--</option>
                                    <option value="Active" @if($data->status === "Active") selected="selected" @endif>Active</option>
                                    <option value="Not Active" @if($data->status === "Not Active") selected="selected" @endif>Not Active</option>
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Customer Name</label><label style="color: darkred">*</label>
                                <input class="form-control" name="name" type="text" value="{{ $data->name }}" placeholder="Input Customer Name.." required>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Remarks</label>
                                <input class="form-control" name="remark" type="text" value="{{ $data->remark }}" placeholder="Input Remarks..">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Tax Number</label>
                                <input class="form-control" name="tax_number" type="text" value="{{ $data->tax_number }}" placeholder="Input Tax Number..">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Tax Code</label>
                                <input class="form-control" name="tax_code" type="text" value="{{ $data->tax_code }}" placeholder="Input Tax Code..">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Salesman</label><label style="color: darkred">*</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_salesmen" required>
                                    <option value="" selected>--Select Salesman--</option>
                                    @foreach($allsalesmans as $sales)
                                        <option value="{{ $sales->id }}" @if($data->id_master_salesmen === $sales->id) selected="selected" @endif>{{ $sales->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Currency</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_currencies">
                                    <option value="" selected>--Select Currency--</option>
                                    @foreach($currencies as $cr)
                                        <option value="{{ $cr->id }}" @if($data->id_master_currencies === $cr->id) selected="selected" @endif>{{ $cr->currency }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Term Payment</label><label style="color: darkred">*</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_term_payments" required>
                                    <option value="" selected>--Select Term Payment--</option>
                                    @foreach($terms as $tr)
                                        <option value="{{ $tr->id }}" @if($data->id_master_term_payments === $tr->id) selected="selected" @endif>{{ $tr->term_payment }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Ppn</label><label style="color: darkred">*</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="ppn" required>
                                    <option value="" selected>--Select Ppn--</option>
                                    <option value="Include" @if($data->ppn === "Include") selected="selected" @endif>Include</option>
                                    <option value="Exclude" @if($data->ppn === "Exclude") selected="selected" @endif>Exclude</option>
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">CBC</label><label style="color: darkred">*</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="square-switch">
                                        <input type="checkbox" name="cbc" id="square-switch1{{ $data->id }}" switch="none" @if($data->cbc === "Y") checked="checked" @endif/>
                                        <label for="square-switch1{{ $data->id }}" data-on-label="Yes"
                                            data-off-label="No"></label>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-6 mb-2">
                                <label class="form-label">CBC</label><label style="color: darkred">*</label>
                                <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                    <input type="checkbox" name="cbc" class="form-check-input" id="customSwitchsizemd" @if($data->cbc === "Y") checked="checked" @endif>
                                </div>
                            </div> --}}

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light" id="sb-update{{ $data->id }}"><i class="mdi mdi-update label-icon"></i>Update</button>
                    </div>
                </form>
                <script>
                    $(document).ready(function() {
                        let idList = "{{ $data->id }}";
                        $('#formupdate' + idList).submit(function(e) {
                            if (!$('#formupdate' + idList).valid()){
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
                    <h5 class="modal-title" id="staticBackdropLabel">Activate Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.activate', encrypt($data->id)) }}" id="formactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Activate</b> This Customer?
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
                    <h5 class="modal-title" id="staticBackdropLabel">Deactivate Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customer.deactivate', encrypt($data->id)) }}" id="formdeactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Deactivate</b> This Customer?
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
                <form action="{{ route('customer.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <p class="text-center">Are You Sure To Delete This Data?</p>
                            <p class="text-center"><b>{{ $data->customer_code }}</b></p>
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