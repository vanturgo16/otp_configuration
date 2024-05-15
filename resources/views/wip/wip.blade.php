<div class="card-body p-0" id="headForm">
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="background-color:azure">
                    <h5 class="mb-0"><b>WIP Ref</b></h5><span class="text-muted">&nbsp;(Click to Add)</span>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row" id="dataTables">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Wip Material</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_wips_material">
                                    <option value="" selected>--Select Wip Material--</option>
                                    @foreach($wipmaterials as $item)
                                        <option value="{{ $item->id }}">{{ $item->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Qty</label>
                                <input class="form-control" name="qty" type="text" value="" placeholder="Input qty..">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Unit</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="master_units_id">
                                    <option value="" selected>--Select Unit--</option>
                                    @foreach($units as $item)
                                        <option value="{{ $item->id }}">{{ $item->unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Qty Results</label>
                                <input class="form-control" name="qty_results" type="text" value="" placeholder="Input Qty Results..">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 align-right">
                        <a type="button" id="resetTableBtn" class="btn btn-light">Reset</a>
                        <a type="button" id="addToTableBtn" class="btn btn-info waves-effect btn-label waves-light">
                            <i class="mdi mdi-playlist-plus label-icon"></i>Add To Table
                        </a>
                    </div>

                    <hr>

                    <table id="storeTable" class="table table-bordered dt-responsive w-100" style="font-size: small">
                        <thead>
                            <tr>
                                <th class="align-middle text-center">WIP</th>
                                <th class="align-middle text-center">Qty</th>
                                <th class="align-middle text-center">Units</th>
                                <th class="align-middle text-center">Qty Results</th>
                                <th class="align-middle text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="dataInput" id="dataInput">
<hr>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addToTableBtn = document.getElementById('addToTableBtn');
        const resetTableBtn = document.getElementById('resetTableBtn');
        const form = document.getElementById('formadd');
        const storeTable = document.getElementById('storeTable');
        const dataTables = document.getElementById('dataTables');
        const headForm = document.getElementById('headForm');
        let rowIndex = 0; // Initialize rowIndex

        addToTableBtn.addEventListener('click', function () {
            const wips = dataTables.querySelector('select[name="id_master_wips_material"]');
            const qty = dataTables.querySelector('input[name="qty"]').value;
            const unit = dataTables.querySelector('select[name="master_units_id"]');
            const qty_results = dataTables.querySelector('input[name="qty_results"]').value;

            // Fetching selected option labels
            const wipsLabel = wips.options[wips.selectedIndex].text;
            const unitLabel = unit.options[unit.selectedIndex].text;

            if (wips.value && qty && unit.value && qty_results) {
                // Create a new row
                const newRow = storeTable.insertRow();
                newRow.innerHTML = `
                    <td class="align-middle text-center" data-value="${wips.value}">${wipsLabel}</td>
                    <td class="align-middle text-center">${qty}</td>
                    <td class="align-middle text-center" data-value="${unit.value}">${unitLabel}</td>
                    <td class="align-middle text-center">${qty_results}</td>
                    <td class="align-middle text-center"><button class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                `;

                storeTable.scrollIntoView({ behavior: 'smooth' });

                // Reset form values
                $('#dataTables input, #dataTables textarea').val('');
                $('#dataTables select').val(null).trigger('change');
                updateTableData();
            } else {
                $('#requiredModal').modal('show');
            }
        });

        resetTableBtn.addEventListener('click', function () {
            headForm.scrollIntoView({ behavior: 'smooth' });
            $('#dataTables input, #dataTables textarea').val('');
            $('#dataTables select').val(null).trigger('change');
        });

        // Function to remove row
        window.removeRow = function (btn) {
            const row = btn.closest('tr');
            row.remove();
            updateTableData();
        };

        // Function to update hidden input with table data
        function updateTableData() {
            const rows = Array.from(storeTable.querySelectorAll('tr'));
            const data = rows.map(row => {
                const cells = Array.from(row.children);
                return {
                    wips: {
                        label: cells[0].textContent.trim(),
                        value: cells[0].dataset.value
                    },
                    qty: cells[1].textContent.trim(),
                    unit: {
                        label: cells[2].textContent.trim(),
                        value: cells[2].dataset.value
                    },
                    qty_result: cells[3].textContent.trim(),
                };
            });
            document.getElementById('dataInput').value = JSON.stringify(data);
        }
    });
</script>