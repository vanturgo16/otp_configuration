@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div class="page-title-left">
                        <a href="{{ route('customer.index') }}" class="btn btn-primary waves-effect btn-label waves-light">
                            <i class="mdi mdi-arrow-left label-icon"></i> Back To List Master Customer
                        </a>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Customer</a></li>
                            <li class="breadcrumb-item active">Add</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        @include('layouts.alert')

        <!-- Modal for required fields -->
        <div class="modal fade" id="requiredModal" data-bs-backdrop="static" role="dialog" aria-labelledby="staticBackdropLabel">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Required Fields</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="text-center">
                                <h1>
                                    <span class="mdi mdi-alert"></span>
                                </h1>
                            </div>
                            <p class="text-center">Please fill in all required fields before adding to the table.</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('customer.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-center py-3">
                            <h5 class="mb-0"><b>Add New Customer</b></h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <label class="form-label">Customer Code</label>
                                    <br>
                                    <span class="badge bg-info text-white">Auto Generate</span>
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
                                    <label class="form-label">Customer Name</label><label style="color: darkred">*</label>
                                    <input class="form-control" name="name" type="text" value="" placeholder="Input Customer Name.." required>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Remarks</label>
                                    <input class="form-control" name="remark" type="text" value="" placeholder="Input Remarks..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Tax Number</label>
                                    <input class="form-control" name="tax_number" type="text" value="" placeholder="Input Tax Number..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Tax Code</label>
                                    <input class="form-control" name="tax_number" type="text" value="" placeholder="Input Tax Code..">
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Salesman</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_salesmen">
                                        <option value="" selected>--Select Salesman--</option>
                                        @foreach($salesmans as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Currency</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_currencies">
                                        <option value="" selected>--Select Currency--</option>
                                        @foreach($currencies as $data)
                                            <option value="{{ $data->id }}">{{ $data->currency }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">Term Payment</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="id_master_term_payments">
                                        <option value="" selected>--Select Term Payment--</option>
                                        @foreach($terms as $data)
                                            <option value="{{ $data->id }}">{{ $data->term_payment }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-6 mb-2">
                                    <label class="form-label">Term Payment</label><label style="color: darkred">*</label>
                                    <select class="form-select" data-trigger name="choices-single-default" name="id_master_term_payments">
                                        <option value="" selected>--Select Term Payment--</option>
                                        @foreach($terms as $data)
                                            <option value="{{ $data->id }}">{{ $data->term_payment }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-6 mb-2">
                                    <label class="form-label">Ppn</label><label style="color: darkred">*</label>
                                    <select class="form-select js-example-basic-single" style="width: 100%" name="ppn" required>
                                        <option value="" selected>--Select Ppn--</option>
                                        <option value="Include">Include</option>
                                        <option value="Exclude">Exclude</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="form-label">CBC</label><label style="color: darkred">*</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <div class="square-switch">
                                            <input type="checkbox" name="cbc" id="square-switch1" switch="none"/>
                                            <label for="square-switch1" data-on-label="Yes"
                                                data-off-label="No"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-0" id="custAddr">
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="background-color:azure">
                                                <h5 class="mb-0"><b><i class="fa fa-map" aria-hidden="true"></i> Customer Address</b></h5><span class="text-muted">&nbsp;(Click to Add)</span>
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row" id="dataAddress">
                                                    <div class="col-12 mb-2">
                                                        <label class="form-label">Address</label><label style="color: darkred">*</label>
                                                        <textarea id="address" class="form-control" name="address" rows="3" placeholder="Input Address.."></textarea>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Postal Code</label><label style="color: darkred">*</label>
                                                        <input id="postal_code" class="form-control" name="postal_code" type="text" value="" placeholder="Input Postal Code..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">City</label><label style="color: darkred">*</label>
                                                        <input id="city" class="form-control" name="city" type="text" value="" placeholder="Input City..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Province</label><label style="color: darkred">*</label>
                                                        <select id="id_master_provinces" class="form-select js-example-basic-single" style="width: 100%" name="id_master_provinces">
                                                            <option value="" selected>--Select Province--</option>
                                                            @foreach($provinces as $province)
                                                                <option value="{{ $province->id }}">{{ $province->province }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Country</label><label style="color: darkred">*</label>
                                                        <select id="id_master_countries" class="form-select js-example-basic-single" style="width: 100%" name="id_master_countries">
                                                            <option value="" selected>--Select Country--</option>
                                                            @foreach($countries as $country)
                                                                <option value="{{ $country->id }}">{{ $country->country }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Telephone</label>
                                                        <input class="form-control" name="telephone" type="text" value="" placeholder="Input Telephone..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Mobile Phone</label>
                                                        <input class="form-control" name="mobile_phone" type="text" value="" placeholder="Input Mobile Phone..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Fax</label>
                                                        <input class="form-control" name="fax" type="text" value="" placeholder="Input Fax..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Email</label>
                                                        <input class="form-control" name="email" type="email" value="" placeholder="Input Email..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Remark</label>
                                                        <input class="form-control" name="remarks" type="text" value="" placeholder="Input Remark..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Contact Person</label>
                                                        <input class="form-control" name="contact_person" type="text" value="" placeholder="Input Contact Person..">
                                                    </div>
                                                    <div class="col-6 mb-2">
                                                        <label class="form-label">Type Address</label><label style="color: darkred">*</label>
                                                        <select id="type_address" class="form-select js-example-basic-single" style="width: 100%" name="type_address">
                                                            <option value="" selected>--Select Type Address--</option>
                                                            <option value="Invoice">Invoice</option>
                                                            <option value="Shipping">Shipping</option>
                                                            <option value="Same As (Invoice, Shipping)">Same As (Invoice, Shipping)</option>
                                                        </select>
                                                    </div>
                                                </div>
            
                                                <div class="col-12 align-right">
                                                    <a type="button" id="resetAddress" class="btn btn-light">Reset</a>
                                                    <a type="button" id="addToTableBtn" class="btn btn-info waves-effect btn-label waves-light">
                                                        <i class="mdi mdi-playlist-plus label-icon"></i>Add To Table
                                                    </a>
                                                </div>
            
                                                <hr>
            
                                                <table id="addressTable" class="table table-bordered dt-responsive w-100" style="font-size: small">
                                                    <thead>
                                                        <tr>
                                                            <th class="align-middle text-center">Address</th>
                                                            <th class="align-middle text-center">Postal Code</th>
                                                            <th class="align-middle text-center">City</th>
                                                            <th class="align-middle text-center">Province</th>
                                                            <th class="align-middle text-center">Country</th>
                                                            <th class="align-middle text-center">Telephone</th>
                                                            <th class="align-middle text-center">Mobile Phone</th>
                                                            <th class="align-middle text-center">Fax</th>
                                                            <th class="align-middle text-center">Email</th>
                                                            <th class="align-middle text-center">Contact Person</th>
                                                            <th class="align-middle text-center">Remarks</th>
                                                            <th class="align-middle text-center">Type Address</th>
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

                            <input type="hidden" name="address_data" id="addressDataInput">

                            <hr>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const addToTableBtn = document.getElementById('addToTableBtn');
                                    const resetAddressBtn = document.getElementById('resetAddress');
                                    const form = document.getElementById('formadd');
                                    const addressTable = document.getElementById('addressTable');
                                    const dataAddress = document.getElementById('dataAddress');
                                    const custAddr = document.getElementById('custAddr');
                                    let rowIndex = 0; // Initialize rowIndex
                            
                                    addToTableBtn.addEventListener('click', function () {
                                        const address = dataAddress.querySelector('textarea[name="address"]').value;
                                        const postalCode = dataAddress.querySelector('input[name="postal_code"]').value;
                                        const city = dataAddress.querySelector('input[name="city"]').value;
                                        const province = dataAddress.querySelector('select[name="id_master_provinces"]');
                                        const country = dataAddress.querySelector('select[name="id_master_countries"]');
                                        const typeAddress = dataAddress.querySelector('select[name="type_address"]');
                            
                                        // Fetching selected option labels
                                        const provinceLabel = province.options[province.selectedIndex].text;
                                        const countryLabel = country.options[country.selectedIndex].text;
                                        const typeAddressLabel = typeAddress.options[typeAddress.selectedIndex].text;
                            
                                        const telephone = dataAddress.querySelector('input[name="telephone"]').value;
                                        const mobilePhone = dataAddress.querySelector('input[name="mobile_phone"]').value;
                                        const fax = dataAddress.querySelector('input[name="fax"]').value;
                                        const email = dataAddress.querySelector('input[name="email"]').value;
                                        const remarks = dataAddress.querySelector('input[name="remarks"]').value;
                                        const contactPerson = dataAddress.querySelector('input[name="contact_person"]').value;
                            
                                        if (address && postalCode && city && province.value && country.value && typeAddress.value) {
                                            // Create a new row
                                            const newRow = addressTable.insertRow();
                                            newRow.innerHTML = `
                                                <td class="align-middle">${address}</td>
                                                <td class="align-middle text-center">${postalCode}</td>
                                                <td class="align-middle text-center">${city}</td>
                                                <td class="align-middle text-center" data-value="${province.value}">${provinceLabel}</td>
                                                <td class="align-middle text-center" data-value="${country.value}">${countryLabel}</td>
                                                <td class="align-middle text-center">${telephone}</td>
                                                <td class="align-middle text-center">${mobilePhone}</td>
                                                <td class="align-middle text-center">${fax}</td>
                                                <td class="align-middle text-center">${email}</td>
                                                <td class="align-middle text-center">${contactPerson}</td>
                                                <td class="align-middle text-center">${remarks}</td>
                                                <td class="align-middle text-center" data-value="${typeAddress.value}">${typeAddressLabel}</td>
                                                <td class="align-middle text-center"><button class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                                            `;
                            
                                            addressTable.scrollIntoView({ behavior: 'smooth' });
                            
                                            // Reset form values
                                            $('#dataAddress input, #dataAddress textarea').val('');
                                            $('#dataAddress select').val(null).trigger('change');
                                            updateAddressData();
                                        } else {
                                            $('#requiredModal').modal('show');
                                            // alert('Please fill in all required fields before adding to the table.');
                                        }
                                    });
                            
                                    resetAddressBtn.addEventListener('click', function () {
                                        custAddr.scrollIntoView({ behavior: 'smooth' });
                                        $('#dataAddress input, #dataAddress textarea').val('');
                                        $('#dataAddress select').val(null).trigger('change');
                                    });
                            
                                    // Function to remove row
                                    window.removeRow = function (btn) {
                                        const row = btn.closest('tr');
                                        row.remove();
                                        updateAddressData();
                                    };
                            
                                    // Function to update hidden input with table data
                                    function updateAddressData() {
                                        const rows = Array.from(addressTable.querySelectorAll('tr'));
                                        const data = rows.map(row => {
                                            const cells = Array.from(row.children);
                                            return {
                                                address: cells[0].textContent.trim(),
                                                postal_code: cells[1].textContent.trim(),
                                                city: cells[2].textContent.trim(),
                                                province: {
                                                    label: cells[3].textContent.trim(), // Get label from table
                                                    value: cells[3].dataset.value // Get value from dataset
                                                },
                                                country: {
                                                    label: cells[4].textContent.trim(), // Get label from table
                                                    value: cells[4].dataset.value // Get value from dataset
                                                },
                                                telephone: cells[5].textContent.trim(),
                                                mobile_phone: cells[6].textContent.trim(),
                                                fax: cells[7].textContent.trim(),
                                                email: cells[8].textContent.trim(),
                                                contact_person: cells[9].textContent.trim(),
                                                remarks: cells[10].textContent.trim(),
                                                type_address: {
                                                    label: cells[11].textContent.trim(), // Get label from table
                                                    value: cells[11].dataset.value // Get value from dataset
                                                }
                                            };
                                        });
                                        document.getElementById('addressDataInput').value = JSON.stringify(data);
                                    }
                                });
                            </script>
                            
                            
                            <div class="row">
                                <div class="col-12 align-center">
                                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb">
                                        <i class="mdi mdi-update label-icon"></i>Add New Customer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
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
@endsection