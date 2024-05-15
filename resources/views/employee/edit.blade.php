@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <a href="{{ route('employee.index') }}" class="btn btn-light waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left label-icon"></i> Back To List Employee
                        </a>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('employee.index') }}">Employee</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <form action="{{ route('employee.update', encrypt($data->id)) }}" id="formupdate" method="POST" enctype="multipart/form-data">
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
                                    <label class="form-label">Employee Code</label>
                                    <input class="form-control" style="background-color:lightgray" value="{{ $data->employee_code }}" placeholder="Input NIK.." readonly disabled>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">NIK</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="nik" type="number" value="{{ $data->nik }}" placeholder="Input NIK.." required>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label">Employee Name (Full Name)</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="name" type="text" value="{{ $data->name }}" placeholder="Input Employee Name.." required>
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
                                    <label class="form-label">Telephone</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="telephone" type="text" value="{{ $data->telephone }}" placeholder="Input Telephone.." required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Mobile Phone</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="mobile_phone" type="text" value="{{ $data->mobile_phone }}" placeholder="Input Mobile Phone.." required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Fax</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="fax" type="text" value="{{ $data->fax }}" placeholder="Input Fax.." required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Email</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="email" type="email" value="{{ $data->email }}" placeholder="Input Email.." required>
                                </div>
                                <div class="card mt-2" style="background-color:rgb(236, 236, 236)">
                                    <div class="row">
                                        <div class="col-12 text-center mt-2">
                                            <label>Internal Information</label>
                                        </div>
                                        <hr>
                                        <div class="col-12 mb-2">
                                            <label class="form-label">User Finger</label><label style="color: darkred">*</label>
                                            <input class="form-control" name="user_id_finger" type="text" value="{{ $data->user_id_finger }}" placeholder="Input User Finger.." required>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="form-label">Department</label><label style="color: darkred">*</label>
                                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_departements" id="id_master_departements{{ $data->id }}" required>
                                                <option value="" selected>--Select Department--</option>
                                                @foreach($alldepartments as $depart)
                                                    <option value="{{ $depart->id }}" @if($data->id_master_departements === $depart->id) selected="selected" @endif>{{ $depart->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="form-label">Bagian</label>
                                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_bagians" id="id_master_bagians{{ $data->id }}">
                                                @if($data->id_master_bagians === null)
                                                    <option value="" selected>--Select Bagian--</option>
                                                @else
                                                    <option value="{{ $data->id_master_bagians }}" selected>{{ $data->bagianname }}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label class="form-label">Work Center</label>
                                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_work_centers">
                                                <option value="" selected>--Select Work Center--</option>
                                                @foreach($allworkcenters as $wc)
                                                    <option value="{{ $wc->id }}" @if($data->id_master_work_centers === $wc->id) selected="selected" @endif>{{ $wc->work_center }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="form-label">Basic Salary</label><label style="color: darkred">*</label>
                                            <div class="input-group">
                                                <div class="input-group-text" style="background-color:rgb(211, 211, 211)">Rp.</div>
                                                <input id="basic_salary" class="form-control" name="basic_salary" type="text" value="{{ number_format($data->basic_salary, 3, ',', '.') }}" placeholder="Input Basic Salary.." required>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="form-label">Regional Minimum</label><label style="color: darkred">*</label>
                                            <div class="input-group">
                                                <div class="input-group-text" style="background-color:rgb(211, 211, 211)">Rp.</div>
                                                <input id="regional_minimum_wage" class="form-control" name="regional_minimum_wage" type="text" value="{{ number_format($data->regional_minimum_wage, 3, ',', '.') }}" placeholder="Input Regional Minimum.." required>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="form-label">Account Number</label><label style="color: darkred">*</label>
                                            <input class="form-control" name="account_number" type="text" value="{{ $data->account_number }}" placeholder="Input Account Number.." required>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="form-label">Remarks</label><label style="color: darkred">*</label>
                                            <input class="form-control" name="remarks" type="text" value="{{ $data->remarks }}" placeholder="Input Remarks.." required>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="form-label">Password (*Fill if want change)</label>
                                            <input class="form-control" name="password" type="password" value="" placeholder="Input Password..">
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
                                            <label class="form-label">Status Employee</label><label style="color: darkred">*</label>
                                            <select class="form-select js-example-basic-single" style="width: 100%" name="status_employee" required>
                                                <option value="" selected>--Select Status--</option>
                                                <option value="Tetap" @if($data->status_employee === "Tetap") selected="selected" @endif>Tetap</option>
                                                <option value="Kontrak" @if($data->status_employee === "Kontrak") selected="selected" @endif>Kontrak</option>
                                                <option value="BCA" @if($data->status_employee === "BCA") selected="selected" @endif>BCA</option>
                                            </select>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label class="form-label">Staff</label><label style="color: darkred">*</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <div class="square-switch">
                                                    <input type="checkbox" name="staff" id="square-switch1{{ $data->id }}" switch="none" @if($data->staff === "Y") checked="checked" @endif/>
                                                    <label for="square-switch1{{ $data->id }}" data-on-label="Yes"
                                                        data-off-label="No"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12 align-right">
                                    <a href="{{ route('employee.index') }}" type="button" class="btn btn-light waves-effect btn-label waves-light">
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