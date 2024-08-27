<div class="btn-group" role="group">
    <button id="btnGroupDrop{{ $data->id }}" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
        aria-expanded="false">
        Action <i class="mdi mdi-chevron-down"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu2" aria-labelledby="btnGroupDrop{{ $data->id }}">
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#info{{ $data->id }}"><span class="mdi mdi-information"></span> | Info</a></li>
        <li><a class="dropdown-item drpdwn" href="#" data-bs-toggle="modal" data-bs-target="#update{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
        <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | Delete</a></li>
    </ul>
</div>

{{-- Modal --}}
<div class="left-align truncate-text">
    {{-- Modal Info --}}
    <div class="modal fade" id="info{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Info Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 mb-2">
                            <div class="form-group">
                                <div><span class="fw-bold">Category :</span></div>
                                <span>
                                    <span>{{ $data->category }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Name Value :</span></div>
                                <span>
                                    <span>{{ $data->name_value }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div><span class="fw-bold">Code Format :</span></div>
                                <span>
                                    <span>{{ $data->code_format }}</span>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Dropdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dropdown.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="page" value="{{ $page }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="form-label">Category</label><label style="color: darkred">*</label>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <select class="form-select js-example-basic-single" style="width: 100%" name="category" required>
                                    <option value="" selected>-- Select Category --</option>
                                    <option disabled>──────────</option>
                                    @foreach( $category as $item)
                                        <option value="{{ $item->category }}" @if($data->category == $item->category) selected="selected" @endif> {{ $item->category }} </option>
                                    @endforeach
                                    <option disabled>──────────</option>
                                    <option class="font-weight-bold" value="NewCat">Add New Category</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <input type="text" name="addcategory" class="form-control" placeholder="Input New Category">
                            </div>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $("input[name='addcategory']").hide();
    
                                    $(document.body).on("change.select", "select[name^='category']", function () {
                                        var category = $(this).val();
    
                                        if(category=="NewCat"){
                                            $("input[name='addcategory']").show();
                                            $('input[name="addcategory"]').attr("required", true);
                                        }
                                        else{
                                            $("input[name='addcategory']").hide();
                                            $('input[name="addcategory"]').attr("required", false);
                                        }
                                    });
                                });
                            </script>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Name Value</label><label style="color: darkred">*</label>
                                <input class="form-control" name="name_value" type="text" value="{{ $data->name_value }}" placeholder="Input Name Value.." required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Code Format</label><label style="color: darkred">*</label>
                                <input class="form-control" name="code_format" type="text" value="{{ $data->code_format }}" placeholder="Input Code Format.." required>
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

    {{-- Modal Delete --}}
    <div class="modal fade" id="delete{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('dropdown.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <p class="text-center">Are You Sure To Delete This Dropdown?</p>
                            <p class="text-center"><b>{{ $data->name_value }}</b></p>
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