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
                                            <span>No</span>
                                        @else
                                            <span>Yes</span>
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