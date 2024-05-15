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
                            <li class="breadcrumb-item active">Info</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <h5 class="mb-0">Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 mb-2">
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
                                    <div><span class="fw-bold">NIK :</span></div>
                                    <span>
                                        <span>{{ $data->nik }}</span>
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
                                <div class="form-group">
                                    <div><span class="fw-bold">Address :</span></div>
                                    <div class="card py-2 px-2" style="background-color: aliceblue">
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
                                        @if($data->staff == 'Y')
                                            <span>Yes</span>
                                        @else
                                            <span>No</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection