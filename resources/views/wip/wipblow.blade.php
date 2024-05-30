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
                                <label class="form-label">Raw Material</label>
                                <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_raw_materials">
                                    <option value="" selected>--Select Raw Material--</option>
                                    @foreach($rawmaterials as $item)
                                        <option value="{{ $item->id }}">{{ $item->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Weight</label>
                                <input class="form-control" name="weights" type="text" value="" placeholder="Input Weight..">
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
                                <th class="align-middle text-center">Raw Material</th>
                                <th class="align-middle text-center">Weight</th>
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
            const raws = dataTables.querySelector('select[name="id_master_raw_materials"]');
            const weight = dataTables.querySelector('input[name="weights"]').value;

            // Fetching selected option labels
            const rawsLabel = raws.options[raws.selectedIndex].text;

            if (raws.value && weight) {
                // Create a new row
                const newRow = storeTable.insertRow();
                newRow.innerHTML = `
                    <td class="align-middle text-center" data-value="${raws.value}">${rawsLabel}</td>
                    <td class="align-middle text-center">${weight}</td>
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
                    raws: {
                        label: cells[0].textContent.trim(),
                        value: cells[0].dataset.value
                    },
                    weights: cells[1].textContent.trim(),
                };
            });
            document.getElementById('dataInput').value = JSON.stringify(data);
        }
    });
</script>