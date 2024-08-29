@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Sales Order')

@section('page-script')


<script>
    var pono = @json($pono);
    var salesOrderDetails = @json($salesOrderDetails);

    // Function to create new row
    function createNewRow(rowNumber, details) {
        let newRow = document.createElement('tr');
        newRow.classList.add('dynamic-row', 'mb-1');
        newRow.id = `dynamic-row-${rowNumber}`;
        newRow.innerHTML = `
            <td>
                <input type="checkbox" id="check${rowNumber}" name="check[]" onchange="gettotal();" checked>
            </td>
            <td>
                <select id="category${rowNumber}" name="category[]"  style="border: none;pointer-events: none;-webkit-appearance: none;"  required>
                    <option value="${details.category}">${details.category}</option>
                </select>
            </td>
            <td>
                <select id="description${rowNumber}" name="description[]"  style="border: none;pointer-events: none;-webkit-appearance: none;" required>
                    <option value="${details.description}">${details.description}</option>
                </select>
            </td>
            <td>
                <input type="text" id="code${rowNumber}" name="code[]"  style="border: none;width: 100px;" value="${details.code}" readonly/>
            </td>
            <td>
                <input type="number" id="quantity${rowNumber}" readonly  name="quantity[]" style="border: none;width: 70px;"  value="${details.quantity}" required/>
            </td>
            <td>
                <input type="number" id="enteredquantity${rowNumber}" class="form-control" name="enteredquantity[]" style="width: 70px;"  value="${details.squantity}" required onkeyup="gettotal();checkdiff();"/>
            </td>
            <td>
                <input type="number" id="price${rowNumber}" readonly name="price[]" style="border: none;width: 70px;"   value="${details.price}" required/>
            </td>
              <td>
                <input type="number" id="taxableprice${rowNumber}" readonly name="taxableprice[]"  style="border: none; width: 70px;" value="${details.taxableprice}" required/>
            </td>
                 <td>
                <input type="number" id="basic_total${rowNumber}" readonly name="basic_total[]"  value="${details.basic_total}"  style="border: none;  width: 100px;" required/>
            </td>
            <td>
                <select id="taxType${rowNumber}" name="taxType[]"  style="border: none;pointer-events: none;-webkit-appearance: none;" required>
                    <option value="${details.taxcode}">${details.taxcode}</option>
                </select>
            </td>
            <td>
                <input type="number" id="tax${rowNumber}" name="tax[]" style="border: none;width: 50px;"  value="${details.taxvalue}" readonly/>
            </td>
             <td>
                <input type="number" id="taxamount${rowNumber}" name="taxamount[]" style="border: none;width: 70px;"  value="${details.taxamount}" readonly/>
            </td>
            <td>
                <input type="number" id="totalamount${rowNumber}" name="totalamount[]" style="border: none;width: 70px;"  value="${details.total_amount}" readonly/>
            </td>
        `;
        document.getElementById('dynamic-rows').appendChild(newRow);
    }

    // Call getpodetails on page load
    document.addEventListener('DOMContentLoaded', function() {
        salesOrderDetails.forEach((details, index) => {
            createNewRow(index + 1, details);
        });
        getpodetails();
    });
</script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Sales Module /</span> Edit Sales Order
</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header text-center"><strong style="font-size:25px">Edit Sales Order</strong></h5>
            <hr class="my-0">
            <div class="card-body">
                <form action="{{route('transctions-SalesOrder.update', ['code' => $id])}}" id="formSalesOrder" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="so" class="form-label"><strong>S.O*</strong></label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="so" name="so" class="form-control" value="{{$id}}" readonly />
                        </div>
                        <div class="col-md-2">
                            <label for="date" class="form-label"><strong>Date*</strong></label>
                        </div>
                        <div class="col-md-4">
                            <input type="date" id="date" name="date" class="form-control" value="2024-07-08" readonly />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="id" class="form-label"><strong>ID*</strong></label>
                        </div>
                        <div class="col-md-4">
                            <select id="id" name="id" class="form-control" onchange="getdist(); getpo();">
                                <option value='{{$vendorid}}'>{{$vendorid}}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="distributor" class="form-label"><strong>Distributor*</strong></label>
                        </div>
                        <div class="col-md-4">
                            <select id="distributor" name="distributor" class="form-control" onchange="getid();">
                                <option value='{{$vendor}}'>{{$vendor}}</option>
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
                            <input type="text" id="po" name="po" class="form-control" value="{{$pono}}" readonly>
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
                                    <th>code</th>
                                    <th>PO Quantity</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Taxable price</th>
                                    <th>Basic total</th>
                                    <th>Tax Type</th>
                                    <th>Tax</th>
                                    <th>Tax Amount</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody id="dynamic-rows">
                                <!-- Dynamic rows will be appended here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-4 text-center">
                        <div class="col-md-3 offset-md-1">
                            <label class="form-label"><strong>Total Quantity (In Tonnage)</strong></label>
                            <input type="text" name="tquantity" id="tquantity" class="form-control"value="{{ $tquantity }}" readonly value="0"/>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><strong>Grand Total</strong></label>
                            <input type="text" name="total" id="total" class="form-control" value="{{ $total }}" readonly value="0"/>
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

  function getpo() {
    var data = @json($data);
    var po = document.getElementById("po");
    var id = document.getElementById("id").value;
    if (data.hasOwnProperty(id)) {
      var x = data[id].split("@")[1];
      var y = x.split("&");
      for (var i = 0; i < y.length; i++) {
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
        var enteredQuantity = parseFloat(document.getElementById("enteredquantity" + i).value);

        var price = parseFloat(document.getElementById("price" + i).value);
        
        var taxable  = parseFloat(document.getElementById("taxableprice" + i).value);
            
        var tax1 = parseFloat(document.getElementById("tax" + i).value) // Ensure tax is treated as a float and defaults to 0 if empty

      


            if (isNaN(enteredQuantity)) {
            enteredQuantity = 0;
            document.getElementById("enteredquantity" + i).value = 0;
        }

        tquantity += enteredQuantity;

        var x = enteredQuantity * price;
        
        var z = ((x *tax1) /(100 + tax1));
 



        sum += x;

sum1 += x;

var basict = parseFloat(taxable * enteredQuantity).toFixed(2);

document.getElementById("taxamount" + i).value = z.toFixed(2);
document.getElementById("totalamount" + i).value = x;

document.getElementById("basic_total" + i).value = basict;

        

        
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

function checkdiff() {
    var index = $('[name="category[]"]').length;

    for (var i = 1; i <= index; i++) {

      if(parseFloat(document.getElementById("enteredquantity"+i).value)>parseFloat(document.getElementById("quantity"+i).value))
    {

      alert("Enter quantity should always be less then avaliable quantity");
      document.getElementById("enteredquantity"+i).value = '0';
      document.getElementById("tquantity").value = '';
      document.getElementById("total").value = '';
      return false;
    }

    }
  }



</script>
