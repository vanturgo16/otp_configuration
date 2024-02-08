@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Customer</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Customer</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('customer.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
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
                                                    <select class="form-select" name="status" required>
                                                        <option value="" selected>--Select Status--</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Not Active">Not Active</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Customer Name</label><label style="color: darkred">*</label>
                                                    <input class="form-control" name="name" type="text" value="" placeholder="Input Customer Name.." required>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Remarks</label>
                                                    <input class="form-control" name="remark" type="text" value="" placeholder="Input Remarks..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Tax Number</label>
                                                    <input class="form-control" name="tax_number" type="text" value="" placeholder="Input Tax Number..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Tax Code</label>
                                                    <input class="form-control" name="tax_number" type="text" value="" placeholder="Input Tax Code..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Salesman</label><label style="color: darkred">*</label>
                                                    <select class="form-select" name="id_master_salesmen" required>
                                                        <option value="" selected>--Select Salesman--</option>
                                                        @foreach($salesmans as $data)
                                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Currency</label>
                                                    <select class="form-select" name="id_master_currencies">
                                                        <option value="" selected>--Select Currency--</option>
                                                        @foreach($currencies as $data)
                                                            <option value="{{ $data->id }}">{{ $data->currency }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Term Payment</label><label style="color: darkred">*</label>
                                                    <select class="form-select" name="id_master_term_payments" required>
                                                        <option value="" selected>--Select Term Payment--</option>
                                                        @foreach($terms as $data)
                                                            <option value="{{ $data->id }}">{{ $data->term_payment }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Ppn</label><label style="color: darkred">*</label>
                                                    <select class="form-select" name="ppn" required>
                                                        <option value="" selected>--Select Ppn--</option>
                                                        <option value="Include">Include</option>
                                                        <option value="Exclude">Exclude</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">CBC</label><label style="color: darkred">*</label>
                                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                        <input type="checkbox" name="cbc" class="form-check-input" id="customSwitchsizemd">
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
                                    <form action="{{ route('customer.index') }}" id="formfilter" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Customer Code</label>
                                                    <input class="form-control" name="customer_code" type="text" value="{{ $customer_code }}" placeholder="Input Customer Code..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="" selected>--All--</option>
                                                        <option value="Active" @if($status == 'Active') selected @endif>Active</option>
                                                        <option value="Not Active" @if($status == 'Not Active') selected @endif>Not Active</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Customer Name</label>
                                                    <input class="form-control" name="name" type="text" value="{{ $name }}" placeholder="Input Customer Name..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Remarks</label>
                                                    <input class="form-control" name="remark" type="text" value="{{ $remark }}" placeholder="Input Remarks..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Tax Number</label>
                                                    <input class="form-control" name="tax_number" type="text" value="{{ $tax_number }}" placeholder="Input Tax Number..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Tax Code</label>
                                                    <input class="form-control" name="tax_number" type="text" value="{{ $tax_number }}" placeholder="Input Tax Code..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Salesman</label>
                                                    <select class="form-select" name="id_master_salesmen">
                                                        <option value="" selected>--Select Salesman--</option>
                                                        @foreach($salesmans as $data)
                                                            <option value="{{ $data->id }}" @if($id_master_salesmen == $data->id) selected="selected" @endif>{{ $data->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Currency</label>
                                                    <select class="form-select" name="id_master_currencies">
                                                        <option value="" selected>--Select Currency--</option>
                                                        @foreach($currencies as $data)
                                                            <option value="{{ $data->id }}" @if($id_master_currencies == $data->id) selected="selected" @endif>{{ $data->currency }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Term Payment</label>
                                                    <select class="form-select" name="id_master_term_payments">
                                                        <option value="" selected>--Select Term Payment--</option>
                                                        @foreach($terms as $data)
                                                            <option value="{{ $data->id }}" @if($id_master_term_payments == $data->id) selected="selected" @endif>{{ $data->term_payment }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Ppn</label>
                                                    <select class="form-select" name="ppn">
                                                        <option value="" selected>--Select Ppn--</option>
                                                        <option value="Include" @if($ppn == 'Include') selected="selected" @endif>Include</option>
                                                        <option value="Exclude" @if($ppn == 'Exclude') selected="selected" @endif>Exclude</option>
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
                            <li class="breadcrumb-item active">Customer</li>
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
                        <h5 class="mb-0"><b>Master Customer</b></h5>
                        List of 
                        @if($customer_code != null)
                            (Code<b> - {{ $customer_code }}</b>)
                        @endif
                        @if($name != null)
                            (Name<b> - {{ $name }}</b>)
                        @endif
                        @if($name != null)
                            (Name<b> - {{ $name }}</b>)
                        @endif
                        @if($remark != null)
                            (Remark<b> - {{ $remark }}</b>)
                        @endif
                        @if($ppn != null)
                            (PPN<b> - {{ $ppn }}</b>)
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
                                    <th class="align-middle text-center">Customer Name</th>
                                    <th class="align-middle text-center">Salesman</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td class="align-middle text-center">{{ $data->no }}</td>
                                        <td class="align-middle">
                                            <b>{{ $data->customer_code }}</b>
                                            <br>
                                            {{ $data->name }}
                                        </td>
                                        <td class="align-middle text-center">
                                            @if($data->salesmanname == null)
                                                <span class="badge bg-secondary text-white">Not Set</span>
                                            @else
                                                <b>{{ $data->salesmanname }}</b>
                                            @endif
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
                                                    <li><a class="dropdown-item drpdwn" href="{{ route('customeraddress.index', encrypt($data->id)) }}"><span class="mdi mdi-menu"></span> | Address</a></li>
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
                                                                            <span>Yes</span>
                                                                        @else
                                                                            <span>No</span>
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
                                                                    <select class="form-select" name="status" required>
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
                                                                    <select class="form-select" name="id_master_salesmen" required>
                                                                        <option value="" selected>--Select Salesman--</option>
                                                                        @foreach($allsalesmans as $sales)
                                                                            <option value="{{ $sales->id }}" @if($data->id_master_salesmen === $sales->id) selected="selected" @endif>{{ $sales->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Currency</label>
                                                                    <select class="form-select" name="id_master_currencies">
                                                                        <option value="" selected>--Select Currency--</option>
                                                                        @foreach($currencies as $cr)
                                                                            <option value="{{ $cr->id }}" @if($data->id_master_currencies === $cr->id) selected="selected" @endif>{{ $cr->currency }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Term Payment</label><label style="color: darkred">*</label>
                                                                    <select class="form-select" name="id_master_term_payments" required>
                                                                        <option value="" selected>--Select Term Payment--</option>
                                                                        @foreach($terms as $tr)
                                                                            <option value="{{ $tr->id }}" @if($data->id_master_term_payments === $tr->id) selected="selected" @endif>{{ $tr->term_payment }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Ppn</label><label style="color: darkred">*</label>
                                                                    <select class="form-select" name="ppn" required>
                                                                        <option value="" selected>--Select Ppn--</option>
                                                                        <option value="Include" @if($data->ppn === "Include") selected="selected" @endif>Include</option>
                                                                        <option value="Exclude" @if($data->ppn === "Exclude") selected="selected" @endif>Exclude</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">CBC</label><label style="color: darkred">*</label>
                                                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                                        <input type="checkbox" name="cbc" class="form-check-input" id="customSwitchsizemd" @if($data->cbc === "Y") checked="checked" @endif>
                                                                    </div>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $datas->appends([
                            'customer_code' => $customer_code,
                            'name' => $name,
                            'remark' => $remark,
                            'tax_number' => $tax_number,
                            'tax_code' => $tax_code,
                            'id_master_salesmen' => $id_master_salesmen,
                            'id_master_currencies' => $id_master_currencies,
                            'id_master_term_payments' => $id_master_term_payments,
                            'ppn' => $ppn,
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
                    customer_code: {!! json_encode($customer_code) !!},
                    name: {!! json_encode($name) !!},
                    remark: {!! json_encode($remark) !!},
                    tax_number: {!! json_encode($tax_number) !!},
                    tax_code: {!! json_encode($tax_code) !!},
                    id_master_salesmen: {!! json_encode($id_master_salesmen) !!},
                    id_master_currencies: {!! json_encode($id_master_currencies) !!},
                    id_master_term_payments: {!! json_encode($id_master_term_payments) !!},
                    ppn: {!! json_encode($ppn) !!},
                    status: {!! json_encode($status) !!},
                    searchDate: {!! json_encode($searchDate) !!},
                    startdate: {!! json_encode($startdate) !!},
                    enddate: {!! json_encode($enddate) !!},
                    flag: 1,
                };

                var currentDate = new Date();
                var formattedDate = currentDate.toISOString().split('T')[0];
                var fileName = "Master Customer Export - " + formattedDate + ".xlsx";

                exportToExcel("{{ route('customer.index') }}", fileName, requestData);
            });
        </script>
    </div>
</div>

@endsection