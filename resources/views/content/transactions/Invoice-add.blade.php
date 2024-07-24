@extends('layouts/contentNavbarLayout')

@section('title', 'Invoice')

@section('page-script')

<script>
  function getsodetails() {
    var soNumber = document.getElementById("so").value;

    $.ajax({
        url: "{{ route('transaction.getsodetails') }}",
        type: 'GET',
        data: { so: soNumber },
        success: function(response) {
            console.log('Response from server:', response); // Debugging log

            // Check if response is a JSON string
            var data;
            try {
                data = Array.isArray(response) ? response : JSON.parse(response);
            } catch (e) {
                console.error('Failed to parse response:', e);
                return;
            }

            // Clear any existing rows
            document.getElementById('dynamic-rows').innerHTML = '';

            // Process each row in the data
            data.forEach(function(item, index) {
                // Split the item string by '@'
                var values = item.split('@'); // Changed item[0] to item

                if (values.length >= 8) { // Ensure there are enough values
                    var transfer = values[0];
                    var code = values[1];
                    var descriptionText = values[2];
                    var quantity = values[3];
                    var unit = values[4];
                    var price = values[5];
                    var taxTypeText = values[6];
                    var taxValue = values[7];

                    // Add new row
                    addNewRow(index + 1, transfer, descriptionText, code, quantity, unit, price, taxTypeText, taxValue);
                } else {
                    console.error('Invalid data format:', values);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
  }

  function addNewRow(rowNumber, transfer, descriptionText, code, quantity, unit, price, taxTypeText, taxValue) {
    let newRow = document.createElement('tr');
    newRow.classList.add('dynamic-row', 'mb-1');
    newRow.id = `dynamic-row-${rowNumber}`;
    newRow.innerHTML = `

        <td>
            <select id="category${rowNumber}" name="category[]" class="form-control" required>
                <option value="${transfer}">${transfer}</option>
            </select>
        </td>
        <td>
            <select id="description${rowNumber}" name="description[]" class="form-control" required>
                <option value="${descriptionText}">${descriptionText}</option>
            </select>
        </td>
        <td>
            <input type="text" id="code${rowNumber}" name="code[]" class="form-control" value="${code}" readonly/>
        </td>
        <td>
            <input type="number" id="quantity${rowNumber}" name="quantity[]" class="form-control" value="${quantity}" required/>
        </td>

        <td>
            <input type="number" id="price${rowNumber}" name="price[]" class="form-control" value="${price}" required/>
        </td>
        <td>
            <select id="taxType${rowNumber}" name="taxType[]" class="form-control" required>
                <option value="${taxTypeText}">${taxTypeText}</option>
            </select>
        </td>
        <td>
            <input type="number" id="tax${rowNumber}" name="tax[]" class="form-control" value="${taxValue}" readonly/>
        </td>
    `;

    document.getElementById('dynamic-rows').appendChild(newRow);
  }
</script>

@endsection




@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Sales Module /</span> Invoice
</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header text-center"><strong style="font-size:25px">Invoice</strong></h5>
            <hr class="my-0">
            <div class="card-body">
                <form action="{{ route('transctions-SalesOrder-store') }}" id="formSalesOrder" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="date" class="form-label"><strong>Date*</strong></label>
                        </div>
                        <div class="col-md-2">
                            <input type="date" id="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" readonly style="border: none;"/>
                        </div>
                        <div class="col-md-2">
                            <label for="id" class="form-label"><strong>Distributor ID*</strong></label>
                        </div>
                        <div class="col-md-2">
                            <select id="id" name="id" class="form-control" onchange="getdist(); getso();">
                                <option value="">-select-</option>
                                @foreach($distinctVendors as $vendor)
                                    <option value="{{ $vendor->vendorid }}">{{ $vendor->vendorid }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="dist" class="form-label"><strong>Distributor*</strong></label>
                        </div>
                        <div class="col-md-2">
                            <select id="dist" name="dist" class="form-control" onchange="getid(); getso();">
                                <option value="">-select-</option>
                                @foreach($distinctVendors as $vendor)
                                    <option value="{{ $vendor->vendor }}">{{ $vendor->vendor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="so" class="form-label"><strong>SO*</strong></label>
                        </div>
                        <div class="col-md-2">
                            <select id="so" name="so" class="form-control" onchange="getsodetails();">
                                <option value="">-select-</option>
                                @foreach($distinctVendors as $po)
                                    <option value="{{ $po->po }}">{{ $po->po }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="po_no" class="form-label"><strong>Invoice</strong></label>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="po_no" name="po_no" class="form-control" value="{{ $inv }}" readonly/>
                        </div>
                        <div class="col-md-2">
                            <label for="book_invoice" class="form-label"><strong>Book Invoice</strong></label>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="book_invoice" name="book_invoice" class="form-control" value=""/>
                        </div>
                    </div>

                    <div class="row mb-4 text-center">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Code</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Tax</th>
                                    <th>Tax value</th>
                                </tr>
                            </thead>
                            <tbody id="dynamic-rows">
                                <tr class="dynamic-row mb-1" id="dynamic-row-1">
                                    <td>
                                        <select id="category1" name="category[]" class="form-control" required>
                                            <option value="">-Select-</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="description1" name="description[]" class="form-control" required>
                                            <option value="">-Select-</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="code1" name="code[]" class="form-control" readonly/>
                                    </td>
                                    <td>
                                        <input type="number" id="quantity1" name="quantity[]" class="form-control" required/>
                                    </td>
                                    <td>
                                        <input type="number" id="price1" name="price[]" class="form-control" required/>
                                    </td>
                                    <td>
                                        <select id="taxType1" name="taxType[]" class="form-control">
                                            <option value="">-Select-</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" id="tax1" name="tax[]" class="form-control" readonly/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3 offset-md-1">
                            <label class="form-label"><strong>Total Quantity (In Tonnage)</strong></label>
                            <input type="text" name="tquantity" id="tquantity" class="form-control" readonly value="0"/>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><strong>Grand Total</strong></label>
                            <input type="text" name="total" id="total" class="form-control" readonly value="0"/>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label"><strong>GSTIN</strong></label>
                            <input type="text" name="gstin" id="gstin" class="form-control" readonly value="0"/>
                        </div>

                        <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label"><strong>Basic Total</strong></label>
                            <input type="text" name="gstin" id="gstin" class="form-control" readonly value="0"/>
                        </div>

                        <div class="row mb-4">
    <!-- TCS Tax Radio Buttons -->
    <div class="col-md-9">
        <div class="form-group">
        <label class="form-label"><strong>TCS Rate (%)</strong></label>
            <strong>N/A</strong>
            <input type="radio" name="tcs_tax" id="tcs_tax" onclick="apply_tcs_tax(this.id)" value="0" checked>
            @foreach($result_data as $key => $tcs)
                <strong>{{ $tcs['tax'] }}</strong>
                <input type="radio" name="tcs_tax" id="tcs_tax{{ $key }}" value="{{ $tcs['tax'] }}">
            @endforeach
        </div>
    </div>

    <div class="col-md-3">
                            <label class="form-label"><strong>Grand Total</strong></label>
                            <input type="text" name="gstin" id="gstin" class="form-control" readonly value="0"/>
                        </div>


</div>


                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label"><strong>Narration</strong></label>
                            <textarea name="narration" id="narration" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="reset" class="btn btn-danger" onclick="window.location.href=''">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection




<script>
  function getdist() {

var id = document.getElementById('id');
  var distributor = document.getElementById('dist');

  var selectedIndex = id.selectedIndex;

  distributor.selectedIndex = selectedIndex;


}
function getid() {

var id = document.getElementById('id');
  var distributor = document.getElementById('dist');

  var selectedIndex = distributor.selectedIndex;

  id.selectedIndex = selectedIndex;


}
function getso() {
  var soDropdown = document.getElementById("so");
  soDropdown.options.length = 0;
  var defaultOption = new Option("-select-", "");
  soDropdown.options.add(defaultOption.cloneNode(true));

  var id = document.getElementById("id").value;
  var soSet = new Set();

  $.ajax({
    url: "{{ route('transaction.getsonumber') }}",
    type: 'GET',
    data: { id: id },
    success: function(response) {
      var response1 = JSON.parse(response)

      for (var i = 0; i < response1.length; i++) {
        if (!soSet.has(response1[i])) {
          soSet.add(response1[i]);
          var soOption = new Option(response1[i], response1[i]);
          soDropdown.options.add(soOption);
        }
      }
    },
    error: function(xhr, status, error) {
      console.error('AJAX Error:', error);
    }
  });
}




</script>


