@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-plus-box label-icon"></i> Add New Term Payment</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New Term Payment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('termpayment.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Term Payment Code</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="term_payment_code" type="text" value="" placeholder="Input Term Payment Code.." required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Term Payment</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="term_payment" type="text" value="" placeholder="Input Term Payment.." required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Term Payment Period</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="payment_period" type="text" value="" placeholder="Input Term Payment Period.." required>
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
                                    <form action="{{ route('termpayment.index') }}" id="formfilter" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body py-8 px-4" style="max-height: 67vh; overflow-y: auto;">
                                            <div class="row">
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Code</label>
                                                    <input class="form-control" name="term_payment_code" type="text" value="{{ $term_payment_code }}" placeholder="Filter Code..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Term Payment</label>
                                                    <input class="form-control" name="term_payment" type="text" value="{{ $term_payment }}" placeholder="Filter Term Payment..">
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="" selected>--All--</option>
                                                        <option value="1" @if($status == '1') selected @endif>Active</option>
                                                        <option value="0" @if($status == '0') selected @endif>Not Active</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-2">
                                                    <label class="form-label">Payment Period</label>
                                                    <input class="form-control" name="payment_period" type="text" value="{{ $payment_period }}" placeholder="Filter Payment Period..">
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
                            <li class="breadcrumb-item active">Term Payment</li>
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
                        <h5 class="mb-0"><b>Master Term Payment</b></h5>
                        List of 
                        @if($term_payment_code != null)
                            (Code<b> - {{ $term_payment_code }}</b>)
                        @endif
                        @if($term_payment != null)
                            (Term Payment<b> - {{ $term_payment }}</b>)
                        @endif
                        @if($status != null)
                            (Status<b> - {{ $status }}</b>)
                        @endif
                        @if($payment_period != null)
                            (Payment Period<b> - {{ $payment_period }}</b>)
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
                                    <th class="align-middle text-center">Code</th>
                                    <th class="align-middle text-center">Term Payment</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td class="align-middle text-center">{{ $data->no }}</td>
                                        <td class="align-middle text-center">{{ $data->term_payment_code }}</td>
                                        <td class="align-middle"><b>{{ $data->term_payment }}</b></td>
                                        <td class="align-middle text-center">
                                            @if($data->is_active == 1)
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
                                                    @if($data->is_active == 0)
                                                        <li><a class="dropdown-item drpdwn-scs" href="#" data-bs-toggle="modal" data-bs-target="#activate{{ $data->id }}"><span class="mdi mdi-check-circle"></span> | Activate</a></li>
                                                    @else
                                                        <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#deactivate{{ $data->id }}"><span class="mdi mdi-close-circle"></span> | Deactivate</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>

                                        {{-- Modal Info --}}
                                        <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Info Term Payment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 mb-2">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Status :</span></div>
                                                                    <span>
                                                                        @if($data->is_active == 1)
                                                                            <span class="badge bg-success text-white">Active</span>
                                                                        @else
                                                                            <span class="badge bg-danger text-white">Inactive</span>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 mb-2">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Term Payment Code :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->term_payment_code }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Term Payment :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->term_payment }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Term Payment Period :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->payment_period }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <div><span class="fw-bold">Created At :</span></div>
                                                                    <span>
                                                                        <span>{{ $data->created_at }}</span>
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
                                            <div class="modal-dialog modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Edit Term Payment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('termpayment.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Term Payment Code</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="term_payment_code" type="text" value="{{ $data->term_payment_code }}" placeholder="Input Term Payment Code.." required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Term Payment</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="term_payment" type="text" value="{{ $data->term_payment }}" placeholder="Input Term Payment.." required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Term Payment Period</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="payment_period" type="text" value="{{ $data->payment_period }}" placeholder="Input Term Payment Period.." required>
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
                                                        <h5 class="modal-title" id="staticBackdropLabel">Activate Term Payment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('termpayment.activate', encrypt($data->id)) }}" id="formactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                Are You Sure to <b>Activate</b> This Term Payment?
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
                                                        <h5 class="modal-title" id="staticBackdropLabel">Deactivate Term Payment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('termpayment.deactivate', encrypt($data->id)) }}" id="formdeactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="text-center">
                                                                Are You Sure to <b>Deactivate</b> This Term Payment?
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
                            'term_payment_code' => $term_payment_code,
                            'term_payment' => $term_payment,
                            'status' => $status,
                            'payment_period' => $payment_period,
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
                    term_payment_code: {!! json_encode($term_payment_code) !!},
                    term_payment: {!! json_encode($term_payment) !!},
                    status: {!! json_encode($status) !!},
                    payment_period: {!! json_encode($payment_period) !!},
                    searchDate: {!! json_encode($searchDate) !!},
                    startdate: {!! json_encode($startdate) !!},
                    enddate: {!! json_encode($enddate) !!},
                    flag: 1,
                };

                var currentDate = new Date();
                var formattedDate = currentDate.toISOString().split('T')[0];
                var fileName = "Master Term Payment Export - " + formattedDate + ".xlsx";

                exportToExcel("{{ route('termpayment.index') }}", fileName, requestData);
            });
        </script>
    </div>
</div>

@endsection