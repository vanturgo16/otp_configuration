@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List Address of <b class="text-primary">"{{ $customer->name}}"</b></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer</a></li>
                            <li class="breadcrumb-item active">Address</li>
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
                            <td class="align-middle"><b>Customer Code</b></td>
                            <td class="align-middle">: {{ $customer->customer_code }}</td>
                        </tr>
                        <tr>
                            <td class="align-middle"><b>Name</b></td>
                            <td class="align-middle">: {{ $customer->name}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Address</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Address</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('customeraddress.store', encrypt($customer->id)) }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
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
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Remark</label>
                                                    <input class="form-control" name="remarks" type="text" value="" placeholder="Input Remark..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Contact Person</label>
                                                    <input class="form-control" name="contact_person" type="text" value="" placeholder="Input Contact Person..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Type Address</label><label style="color: darkred">*</label>
                                                    <select class="form-select" name="type_address" required>
                                                        <option value="" selected>--Select Type Address--</option>
                                                        <option value="Invoice">Invoice</option>
                                                        <option value="Shipping">Shipping</option>
                                                        <option value="Same As (Invoice, Shipping)">Same As (Invoice, Shipping)</option>
                                                    </select>
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
                                    <form action="{{ route('customeraddress.index', encrypt($id)) }}" id="formfilter" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
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
                                                    <label class="form-label">Province</label>
                                                    <select class="form-select" name="id_master_provinces">
                                                        <option value="" selected>--Select Province--</option>
                                                        @foreach($provinces as $province)
                                                            <option value="{{ $province->id }}" @if($id_master_provinces == $province->id) selected="selected" @endif>{{ $province->province }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Country</label>
                                                    <select class="form-select" name="id_master_countries">
                                                        <option value="" selected>--Select Country--</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->id }}" @if($id_master_countries == $country->id) selected="selected" @endif>{{ $country->country }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Telephone</label>
                                                    <input class="form-control" name="telephone" type="text" value="{{ $address }}" placeholder="Input Telephone..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Mobile Phone</label>
                                                    <input class="form-control" name="mobile_phone" type="text" value="{{ $address }}" placeholder="Input Mobile Phone..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Fax</label>
                                                    <input class="form-control" name="fax" type="text" value="{{ $address }}" placeholder="Input Fax..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Email</label>
                                                    <input class="form-control" name="email" type="email" value="{{ $address }}" placeholder="Input Email..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Type Address</label>
                                                    <select class="form-select" name="type_address">
                                                        <option value="" selected>--Select Type Address--</option>
                                                        <option value="Invoice" @if($type_address == 'Invoice') selected="selected" @endif>Invoice</option>
                                                        <option value="Shipping" @if($type_address == 'Shipping') selected="selected" @endif>Shipping</option>
                                                        <option value="Same As (Invoice, Shipping)" @if($type_address == 'Same As (Invoice, Shipping)') selected="selected" @endif>Same As (Invoice, Shipping)</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="" selected>--All--</option>
                                                        <option value="Active" @if($status == 'Active') selected @endif>Active</option>
                                                        <option value="0" @if($status == '0') selected @endif>Not Active</option>
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
                                    <th class="align-middle text-center">Address</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td class="align-middle text-center">{{ $data->no }}</td>
                                        <td class="align-middle">{{ $data->address }}</td>
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
                                                                    <select class="form-select" name="type_address" required>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $datas->appends([
                            'address' => $address,
                            'postal_code' => $postal_code,
                            'city' => $city,
                            'id_master_provinces' => $id_master_provinces,
                            'id_master_countries' => $id_master_countries,
                            'telephone' => $telephone,
                            'mobile_phone' => $mobile_phone,
                            'email' => $email,
                            'type_address' => $type_address,
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
                    address: {!! json_encode($address) !!},
                    postal_code: {!! json_encode($postal_code) !!},
                    id_master_provinces: {!! json_encode($id_master_provinces) !!},
                    id_master_countries: {!! json_encode($id_master_countries) !!},
                    telephone: {!! json_encode($telephone) !!},
                    mobile_phone: {!! json_encode($mobile_phone) !!},
                    fax: {!! json_encode($fax) !!},
                    email: {!! json_encode($email) !!},
                    type_address: {!! json_encode($type_address) !!},
                    status: {!! json_encode($status) !!},
                    searchDate: {!! json_encode($searchDate) !!},
                    startdate: {!! json_encode($startdate) !!},
                    enddate: {!! json_encode($enddate) !!},
                    flag: 1,
                };

                var currentDate = new Date();
                var formattedDate = currentDate.toISOString().split('T')[0];
                var fileName = "Master Customer Address Export - " + formattedDate + ".xlsx";

                exportToExcel("{{ route('customeraddress.index', encrypt($id)) }}", fileName, requestData);
            });
        </script>
    </div>
</div>
@endsection