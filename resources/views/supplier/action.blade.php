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
                                <div><span class="fw-bold">Supplier Code :</span></div>
                                <span>
                                    <span>{{ $data->supplier_code }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Supplier Name :</span></div>
                                <span>
                                    <span>{{ $data->name }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Invoice Name :</span></div>
                                <span>
                                    <span>{{ $data->name_invoice }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Domestic :</span></div>
                                <span>
                                    @if($data->is_domestic == '1')
                                        <span>Yes</span>
                                    @else
                                        <span>No</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Address :</span></div>
                                <span>
                                    <span>{{ $data->address }}, {{ $data->city }}, {{ $data->province }}, {{ $data->country }}, {{ $data->postal_code }}</span>
                                </span>
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
                                    <span>{{ $data->email }}</span>
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
                                <div><span class="fw-bold">Remarks :</span></div>
                                <span>
                                    <span>{{ $data->remarks }}</span>
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
                                <div><span class="fw-bold">Bank Name :</span></div>
                                <span>
                                    <span>{{ $data->bank_name }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Bank Account Number :</span></div>
                                <span>
                                    <span>{{ $data->bank_account_number }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Account Holder :</span></div>
                                <span>
                                    <span>{{ $data->account_holder }}</span>
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
                <form action="{{ route('supplier.update', encrypt($data->id)) }}" id="formupdate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="form-label">Supplier Code</label>
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
                                <label class="form-label">Supplier Name</label><label style="color: darkred">*</label>
                                <input class="form-control" name="name" type="text" value="{{ $data->name }}" placeholder="Input Supplier Name.." required>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Invoice Name</label><label style="color: darkred">*</label>
                                <input class="form-control" name="name_invoice" type="text" value="{{ $data->name_invoice }}" placeholder="Input Invoice Name.." required>
                            </div>
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
                                <label class="form-label">Domestic</label><label style="color: darkred">*</label>
                                <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                    <input type="checkbox" name="is_domestic" class="form-check-input" id="customSwitchsizemd" @if($data->is_domestic === 1) checked="checked" @endif>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Remarks</label>
                                <input class="form-control" name="remarks" type="text" value="{{ $data->remarks }}" placeholder="Input Remarks..">
                            </div>
                            <hr>
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
                            <hr>
                            <div class="col-6 mb-2">
                                <label class="form-label">Tax Number</label>
                                <input class="form-control" name="tax_number" type="text" value="{{ $data->tax_number }}" placeholder="Input Tax Number..">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Currency</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_currencies">
                                    <option value="" selected>--Select Currency--</option>
                                    @foreach($allcurrencies as $currency)
                                        <option value="{{ $currency->id }}" @if($data->id_master_currencies === $currency->id) selected="selected" @endif>{{ $currency->currency }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Term Payment</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_term_payments">
                                    <option value="" selected>--Select Term Payment--</option>
                                    @foreach($allterms as $tr)
                                        <option value="{{ $tr->id }}" @if($data->id_master_term_payments === $tr->id) selected="selected" @endif>{{ $tr->term_payment }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Bank Name</label><label style="color: darkred">*</label>
                                <input class="form-control" name="bank_name" type="text" value="{{ $data->bank_name }}" placeholder="Input Bank Name.." required>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Bank Account Number</label><label style="color: darkred">*</label>
                                <input class="form-control" name="bank_account_number" type="text" value="{{ $data->bank_account_number }}" placeholder="Input Account Number.." required>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Account Holder</label>
                                <input class="form-control" name="account_holder" type="text" value="{{ $data->account_holder }}" placeholder="Input Account Holder..">
                            </div>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Activate Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('supplier.activate', encrypt($data->id)) }}" id="formactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Activate</b> This Supplier?
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
                    <h5 class="modal-title" id="staticBackdropLabel">Deactivate Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('supplier.deactivate', encrypt($data->id)) }}" id="formdeactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center">
                            Are You Sure to <b>Deactivate</b> This Supplier?
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
                <form action="{{ route('supplier.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <p class="text-center">Are You Sure To Delete This Data?</p>
                            <p class="text-center"><b>{{ $data->supplier_code }}</b></p>
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