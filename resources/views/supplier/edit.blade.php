@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <form action="{{ route('supplier.index') }}" method="GET" id="resetForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="idUpdated" value="{{ $data->id }}">
                            <button type="submit" class="btn btn-light waves-effect btn-label waves-light">
                                <i class="mdi mdi-arrow-left label-icon"></i> Back To List Supplier
                            </button>
                        </form>
                        {{-- <a href="{{ route('supplier.index') }}" class="btn btn-light waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left label-icon"></i> Back To List Supplier
                        </a> --}}
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('supplier.index') }}">Supplier</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.alert')

        <form action="{{ route('supplier.update', encrypt($data->id)) }}" id="formupdate" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center py-3">
                            <h5 class="mb-0">Edit</h5>
                        </div>
                        <div class="card-body">
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
                            <hr>
                            <div class="row">
                                <div class="col-12 align-right">
                                    <a href="{{ route('supplier.index') }}" type="button" class="btn btn-light waves-effect btn-label waves-light">
                                        <i class="mdi mdi-arrow-left-circle label-icon"></i>Back
                                    </a>
                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb">
                                        <i class="mdi mdi-update label-icon"></i>Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Validation Form --}}
<script>
    document.getElementById('formupdate').addEventListener('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            return false;
        }
        var submitButton = this.querySelector('button[name="sb"]');
        submitButton.disabled = true;
        submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
        return true;
    });
</script>

@endsection