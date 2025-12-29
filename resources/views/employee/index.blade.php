@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">

        @include('layouts.alert')

        <!-- Modal for bulk delete confirmation -->
        <div class="modal fade" id="deleteselected" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row text-center">
                            <p>Are you sure you want to delete the selected items?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-deleteselected" onclick="bulkDeleted('{{ route('employee.deleteselected') }}')"><i class="mdi mdi-delete label-icon"></i>Delete</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Search --}}
        <div class="modal fade" id="sort" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel"><i class="mdi mdi-filter label-icon"></i> Search & Filter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('employee.index') }}" id="formfilter" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Employee Code</label>
                                        <input class="form-control" name="employee_code" type="text" value="{{ $employee_code }}" placeholder="Input Employee Code..">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">NIK</label>
                                        <input class="form-control" name="nik" type="text" value="{{ $nik }}" placeholder="Input NIK..">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input class="form-control" name="name" type="text" value="{{ $name }}" placeholder="Input Name..">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input class="form-control" name="address" type="text" value="{{ $address }}" placeholder="Input Address..">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile Phone</label>
                                        <input class="form-control" name="mobile_phone" type="text" value="{{ $mobile_phone }}" placeholder="Input Mobile Phone..">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Department</label>
                                        <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_departements">
                                            <option value="" selected>--Select Department--</option>
                                            @foreach($departments as $data)
                                                <option value="{{ $data->id }}" @if($id_master_departements == $data->id) selected="selected" @endif>{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Basic Salary</label>
                                        <div class="input-group">
                                            <div class="input-group-text" style="background-color:rgb(211, 211, 211)">Rp.</div>
                                            <input id="basic_salary_search" class="form-control" name="basic_salary" type="text" value="{{ number_format($basic_salary, 3, '.', ',') }}" placeholder="Input Basic Salary..">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Status</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="status">
                                        <option value="" selected>--All--</option>
                                        <option value="Active" @if($status == 'Active') selected @endif>Active</option>
                                        <option value="Not Active" @if($status == 'Not Active') selected @endif>Not Active</option>
                                    </select>
                                </div>
                                <hr class="mt-2">
                                <div class="col-4 mb-2">
                                    <label class="form-label">Filter Date</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="searchDate">
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

                        // Rupiah Input Format
                        document.getElementById('basic_salary_search').addEventListener('input', formatCurrencyInput);
                    </script>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col-lg-4">
                                <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Employee</button>
                                {{-- Modal Add --}}
                                <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Add New Employee</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('employee.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                                    <div class="row">
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Employee Code (Exxxxxx)</label>
                                                            <br>
                                                            <span class="badge bg-info text-white">Auto Generate</span>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">NIK</label><label style="color: darkred">*</label>
                                                            <input class="form-control" name="nik" type="number" value="" placeholder="Input NIK.." required>
                                                        </div>
                                                        <div class="col-12 mb-2">
                                                            <label class="form-label">Employee Name (Full Name)</label><label style="color: darkred">*</label>
                                                            <input class="form-control" name="name" type="text" value="" placeholder="Input Employee Name.." required>
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
                                                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_provinces" required>
                                                                <option value="" selected>--Select Province--</option>
                                                                @foreach($allprovinces as $province)
                                                                    <option value="{{ $province->id }}">{{ $province->province }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Country</label><label style="color: darkred">*</label>
                                                            <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_countries" required>
                                                                <option value="" selected>--Select Country--</option>
                                                                @foreach($countries as $country)
                                                                    <option value="{{ $country->id }}">{{ $country->country }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Telephone</label><label style="color: darkred">*</label>
                                                            <input class="form-control" name="telephone" type="text" value="" placeholder="Input Telephone.." required>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Mobile Phone</label><label style="color: darkred">*</label>
                                                            <input class="form-control" name="mobile_phone" type="text" value="" placeholder="Input Mobile Phone.." required>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Fax</label><label style="color: darkred">*</label>
                                                            <input class="form-control" name="fax" type="text" value="" placeholder="Input Fax.." required>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Email</label><label style="color: darkred">*</label>
                                                            <input class="form-control" name="email" type="email" value="" placeholder="Input Email.." required>
                                                        </div>
                                                        <div class="card mt-2" style="background-color:rgb(236, 236, 236)">
                                                            <div class="row">
                                                                <div class="col-12 text-center mt-2">
                                                                    <label>Internal Information</label>
                                                                </div>
                                                                <hr>
                                                                <div class="col-12 mb-2">
                                                                    <label class="form-label">User Finger</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="user_id_finger" type="text" value="" placeholder="Input User Finger.." required>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Department</label><label style="color: darkred">*</label>
                                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_departements" required>
                                                                        <option value="" selected>--Select Department--</option>
                                                                        @foreach($departments as $data)
                                                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Bagian</label>
                                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_bagians">
                                                                        <option value="" selected>--Select Bagian--</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <label class="form-label">Work Center</label>
                                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_work_centers">
                                                                        <option value="" selected>--Select Work Center--</option>
                                                                        @foreach($workcenters as $data)
                                                                            <option value="{{ $data->id }}">{{ $data->work_center }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Basic Salary</label><label style="color: darkred">*</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-text" style="background-color:rgb(211, 211, 211)">Rp.</div>
                                                                        <input id="basic_salary" class="form-control" name="basic_salary" type="text" value="3.841.368" placeholder="Input Basic Salary.." required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Regional Minimum</label><label style="color: darkred">*</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-text" style="background-color:rgb(211, 211, 211)">Rp.</div>
                                                                        <input id="regional_minimum_wage" class="form-control" name="regional_minimum_wage" type="text" value="4.240.000" placeholder="Input Regional Minimum.." required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Account Number</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="account_number" type="text" value="" placeholder="Input Account Number.." required>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Remarks</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="remarks" type="text" value="" placeholder="Input Remarks.." required>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Password</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="password" type="password" value="" placeholder="Input Password.." required>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Status</label><label style="color: darkred">*</label>
                                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="status" required>
                                                                        <option value="" selected>--Select Status--</option>
                                                                        <option value="Active">Active</option>
                                                                        <option value="Not Active">Not Active</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Status Employee</label><label style="color: darkred">*</label>
                                                                    <select class="form-select js-example-basic-single" style="width: 100%" name="status_employee" required>
                                                                        <option value="" selected>--Select Status--</option>
                                                                        <option value="Tetap">Tetap</option>
                                                                        <option value="Kontrak">Kontrak</option>
                                                                        <option value="BCA">BCA</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <label class="form-label">Staff</label><label style="color: darkred">*</label>
                                                                    <div class="d-flex flex-wrap gap-2">
                                                                        <div class="square-switch">
                                                                            <input type="checkbox" name="staff" id="square-switch1" switch="none"/>
                                                                            <label for="square-switch1" data-on-label="Yes"
                                                                                data-off-label="No"></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-account-plus label-icon"></i>Add</button>
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

                                                // getBagianFromDepartment
                                                $('select[name="id_master_departements"]').on('change', function() {
                                                    var iddept = $(this).val();
                                                    var url = '{{ route("department.mappingBagian", ":id") }}';
                                                    url = url.replace(':id', iddept);
                                                    if (iddept) {
                                                        $.ajax({
                                                            url: url,
                                                            type: "GET",
                                                            dataType: "json",
                                                            success: function(data) {
                                                                $('select[name="id_master_bagians"]').empty();
                                                                $('select[name="id_master_bagians"]').append(
                                                                    '<option value="" selected>--Select Bagian--</option>'
                                                                );
                                                                $.each(data, function(div, value) {
                                                                    $('select[name="id_master_bagians"]').append(
                                                                        '<option value="' +
                                                                        value.id +'">' + value.name + '</option>');
                                                                });
                                                            }
                                                        });
                                                    } else {
                                                        $('select[name="id_master_bagians"]').empty();
                                                    }
                                                });

                                                // Rupiah Input Format
                                                document.getElementById('basic_salary').addEventListener('input', formatCurrencyInput);
                                                document.getElementById('regional_minimum_wage').addEventListener('input', formatCurrencyInput);
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="text-center">
                                    <h5 class="fw-bold">Master Employee</h5>
                                </div>
                            </div>
                            <div class="col-lg-4"></div>
                            <div class="col-lg-12">
                                <div class="text-center">
                                    List of 
                                    @if($employee_code)
                                        (Employee Code<b> - {{ $employee_code }}</b>)
                                    @endif
                                    @if($nik)
                                        (NIK<b> - {{ $nik }}</b>)
                                    @endif
                                    @if($name)
                                        (Name<b> - {{ $name }}</b>)
                                    @endif
                                    @if($name)
                                        (Address<b> - {{ $address }}</b>)
                                    @endif
                                    @if($mobile_phone)
                                        (Phone<b> - {{ $mobile_phone }}</b>)
                                    @endif
                                    @if($id_master_departements)
                                        (ID Department<b> - {{ $id_master_departements }}</b>)
                                    @endif
                                    @if($basic_salary)
                                        (Basic Salary<b> - {{ $basic_salary }}</b>)
                                    @endif
                                    @if($status)
                                        (Status<b> - {{ $status }}</b>)
                                    @endif
                                    @if($searchDate == 'Custom')
                                        (Date From<b> {{ $startdate }} </b>Until <b>{{ $enddate }}</b>)
                                    @else
                                        (<b>All Date</b>)
                                    @endif 
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <table class="table table-bordered dt-responsive w-100" id="server-side-table" style="font-size: small">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle text-center">
                                        <input type="checkbox" id="checkAllRows">
                                    </th>
                                    <th class="align-middle text-center">#</th>
                                    <th class="align-middle text-center">Employee Name</th>
                                    <th class="align-middle text-center">NIK</th>
                                    <th class="align-middle text-center">Address</th>
                                    <th class="align-middle text-center">Mobile Phone</th>
                                    <th class="align-middle text-center">Department</th>
                                    <th class="align-middle text-center">Basic Salary (Rp)</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var i = 1;
        var url = '{!! route('employee.index') !!}';
        
        var idUpdated = '{{ $idUpdated }}';
        var pageNumber = '{{ $page_number }}';
        var pageLength = 5;
        var displayStart = (pageNumber - 1) * pageLength;
        var firstReload = true; 

        var currentDate = new Date();
        var formattedDate = currentDate.toISOString().split('T')[0];
        var fileName = "Master Employee Export - " + formattedDate + ".xlsx";
        var data = {
            employee_code: '{{ $employee_code }}',
            nik: '{{ $nik }}',
            name: '{{ $name }}',
            address: '{{ $address }}',
            mobile_phone: '{{ $mobile_phone }}',
            id_master_departements: '{{ $id_master_departements }}',
            basic_salary: '{{ $basic_salary }}',
            status: '{{ $status }}',
            searchDate: '{{ $searchDate }}',
            startdate: '{{ $startdate }}',
            enddate: '{{ $enddate }}'
        };
        var requestData = Object.assign({}, data);
        requestData.flag = 1;

        var dataTable = $('#server-side-table').DataTable({
            dom: '<"top d-flex"<"position-absolute top-0 end-0 d-flex"fl><"pull-left col-sm-12 col-md-5"B>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>><"clear:both">',
            initComplete: function(settings, json) {
                $('.dataTables_filter').html('<div class="input-group">' +
                '<button class="btn btn-sm btn-light me-1" type="button" id="custom-button" data-bs-toggle="modal" data-bs-target="#sort"><i class="mdi mdi-filter label-icon"></i> Sort & Filter</button>' +
                '<input class="form-control me-1" id="custom-search-input" type="text" placeholder="Search...">' +
                '</div>');
                $('.top').prepend(
                    `<div class='pull-left'>
                        <div class="btn-group mb-2" style="margin-right: 10px;"> <!-- Added inline style for margin -->
                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-checkbox-multiple-marked-outline"></i> Bulk Actions <i class="fas fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteselected"><i class="mdi mdi-trash-can"></i> Delete Selected</button></li>
                            </ul>
                        </div>
                    </div>`
                );
            },
            buttons: [
                {
                    extend: "excel",
                    text: '<i class="mdi mdi-file-excel label-icon"></i> Export to Excel',
                    className: 'btn btn-light waves-effect btn-label waves-light mb-2',
                    action: function (e, dt, button, config) {
                        $.ajax({
                            url: url,
                            method: "GET",
                            data: requestData,
                            success: function (response) {
                                generateExcel(response, fileName);
                            },
                            error: function (error) {
                                console.error(
                                    "Error sending data to server:",
                                    error
                                );
                            },
                        });
                    },
                },
            ],
            language: {
                processing: '<div id="custom-loader" class="dataTables_processing"></div>'
            },
            processing: true,
            serverSide: true,
            
            displayStart: displayStart,
            pageLength: pageLength,

            lengthMenu: [
                [5, 10, 20, 25, 50, 100, 200, -1],
                [5, 10, 20, 25, 50, 100, 200, "All"]
            ],
            language: {
                lengthMenu: '<select class="form-select" style="width: 100%">' +
                            '<option value="5">5</option>' +
                            '<option value="10">10</option>' +
                            '<option value="20">20</option>' +
                            '<option value="25">25</option>' +
                            '<option value="50">50</option>' +
                            '<option value="100">100</option>' +
                            '<option value="200">200</option>' +
                            '<option value="-1">All</option>' +
                            '</select>'
            },
            aaSorting: [],
            ajax: {
                url: url,
                type: 'GET',
                data: data
            },
            columns: [
                {
                    data: 'bulk-action',
                    name: 'bulk-action',
                    className: 'align-top text-center',
                    orderable: false,
                    searchable: false
                },
                {
                data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                    render: function(data, type, row) {
                        return '<b>' + row.employee_code + '</b><br>' + row.name;
                    },
                },
                {
                    data: 'nik',
                    name: 'nik',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'address',
                    name: 'address',
                    orderable: true,
                    searchable: true,
                    className: 'align-top',
                },
                {
                    data: 'mobile_phone',
                    name: 'mobile_phone',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                },
                {
                    data: 'departmentname',
                    name: 'departmentname',
                    orderable: true,
                    searchable: true,
                    className: 'align-top text-center',
                },
                {
                    data: 'basic_salary',
                    name: 'basic_salary',
                    orderable: true,
                    searchable: true,
                    className: 'text-end',
                    render: function(data, type, row) {
                        if (data == null) {
                            return '<span class="badge bg-secondary">Null</span>';
                        }
                        var formattedAmount = numberFormat(data, 3, ',', '.'); 
                        var parts = formattedAmount.split(',');
                        if (parts.length > 1) {
                            return ' <span class="text-bold">' + parts[0] + '</span><span class="text-muted">,' + parts[1] + '</span>';
                        }
                        return ' <span class="text-bold">' + parts[0] + '</span>';
                    },
                },
                {
                    data: 'status',
                    orderable: true,
                    className: 'align-top text-center',
                    render: function(data, type, row) {
                        var html
                        if(row.status == 'Active'){
                            html = '<span class="badge bg-success text-white">Active</span>';
                        } else {
                            html = '<span class="badge bg-danger text-white">Inactive</span>';
                        }
                        return html;
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'align-top text-center',
                },
            ],
            bAutoWidth: false,
            columnDefs: [{
                width: "1%",
                targets: [0]
            }],
            drawCallback: function(settings) {
                if (firstReload && idUpdated) {
                    // Reset URL
                    let urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.toString()) {
                        let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                        history.pushState({}, "", newUrl);
                    }
                    var row = dataTable.row(function(idx, data, node) {
                        return data.id == idUpdated;
                    });

                    if (row.length) {
                        var rowNode = row.node();
                        $('html, body').animate({
                            scrollTop: $(rowNode).offset().top - $(window).height() / 2
                        }, 500);
                        // Highlight the row for 5 seconds
                        $(rowNode).addClass('table-info');
                        setTimeout(function() {
                            $(rowNode).removeClass('table-info');
                        }, 3000);
                    }
                    firstReload = false;
                }
            }
        });

        $(document).on('keyup', '#custom-search-input', function () {
            dataTable.search(this.value).draw();
        });
        $('.dataTables_processing').css('z-index', '9999');
    });
</script>

@endsection