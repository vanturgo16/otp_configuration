<div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu2" aria-labelledby="btnGroupDrop{{ $data->id }}">
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-information"></span> | Info</a></li>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Info Bagian</h5>
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
                        <div class="col-lg-12">
                            <div class="card py-2 px-2" style="background-color:rgb(236, 236, 236)">
                                <div class="form-group">
                                    <div><span class="fw-bold">Address :</span></div>
                                    <span>
                                        <span>{{ $data->address.', '.$data->postal_code.', '.$data->city.', '.$data->province.', '.$data->country }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Telephone :</span></div>
                                <span>
                                    <span>{{ $data->telephone }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Mobile Phone :</span></div>
                                <span>
                                    <span>{{ $data->mobile_phone }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Fax :</span></div>
                                <span>
                                    <span>{{ $data->fax }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Email :</span></div>
                                <span>
                                    <span>{{ $data->email }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Remark :</span></div>
                                <span>
                                    <span>{{ $data->remarks }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Contact Person :</span></div>
                                <span>
                                    <span>{{ $data->contact_person }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Type Address :</span></div>
                                <span>
                                    <span>{{ $data->type_address }}</span>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Bagian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customeraddress.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label class="form-label">Address</label><label style="color: darkred">*</label>
                                <textarea class="form-control" name="address" rows="3" placeholder="Input Address.." required>{{ $data->address }}</textarea>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Postal Code</label><label style="color: darkred">*</label>
                                <input class="form-control" name="postal_code" type="text" value="{{ $data->postal_code }}" placeholder="Input Postal Code.." required>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">City</label><label style="color: darkred">*</label>
                                <input class="form-control" name="city" type="text" value="{{ $data->city }}" placeholder="Input City.." required>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Province</label><label style="color: darkred">*</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_provinces" required>
                                    <option value="" selected>--Select Province--</option>
                                    @foreach($allprovinces as $province)
                                        <option value="{{ $province->id }}" @if($data->id_master_provinces === $province->id) selected="selected" @endif>{{ $province->province }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Country</label><label style="color: darkred">*</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_countries" required>
                                    <option value="" selected>--Select Country--</option>
                                    @foreach($allcountries as $country)
                                        <option value="{{ $country->id }}" @if($data->id_master_countries === $country->id) selected="selected" @endif>{{ $country->country }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Telephone</label>
                                <input class="form-control" name="telephone" type="text" value="{{ $data->telephone }}" placeholder="Input Telephone..">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Mobile Phone</label>
                                <input class="form-control" name="mobile_phone" type="text" value="{{ $data->mobile_phone }}" placeholder="Input Mobile Phone..">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Fax</label>
                                <input class="form-control" name="fax" type="text" value="{{ $data->fax }}" placeholder="Input Fax..">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Email</label>
                                <input class="form-control" name="email" type="email" value="{{ $data->email }}" placeholder="Input Email..">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Remark</label>
                                <input class="form-control" name="remarks" type="text" value="{{ $data->remarks }}" placeholder="Input Remark..">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Contact Person</label>
                                <input class="form-control" name="contact_person" type="text" value="{{ $data->contact_person }}" placeholder="Input Contact Person..">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Type Address</label><label style="color: darkred">*</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="type_address" required>
                                    <option value="" selected>--Select Type Address--</option>
                                    <option value="Invoice" @if($data->type_address === "Invoice") selected="selected" @endif>Invoice</option>
                                    <option value="Shipping" @if($data->type_address === "Shipping") selected="selected" @endif>Shipping</option>
                                    <option value="Same As (Invoice, Shipping)" @if($data->type_address === "Same As (Invoice, Shipping)") selected="selected" @endif>Same As (Invoice, Shipping)</option>
                                </select>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Activate Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customeraddress.activate', encrypt($data->id)) }}" id="formactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Activate</b> This Address?
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
                    <h5 class="modal-title" id="staticBackdropLabel">Deactivate Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('customeraddress.deactivate', encrypt($data->id)) }}" id="formdeactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Deactivate</b> This Address?
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
                <form action="{{ route('customeraddress.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <p class="text-center">Are You Sure To Delete This Data?</p>
                            <p class="text-center"><b>{{ $data->address }}</b></p>
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