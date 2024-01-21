@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List Employee</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Employee</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('fail'))
            <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <i class="mdi mdi-block-helper label-icon"></i><strong>Failed</strong> - {{ session('fail') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <i class="mdi mdi-alert-outline label-icon"></i><strong>Warning</strong> - {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <i class="mdi mdi-alert-circle-outline label-icon"></i><strong>Info</strong> - {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
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
                                                    <select class="form-control" name="id_master_provinces" required>
                                                        <option value="" selected>--Select Province--</option>
                                                        @foreach($provinces as $province)
                                                            <option value="{{ $province->id }}">{{ $province->province }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Country</label><label style="color: darkred">*</label>
                                                    <select class="form-control" name="id_master_countries" required>
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
                                                            <select class="form-control" name="id_master_departements" required>
                                                                <option value="" selected>--Select Department--</option>
                                                                @foreach($departments as $data)
                                                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Bagian</label>
                                                            <select class="form-control" name="id_master_bagians">
                                                                <option value="" selected>--Select Bagian--</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 mb-2">
                                                            <label class="form-label">Work Center</label>
                                                            <select class="form-control" name="id_master_work_centers">
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
                                                                <input class="form-control" name="basic_salary" type="text" value="3.841.368" placeholder="Input Basic Salary.." required>
                                                                <div class="input-group-text" style="background-color:rgb(211, 211, 211)">,00</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Regional Minimum</label><label style="color: darkred">*</label>
                                                            <div class="input-group">
                                                                <div class="input-group-text" style="background-color:rgb(211, 211, 211)">Rp.</div>
                                                                <input class="form-control" name="regional_minimum_wage" type="text" value="4.240.000" placeholder="Input Regional Minimum.." required>
                                                                <div class="input-group-text" style="background-color:rgb(211, 211, 211)">,00</div>
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
                                                            <select class="form-control" name="status" required>
                                                                <option value="" selected>--Select Status--</option>
                                                                <option value="Active">Active</option>
                                                                <option value="Not Active">Not Active</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Status Employee</label><label style="color: darkred">*</label>
                                                            <select class="form-control" name="status_employee" required>
                                                                <option value="" selected>--Select Status--</option>
                                                                <option value="Tetap">Tetap</option>
                                                                <option value="Kontrak">Kontrak</option>
                                                                <option value="BCA">BCA</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-6 mb-2">
                                                            <label class="form-label">Staff</label><label style="color: darkred">*</label>
                                                            <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                                <input type="checkbox" name="staff" class="form-check-input" id="customSwitchsizemd">
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
                                    <th class="align-middle text-center">Employee Name</th>
                                    <th class="align-middle text-center">Department</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 0;?> 
                                @foreach ($datas as $data)
                                <?php $no++ ;?>
                                <tr>
                                    <td class="align-middle text-center">{{ $no }}</td>
                                    <td class="align-middle">
                                        <b>{{ $data->employee_code }}</b>
                                        <br>
                                        {{ $data->name }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $data->departmentname }}
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
                                                    <h5 class="modal-title" id="staticBackdropLabel">Info Employee</h5>
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
                                                                <div><span class="fw-bold">Employee Code :</span></div>
                                                                <span>
                                                                    <span>{{ $data->employee_code }}</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <div class="form-group">
                                                                <div><span class="fw-bold">Employee Name :</span></div>
                                                                <span>
                                                                    <span>{{ $data->name }}</span>
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
                                                                <div><span class="fw-bold">User Finger :</span></div>
                                                                <span>
                                                                    <span>{{ $data->user_id_finger }}</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <div class="form-group">
                                                                <div><span class="fw-bold">Department :</span></div>
                                                                <span>
                                                                    <span>{{ $data->departmentname }}</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <div class="form-group">
                                                                <div><span class="fw-bold">Bagian :</span></div>
                                                                <span>
                                                                    @if($data->bagianname == null)
                                                                        <span class="badge bg-secondary text-white">Not Set</span>
                                                                    @else
                                                                        <span>{{ $data->bagianname }}</span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <div class="form-group">
                                                                <div><span class="fw-bold">Work Center :</span></div>
                                                                <span>
                                                                    @if($data->work_center == null)
                                                                        <span class="badge bg-secondary text-white">Not Set</span>
                                                                    @else
                                                                        <span>{{ $data->work_center }}</span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <div class="form-group">
                                                                <div><span class="fw-bold">Basic Salary :</span></div>
                                                                <span>
                                                                    <span>{{ number_format($data->basic_salary, 0, ',', '.') }}</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <div class="form-group">
                                                                <div><span class="fw-bold">Regional Minimum :</span></div>
                                                                <span>
                                                                    <span>{{ number_format($data->regional_minimum_wage, 0, ',', '.') }}</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <div class="form-group">
                                                                <div><span class="fw-bold">Account Number :</span></div>
                                                                <span>
                                                                    <span>{{ $data->account_number }}</span>
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
                                                                <div><span class="fw-bold">Status Employee :</span></div>
                                                                <span>
                                                                    <span>{{ $data->status_employee }}</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 mb-2">
                                                            <div class="form-group">
                                                                <div><span class="fw-bold">Staff :</span></div>
                                                                <span>
                                                                    @if($data->staff == 'N')
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
                                                    <h5 class="modal-title" id="staticBackdropLabel">Update Employee</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('employee.update', encrypt($data->id)) }}" id="formupdate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
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
                                                                <select class="form-control" name="id_master_provinces" required>
                                                                    <option value="" selected>--Select Province--</option>
                                                                    @foreach($allprovinces as $province)
                                                                        <option value="{{ $province->id }}" @if($data->id_master_provinces === $province->id) selected="selected" @endif>{{ $province->province }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <label class="form-label">Country</label><label style="color: darkred">*</label>
                                                                <select class="form-control" name="id_master_countries" required>
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
                                                                        <select class="form-control" name="id_master_departements" id="id_master_departements{{ $data->id }}" required>
                                                                            <option value="" selected>--Select Department--</option>
                                                                            @foreach($alldepartments as $depart)
                                                                                <option value="{{ $depart->id }}" @if($data->id_master_departements === $depart->id) selected="selected" @endif>{{ $depart->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-6 mb-2">
                                                                        <label class="form-label">Bagian</label>
                                                                        <select class="form-control" name="id_master_bagians" id="id_master_bagians{{ $data->id }}">
                                                                            @if($data->id_master_bagians === null)
                                                                                <option value="" selected>--Select Bagian--</option>
                                                                            @else
                                                                                <option value="{{ $data->id_master_bagians }}" selected>{{ $data->bagianname }}</option>
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-12 mb-2">
                                                                        <label class="form-label">Work Center</label>
                                                                        <select class="form-control" name="id_master_work_centers">
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
                                                                            <input class="form-control" name="basic_salary" type="text" value="{{ number_format($data->basic_salary, 0, ',', '.') }}" placeholder="Input Basic Salary.." required>
                                                                            <div class="input-group-text" style="background-color:rgb(211, 211, 211)">,00</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 mb-2">
                                                                        <label class="form-label">Regional Minimum</label><label style="color: darkred">*</label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-text" style="background-color:rgb(211, 211, 211)">Rp.</div>
                                                                            <input class="form-control" name="regional_minimum_wage" type="text" value="{{ number_format($data->regional_minimum_wage, 0, ',', '.') }}" placeholder="Input Regional Minimum.." required>
                                                                            <div class="input-group-text" style="background-color:rgb(211, 211, 211)">,00</div>
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
                                                                        <select class="form-control" name="status" required>
                                                                            <option value="" selected>--Select Status--</option>
                                                                            <option value="Active" @if($data->status === "Active") selected="selected" @endif>Active</option>
                                                                            <option value="Not Active" @if($data->status === "Not Active") selected="selected" @endif>Not Active</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-6 mb-2">
                                                                        <label class="form-label">Status Employee</label><label style="color: darkred">*</label>
                                                                        <select class="form-control" name="status_employee" required>
                                                                            <option value="" selected>--Select Status--</option>
                                                                            <option value="Tetap" @if($data->status_employee === "Tetap") selected="selected" @endif>Tetap</option>
                                                                            <option value="Kontrak" @if($data->status_employee === "Kontrak") selected="selected" @endif>Kontrak</option>
                                                                            <option value="BCA" @if($data->status_employee === "BCA") selected="selected" @endif>BCA</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-6 mb-2">
                                                                        <label class="form-label">Staff</label><label style="color: darkred">*</label>
                                                                        <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                                            <input type="checkbox" name="staff" class="form-check-input" id="customSwitchsizemd" @if($data->staff === "Y") checked="checked" @endif>
                                                                        </div>
                                                                    </div>
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
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Activate --}}
                                    <div class="modal fade" id="activate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Activate Employee</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('employee.activate', encrypt($data->id)) }}" id="formactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            Are You Sure to <b>Activate</b> This Employee?
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
                                                    <h5 class="modal-title" id="staticBackdropLabel">Deactivate Employee</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('employee.deactivate', encrypt($data->id)) }}" id="formdeactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            Are You Sure to <b>Deactivate</b> This Employee?
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

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection