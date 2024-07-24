@extends('layouts/contentNavbarLayout')

@section('title', 'Sales Order')

@section('page-script')

<script>


  function getpodetails() {
    var poNumber = document.getElementById("po").value;

    $.ajax({
        url: "{{route('transaction.getpo')}}",
        type: 'GET',
        data: { po: poNumber },
        success: function(response) {
            // Log response for debugging
            console.log('Response:', response);

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
                var values = item[0].split('@');

                if (values.length >= 8) { // Ensure there are enough values
                    var transfer = values[0];
                    var code = values[1];
                    var descriptionText = values[2];
                    var rate = values[3];
                    var unit = values[4];
                    var price = values[5];
                    var taxTypeText = values[6];
                    var taxValue = values[7];

                    // Add new row
                    addNewRow(index + 1, transfer, descriptionText, code, rate, unit, price, taxTypeText, taxValue);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });

    function addNewRow(rowNumber, transfer, descriptionText, code, rate, unit, price, taxTypeText, taxValue) {
        let newRow = document.createElement('tr');
        newRow.classList.add('dynamic-row', 'mb-1');
        newRow.id = `dynamic-row-${rowNumber}`;
        newRow.innerHTML = `
            <td>
                <input type="checkbox" id="check${rowNumber}" name="check[]" onchange="gettotal();" checked>
            </td>

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
                <input type="number" id="quantity${rowNumber}" name="quantity[]" class="form-control" value="${rate}" required/>
            </td>
                   <td>
                <input type="number" id="enterquantity${rowNumber}" name="enteredquantity[]" class="form-control" value="" required onkeyup = "gettotal();checkdiff();"/>
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
  }
</script>

@endsection



@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Sales Module /</span> Sales Order
</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header text-center"><strong style="font-size:25px">Sales Order</strong></h5>
            <hr class="my-0">
            <div class="card-body">
                <form action="{{route('transctions-SalesOrder-store')}}" id="formSalesOrder" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="so" class="form-label"><strong>S.O*</strong></label>
                        </div>
                        <div class="col-md-4">
                        <input type="text" id="so" name="so" class="form-control" value="{{$po}}" readonly style="border: none;" />

                        </div>
                        <div class="col-md-2">
                            <label for="date" class="form-label"><strong>Date*</strong></label>
                        </div>
                        <div class="col-md-4">
    <input type="date" id="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" readonly  style="border: none;"/>
</div>

                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="id" class="form-label"><strong>ID*</strong></label>
                        </div>
                        <div class="col-md-4">
                        <select id="id" name="id" class="form-control" onchange = "getdist();getpo();getso();">
                          <option value = ''>-select-</option>
                          @foreach (array_keys($data) as $key)
                         <option value="{{ $key }}">{{ $key }}</option>
                           @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="distributor" class="form-label"><strong>Distributor*</strong></label>
                        </div>
                        <div class="col-md-4">
                        <select id="distributor" name="distributor" class="form-control" onchange = "getid();getpo();">
                          <option value = ''>-select-</option>
                          @foreach ($data as $key)
                          @php
                          $value = explode('@',$key)[0];
                          $po = explode('@',$key)[1];
                          @endphp
                         <option value="{{ $value }}">{{ $value }}</option>
                           @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2">

                        </div>
                        <div class="col-md-4">

                        </div>

                        <div class="col-md-4">

                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="po_no" class="form-label"><strong>P.O. No</strong></label>
                        </div>
                        <div class="col-md-4">
                        <select id="po" name="po" class="form-control" onchange="getpodetails();">
                          <option value = ''>-select-</option>
                        </select>
                        </div>
                    </div>
                    <div class="row mb-4 text-center">
                        <table class="table">
                            <thead>
                                <tr>
                                <th>
  <input type="checkbox" id="checkall" name="checkall[]" onchange="toggleCheckboxes();" checked>
</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Code</th>
                                    <th>Quantity</th>
                                    <th>Entered Quantity</th>
                                    <th>Price</th>
                                    <th>Tax Type</th>
                                    <th>Tax</th>
                                </tr>
                            </thead>
                            <tbody id="dynamic-rows">
                                <tr class="dynamic-row mb-1" id="dynamic-row-1">
                                <td>
                <input type="checkbox" id="check" name="check[]" onchange="gettotal();">
            </td>
                                    <td>
                                        <select id="category1" name="category[]" class="form-control" required>
                                            <option value="">-Select-</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select id="description1" name="description[]" class="form-control" required>
                                            <option value="">-Select-</option>
                                            <!-- Add other options dynamically -->
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="code1" name="code[]" class="form-control" readonly/>
                                    </td>
                                    <td>
                                        <input type="number" id="quantity1" name="quantity[]" class="form-control" required/>
                                    </td>
                                    <td>
                                        <input type="number" id="eneteredquantity1" name="enteredquantity[]" class="form-control" onkeyup = "gettotal();" required/>
                                    </td>
                                    <td>
                                        <input type="number" id="price1" name="price[]" class="form-control" required/>
                                    </td>
                                    <td>
                                        <select id="taxType1" name="taxType[]" class="form-control">
                                            <option value="">-Select-</option>
                                            <!-- Add other options as needed -->
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" id="tax1" name="tax[]" class="form-control" readonly/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-4 text-center">
                        <div class="col-md-3 offset-md-1">
                            <label class="form-label"><strong>Total Quantity (In Tonnage)</strong></label>
                            <input type="text" name = "tquantity" id = "tquantity" class="form-control" readonly value="0"/>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><strong>Grand Total</strong></label>
                            <input type="text" name = "total" id = "total" class="form-control" readonly value="0"/>
                        </div>
                    </div>

                    <div class="text-center">

                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="reset" class="btn btn-danger" onclick="window.location.href='{{ route('transctions-SalesOrder')}}'">Cancel</button

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
  var distributor = document.getElementById('distributor');

  var selectedIndex = id.selectedIndex;

  distributor.selectedIndex = selectedIndex;


}
function getid() {

var id = document.getElementById('id');
  var distributor = document.getElementById('distributor');

  var selectedIndex = distributor.selectedIndex;

  id.selectedIndex = selectedIndex;


}
function getpo()
{
  document.getElementById("po").options.length = 1;
  var data = <?php echo json_encode($data); ?>;
  var po = document.getElementById("po");
  var id = document.getElementById("id").value;
  if (data.hasOwnProperty(id)) {
     var x =  data[id].split("@")[1];
     var y = x.split("&");
     for(var i =0;i<y.length;i++)
     {
      var poOption = new Option(y[i], y[i]);
      po.add(poOption);
     }
    }
}
function gettotal() {

    var tquantity = 0;
    var sum = 0;
    var sum1 = 0;
    var index = $('[name="category[]"]').length;

    for (var i = 1; i <= index; i++) {

        if (document.getElementById("check" + i).checked) { // Check if the checkbox exists and is checked
            var enteredQuantity = parseFloat(document.getElementById("enterquantity" + i).value);

            var price = parseFloat(document.getElementById("price" + i).value);
            var tax = parseFloat(document.getElementById("tax" + i).value) || 0; // Ensure tax is treated as a float and defaults to 0 if empty

            if (isNaN(enteredQuantity)) {
                enteredQuantity = 0;
                document.getElementById("enterquantity" + i).value = 0;
            }

            tquantity += enteredQuantity;

            var x = enteredQuantity * price;
            sum += x;

            if (tax > 0) {
                var z = x * (tax / 100);
                var z1 = z + x;
                sum1 += z1;
            } else {
                sum1 += x;
            }
        }
    }

    document.getElementById("tquantity").value = tquantity.toFixed(2);
    document.getElementById("total").value = sum1.toFixed(2);
}

function toggleCheckboxes() {
    var index = $('[name="category[]"]').length;

    for (var i = 1; i <= index; i++) {
      if (document.getElementById("checkall").checked) {
        document.getElementById("check" + i).checked = true;
      } else {
        document.getElementById("check" + i).checked = false;
      }
    }
    gettotal(); // Assuming gettotal() updates totals based on checkbox states
  }


  function getso() {
    var id = document.getElementById("id").value;

    $.ajax({
        url: "{{route('transaction.getso')}}",
        type: 'GET',
        data: { id: id },
        success: function(response) {
          document.getElementById("so").value = response;
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
  }

  function checkdiff() {
    var index = $('[name="category[]"]').length;

    for (var i = 1; i <= index; i++) {

      if(parseFloat(document.getElementById("enterquantity"+i).value)>parseFloat(document.getElementById("quantity"+i).value))
    {

      alert("Enter quantity should always be less then avaliable quantity");
      document.getElementById("enterquantity"+i).value = '0';
      document.getElementById("tquantity").value = '';
      document.getElementById("total").value = '';
      return false;
    }

    }
  }


</script>
