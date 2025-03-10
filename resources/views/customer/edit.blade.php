@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <form action="{{ route('customer.index') }}" method="GET" id="resetForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="idUpdated" value="{{ $data->id }}">
                            <button type="submit" class="btn btn-light waves-effect btn-label waves-light">
                                <i class="mdi mdi-arrow-left label-icon"></i> Back To List Customer
                            </button>
                        </form>
                        {{-- <a href="{{ route('customer.index') }}" class="btn btn-light waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left label-icon"></i> Back To List Customer
                        </a> --}}
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.alert')

        <form action="{{ route('customer.update', encrypt($data->id)) }}" id="formupdate" method="POST" enctype="multipart/form-data">
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
                            <hr>
                            <div class="row">
                                <div class="col-12 align-right">
                                    <a href="{{ route('customer.index') }}" type="button" class="btn btn-light waves-effect btn-label waves-light">
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

    // getBagianFromDepartment
    $('#id_master_departements{{ $data->id }}').on('change', function() {
        var iddept = $(this).val();
        var url = '{{ route("department.mappingBagian", ":id") }}';
        url = url.replace(':id', iddept);
        if (iddept) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#id_master_bagians{{ $data->id }}').empty();
                    $('#id_master_bagians{{ $data->id }}').append(
                        '<option value="" selected>--Select Bagian--</option>'
                    );
                    $.each(data, function(div, value) {
                        $('#id_master_bagians{{ $data->id }}').append(
                            '<option value="' + value.id + '">' + value.name + '</option>'
                        );
                    });
                }
            });
        } else {
            $('#id_master_bagians{{ $data->id }}').empty();
        }
    });

    // Rupiah Input Format
    document.getElementById('basic_salary').addEventListener('input', formatCurrencyInput);
    document.getElementById('regional_minimum_wage').addEventListener('input', formatCurrencyInput);
    
</script>

@endsection