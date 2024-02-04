@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Supplier</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Supplier</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('supplier.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
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
                                                    <select class="form-select" name="status" required>
                                                        <option value="" selected>--Select Status--</option>
                                                        <option value="Active">Active</option>
                                                        <option value="0">Not Active</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Supplier Name</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="name" type="text" value="" placeholder="Input Supplier Name.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Invoice Name</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="name_invoice" type="text" value="" placeholder="Input Invoice Name.." required>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label class="form-label">Address</label><label style="color: darkred">*</label>
                                                    <textarea class="form-control" name="address" rows="3" placeholder="Input Address.." required></textarea>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Postal Code</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="postal_code" type="text" value="" placeholder="Input Postal Code.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">City</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="city" type="text" value="" placeholder="Input City.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Province</label><label style="color: darkred">*</label>
                                                    <select class="form-select" name="id_master_provinces" required>
                                                        <option value="" selected>--Select Province--</option>
                                                        @foreach($provinces as $province)
                                                            <option value="{{ $province->id }}">{{ $province->province }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Country</label><label style="color: darkred">*</label>
                                                    <select class="form-select" name="id_master_countries" required>
                                                        <option value="" selected>--Select Country--</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->id }}">{{ $country->country }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Domestic</label><label style="color: darkred">*</label>
                                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                        <input type="checkbox" name="is_domestic" class="form-check-input" id="customSwitchsizemd" checked>
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Remarks</label>
                                                    <input class="form-control" name="remarks" type="text" value="" placeholder="Input Remarks..">
                                                </div>
                                                <hr>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Telephone</label>
                                                    <input class="form-control" name="telephone" type="text" value="" placeholder="Input Telephone..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Mobile Phone</label>
                                                    <input class="form-control" name="mobile_phone" type="text" value="" placeholder="Input Mobile Phone..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Fax</label>
                                                    <input class="form-control" name="fax" type="text" value="" placeholder="Input Fax..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Email</label>
                                                    <input class="form-control" name="email" type="email" value="" placeholder="Input Email..">
                                                </div>
                                                <hr>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Tax Number</label>
                                                    <input class="form-control" name="tax_number" type="text" value="" placeholder="Input Tax Number..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Currency</label>
                                                    <select class="form-select" name="id_master_currencies">
                                                        <option value="" selected>--Select Currency--</option>
                                                        @foreach($currencies as $currency)
                                                            <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Term Payment</label>
                                                    <select class="form-select" name="id_master_term_payments">
                                                        <option value="" selected>--Select Term Payment--</option>
                                                        @foreach($terms as $tr)
                                                            <option value="{{ $tr->id }}">{{ $tr->term_payment }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Bank Name</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="bank_name" type="text" value="" placeholder="Input Bank Name.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Bank Account Number</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="bank_account_number" type="text" value="" placeholder="Input Account Number.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Account Holder</label>
                                                    <input class="form-control" name="account_holder" type="text" value="" placeholder="Input Account Holder..">
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
                                    <form action="{{ route('supplier.index') }}" id="formfilter" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Supplier Code</label>
                                                    <input class="form-control" name="supplier_code" type="text" value="{{ $supplier_code }}" placeholder="Input Supplier Code..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="" selected>--All--</option>
                                                        <option value="Active" @if($status == 'Active') selected @endif>Active</option>
                                                        <option value="0" @if($status == '0') selected @endif>Not Active</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Supplier Name</label>
                                                    <input class="form-control" name="name" type="text" value="{{ $name }}" placeholder="Input Supplier Name..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Invoice Name</label>
                                                    <input class="form-control" name="name_invoice" type="text" value="{{ $name_invoice }}" placeholder="Input Invoice Name..">
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label class="form-label">Address</label>
                                                    <textarea class="form-control" name="address" rows="3" placeholder="Input Address..">{{ $address }}</textarea>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Postal Code</label>
                                                    <input class="form-control" name="postal_code" type="text" value="{{ $postal_code }}" placeholder="Input Postal Code..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">City</label>
                                                    <input class="form-control" name="city" type="text" value="{{ $city }}" placeholder="Input City..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Email</label>
                                                    <input class="form-control" name="email" type="text" value="{{ $email }}" placeholder="Input Email..">
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
                            <li class="breadcrumb-item active">Supplier</li>
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
                        <h5 class="mb-0"><b>Master Supplier</b></h5>
                        List of 
                        @if($supplier_code != null)
                            (Code<b> - {{ $supplier_code }}</b>)
                        @endif
                        @if($name != null)
                            (Name<b> - {{ $name }}</b>)
                        @endif
                        @if($name_invoice != null)
                            (Invoice<b> - {{ $name_invoice }}</b>)
                        @endif
                        @if($status != null)
                            (Status<b> - {{ $status }}</b>)
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
                                    <th class="align-middle text-center">Supplier Name</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td class="align-middle text-center">{{ $data->no }}</td>
                                        <td class="align-middle">
                                            <b>{{ $data->supplier_code }}</b>
                                            <br>
                                            {{ $data->name }}
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($data->status == 'Active')
                                                <span class="badge bg-success text-white">Active</span>
                                            @else
                                                <span class="badge bg-danger text-white">Inactive</span>
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
                                                    @if($data->status == 'Active')
                                                        <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#deactivate{{ $data->id }}"><span class="mdi mdi-close-circle"></span> | Deactivate</a></li>
                                                    @else
                                                        <li><a class="dropdown-item drpdwn-scs" href="#" data-bs-toggle="modal" data-bs-target="#activate{{ $data->id }}"><span class="mdi mdi-check-circle"></span> | Activate</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>

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
                                                                    <select class="form-select" name="status" required>
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
                                                                    <select class="form-select" name="id_master_provinces" required>
                                                                        <option value="" selected>--Select Province--</option>
                                                                        @foreach($allprovinces as $province)
                                                                            <option value="{{ $province->id }}" @if($data->id_master_provinces === $province->id) selected="selected" @endif>{{ $province->province }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Country</label><label style="color: darkred">*</label>
                                                                    <select class="form-select" name="id_master_countries" required>
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
                                                                    <select class="form-select" name="id_master_currencies">
                                                                        <option value="" selected>--Select Currency--</option>
                                                                        @foreach($allcurrencies as $currency)
                                                                            <option value="{{ $currency->id }}" @if($data->id_master_currencies === $currency->id) selected="selected" @endif>{{ $currency->currency }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Term Payment</label>
                                                                    <select class="form-select" name="id_master_term_payments">
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $datas->appends([
                            'supplier_code' => $supplier_code,
                            'name' => $name,
                            'name_invoice' => $name_invoice,
                            'address' => $address,
                            'postal_code' => $postal_code,
                            'city' => $city,
                            'email' => $email,
                            'status' => $status,
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
                    supplier_code: {!! json_encode($supplier_code) !!},
                    name: {!! json_encode($name) !!},
                    name_invoice: {!! json_encode($name_invoice) !!},
                    address: {!! json_encode($address) !!},
                    postal_code: {!! json_encode($postal_code) !!},
                    city: {!! json_encode($city) !!},
                    email: {!! json_encode($email) !!},
                    status: {!! json_encode($status) !!},
                    searchDate: {!! json_encode($searchDate) !!},
                    startdate: {!! json_encode($startdate) !!},
                    enddate: {!! json_encode($enddate) !!},
                    flag: 1,
                };

                var currentDate = new Date();
                var formattedDate = currentDate.toISOString().split('T')[0];
                var fileName = "Master Supplier Export - " + formattedDate + ".xlsx";

                exportToExcel("{{ route('supplier.index') }}", fileName, requestData);
            });
        </script>
    </div>
</div>

@endsection