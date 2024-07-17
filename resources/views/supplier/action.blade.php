<div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu2" aria-labelledby="btnGroupDrop{{ $data->id }}">
        <li><a class="dropdown-item drpdwn" href="{{ route('supplier.info', encrypt($data->id)) }}"><span class="mdi mdi-information"></span> | Info</a></li>
        <li><a class="dropdown-item drpdwn" href="{{ route('supplier.edit', encrypt($data->id)) }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
        @if($data->status == 'Active')
            <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#deactivate{{ $data->id }}"><span class="mdi mdi-close-circle"></span> | Deactivate</a></li>
        @else
            <li><a class="dropdown-item drpdwn-scs" href="#" data-bs-toggle="modal" data-bs-target="#activate{{ $data->id }}"><span class="mdi mdi-check-circle"></span> | Activate</a></li>
        @endif
        <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | Delete</a></li>
    </ul>
</div>

{{-- Modal --}}
<div class="left-align truncate-text">
    @if($data->status == 'Active')
        {{-- Modal Deactivate --}}
        <div class="modal fade" id="deactivate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Deactivate Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('supplier.deactivate', encrypt($data->id)) }}" id="formdeactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="text-center">
                                Are You Sure to <b>Deactivate</b> This Supplier?
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
    @else
        {{-- Modal Activate --}}
        <div class="modal fade" id="activate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Activate Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('supplier.activate', encrypt($data->id)) }}" id="formactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="text-center">
                                Are You Sure to <b>Activate</b> This Supplier?
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
    @endif

    {{-- Modal Delete --}}
    <div class="modal fade" id="delete{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('supplier.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <p class="text-center">Are You Sure To Delete This Data?</p>
                            <p class="text-center"><b>{{ $data->supplier_code }}</b></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-delete{{ $data->id }}"><i class="mdi mdi-delete label-icon"></i>Delete</button>
                    </div>
                </form>
                <script>
                    $(document).ready(function() {
                        let userId = "{{ $data->id }}";
                        $('#formdelete' + userId).submit(function(e) {
                            if (!$('#formdelete' + userId).valid()){
                                e.preventDefault();
                            } else {
                                $('#sb-delete' + userId).attr("disabled", "disabled");
                                $('#sb-delete' + userId).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>