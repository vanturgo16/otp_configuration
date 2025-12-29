@if($data->no_lmts)
    <button class="btn btn-sm btn-info waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#detail{{ $data->id }}" title="Hold Detail">
        <i class="mdi mdi-information label-icon"></i> Detail
    </button>
@else
    <button class="btn btn-sm btn-warning waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#hold{{ $data->id }}" title="Hold">
        <i class="mdi mdi-pause-circle-outline label-icon"></i> Hold
    </button>
@endif

{{-- Modal --}}
<div class="left-align truncate-text">
    @if($data->no_lmts)
        {{-- Modal Create HOLD --}}
        <div class="modal fade" id="detail{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Detail HOLD</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4" style="max-height: 65vh; overflow-y: auto;">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">LMTS Number</label>
                                <br>
                                <span class="badge bg-info text-white">{{ $data->no_lmts ?? '-' }}</span>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">HOLD at</label>
                                <br>
                                <span>{{ $data->lmts_date ?? '-' }}</span>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">LMTS Disposisi</label>
                                <br>
                                @php
                                    $disposisi = json_decode($data->lmts_disposisi ?? '{}', true);
                                    $labels = [];

                                    if (!empty($disposisi['is_return'])) $labels[] = 'Return';
                                    if (!empty($disposisi['is_repair'])) $labels[] = 'Repair';
                                    if (!empty($disposisi['is_scrap']))  $labels[] = 'Scrap';
                                @endphp
                                <span>{{ count($labels) ? implode(', ', $labels) : '-' }}</span>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">LMTS Remarks</label>
                                <br>
                                <span>{{ $data->lmts_remarks ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Modal Create HOLD --}}
        <div class="modal fade" id="hold{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">HOLD</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('historystock.hold') }}" id="formHold{{ $data->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_good_receipt_notes" value="{{ $data->id_grn }}" required>
                        <input type="hidden" name="id_good_receipt_notes_details" value="{{ $data->id_grn_detail }}" required>
                        <input type="hidden" name="id_detail_grn_detail" value="{{ $data->id }}" required>
                        <input type="hidden" name="receipt_number" value="{{ $data->receipt_number }}" required>
                        <input type="hidden" name="type_product" value="{{ $data->type_product }}" required>
                        <input type="hidden" name="status" value="0" required>

                        <div class="modal-body p-4" style="max-height: 65vh; overflow-y: auto;">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">LMTS Number</label><label style="color: darkred">*</label>
                                    <br>
                                    <span class="badge bg-info text-white">Auto Generate</span>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Date</label><label style="color: darkred">*</label>
                                    <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" readonly required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Product</label><label style="color: darkred">*</label>
                                    <input type="hidden" name="id_master_products" value="{{ $product->id }}" required>
                                    <input type="text" class="form-control" name="description" value="{{ $product->description }}" readonly required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Qty</label><label style="color: darkred">*</label>
                                    <input type="hidden" name="total_glq" value="{{ $data->glq }}" required>
                                    <input type="text" class="form-control" name="qty" value="{{ $data->qty }}" readonly required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Unit</label><label style="color: darkred">*</label>
                                    <input type="hidden" name="id_master_units" value="{{ $data->master_units_id }}" required>
                                    <input type="text" class="form-control" name="unit" value="{{ $data->unit_code }}" readonly required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Lot Number</label><label style="color: darkred">*</label>
                                    <input type="text" class="form-control" name="lot_number" value="{{ $data->lot_number }}" readonly required>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Ext Lot Number</label>
                                    <input type="text" class="form-control" name="external_lot" value="{{ $data->ext_lot_number ?? '-' }}" readonly>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Remarks</label><label style="color: darkred">*</label>
                                    <textarea type="text" class="form-control" name="remarks" required></textarea>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Disposisi</label><label style="color: darkred">*</label>
                                        <div class="card p-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="button_active[]" value="is_return">
                                                <label class="form-check-label">Return</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="button_active[]" value="is_repair">
                                                <label class="form-check-label">Repair</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="button_active[]" value="is_scrap">
                                                <label class="form-check-label">Scrap</label>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            const checkboxes = document.querySelectorAll('input[name="button_active[]"]');
                                            function validateCheckboxes() {
                                                const anyChecked = [...checkboxes].some(c => c.checked);
                                                checkboxes.forEach(c => c.required = !anyChecked);
                                            }
                                            validateCheckboxes();
                                            checkboxes.forEach(cb => cb.addEventListener('change', validateCheckboxes));
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning waves-effect btn-label waves-light" id="btnHold{{ $data->id }}"><i class="mdi mdi-pause-circle-outline label-icon"></i>Hold</button>
                        </div>
                    </form>
                    <script>
                        $(document).ready(function() {
                            let idList = "{{ $data->id }}";
                            $('#formHold' + idList).submit(function(e) {
                                if (!$('#formHold' + idList).valid()){
                                    e.preventDefault();
                                } else {
                                    $('#btnHold' + idList).attr("disabled", "disabled");
                                    $('#btnHold' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    @endif
</div>