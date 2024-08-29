@extends('layouts/contentNavbarLayout')

@section('title', 'Invoice')

@section('page-script')

<script>
    // Ensure the passed variables are correctly converted to JSON
    var invoice = @json($invoice);
    var invoiceDetails = @json($invoiceDetails);
console.log(invoiceDetails);
    // Function to add a new row
    function addNewRow(rowNumber, category,description, code, quantity, price, taxable, basic, tax, tax_value,tax_amount,Total) {
        let newRow = document.createElement('tr');
        newRow.classList.add('dynamic-row', 'mb-1');
        newRow.id = `dynamic-row-${rowNumber}`;
        newRow.innerHTML = `
  <td>
            <select id="category${rowNumber}" name="category[]"  style="border: none;pointer-events: none;-webkit-appearance: none;" required>
                <option value="${category}">${category}</option>
            </select>
        </td>
        <td>
            <select id="description${rowNumber}" name="description[]"  style="border: none;pointer-events: none;-webkit-appearance: none;" required>
                <option value="${description}">${description}</option>
            </select>
        </td>
        <td>
            <input type="text" id="code${rowNumber}" name="code[]" style="border: none;width: 100px;" value="${code}" readonly/>
        </td>
        <td>
            <input type="number" id="quantity${rowNumber}" name="quantity[]"  style="border: none;width: 70px" value="${quantity}" required readonly/>
        </td>

        <td>
            <input type="number" id="price${rowNumber}" name="price[]"  style="border: none;width: 70px;" value="${price}" required
            readonly/>
        </td>
             <td>
                <input type="number" id="taxable${rowNumber}" name="taxable[]" style="border: none; width: 70px;" readonly  value="${taxable}" required/>
            </td>
               <td>
                <input type="number" id="basic${rowNumber}" name="basic[]" readonly  value="${basic}" style="border: none;  width: 100px;" required/>
            </td>
        <td>
            <select id="tax${rowNumber}" name="tax[]"  style="border: none;pointer-events: none;-webkit-appearance: none;" required>
                <option value="${tax}">${tax}</option>
            </select>
        </td>
        <td>
            <input type="number" id="tax_value${rowNumber}" name="tax_value[]" style="border: none;width: 50px;"   value="${tax_value}" readonly/>
        </td>
         <td>
            <input type="number" id="taxamount${rowNumber}" name="taxamount[]" style="border: none;width: 70px;"  value="${tax_amount}" readonly/>
        </td>
         <td>
            <input type="number" id="Total${rowNumber}" name="Total[]"  style="border: none;width: 100px;" value="${Total}" readonly/>
        </td>
        `;
        document.getElementById('dynamic-rows').appendChild(newRow);
    }

    // Example usage of addNewRow function
    invoiceDetails.forEach((detail, index) => {
        addNewRow(index+1, detail.category, detail.description, detail.code, detail.quantity, detail.price, detail.taxable_price, detail.basic, detail.taxcode
        , detail.taxvalue, detail.taxamount,detail.Total);
    });
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
                <form action="{{ route('transctions-editinvoice-store', ['id' => $invoice]) }}" id="formSalesOrder" method="POST" enctype="multipart/form-data">
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
                                <option value="{{$vendorid}}">{{$vendorid}}</option>

                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="dist" class="form-label"><strong>Distributor*</strong></label>
                        </div>
                        <div class="col-md-2">
                            <select id="dist" name="dist" class="form-control" onchange="getid(); getso();">
                                <option value="{{$vendor}}">{{$vendor}}</option>

                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="so" class="form-label"><strong>SO*</strong></label>
                        </div>
                        <div class="col-md-2">
                            <select id="so" name="so" class="form-control" onchange="getsodetails();">
                                <option value="{{$so}}">{{$so}}</option>

                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="invoice" class="form-label"><strong>Invoice</strong></label>
                        </div>
                        <div class="col-md-2">
                        <input type="text" id="invoice" name="invoice" class="form-control" value="{{$invoice}}" readonly/>

                        </div>
                        <div class="col-md-2">
                            <label for="book_invoice" class="form-label"><strong>Book Invoice</strong></label>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="book_invoice" name="book_invoice" class="form-control" value="{{$book_invoice}}"/>
                        </div>
                    </div>

                    <div class="row mb-3">
    <div class="col-md-2">
        <label class="form-label"><strong>Irn no</strong></label>
        <input type="text" name="irn" id="irn" value = "{{$irn}}" class="form-control"/>
        @error('irn')
                        <div class="alert alert-danger p-1">{{ $message }}</div>
                    @enderror
    </div>

    <div class="col-md-3">
        <label class="form-label"><strong>Eway bill no</strong></label>
        <input type="text" name="ewaybill" id="ewaybill" value = "{{$ewaybill}}" class="form-control"/>
        @error('ewaybill')
                        <div class="alert alert-danger p-1">{{ $message }}</div>
                    @enderror
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
                                    <th>Taxable Price</th>
                                    <th>Basic Total</th>
                                    <th>Tax</th>
                                    <th>Tax value</th>
                                    <th>Tax amount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="dynamic-rows">

                            </tbody>
                        </table>
                    </div>

                    <div class="row mb-4">
    <div class="col-md-3 offset-md-1">
        <label class="form-label"><strong>Total Quantity</strong></label>
        <input type="text" name="tquantity" id="tquantity" class="form-control" readonly value="{{$totalquantity}}"/>
    </div>

    <div class="col-md-3">
        <label class="form-label"><strong>GSTIN</strong></label>
        <input type="text" name="gstin" id="gstin" class="form-control" readonly value="{{$gstin}}"/>
    </div>

    <div class="col-md-3">
        <label class="form-label"><strong>Grand Total</strong></label>
        <input type="text" name="total" id="total" class="form-control" readonly value="{{$total}}"/>
    </div>
</div>






                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label"><strong>Narration</strong></label>
                            <textarea name="narration" id="narration" class="form-control" rows="3">{{$narration}}</textarea>
                        </div>
                        
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="reset" class="btn btn-danger" onclick="window.location.href='{{ route('transctions-Invoice')}}'">Cancel</button
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

function gettotal() {

var tquantity = 0;
var sum = 0;
var sum1 = 0;
var index = $('[name="category[]"]').length;

for (var i = 1; i <= index; i++) {

    // Check if the checkbox exists and is checked
        var enteredQuantity = parseFloat(document.getElementById("quantity" + i).value);

        var price = parseFloat(document.getElementById("price" + i).value);
        var tax = parseFloat(document.getElementById("tax" + i).value) || 0; // Ensure tax is treated as a float and defaults to 0 if empty



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

document.getElementById("tquantity").value = tquantity.toFixed(2);
document.getElementById("total").value = sum1.toFixed(2);
document.getElementById("btotal").value = sum1.toFixed(2);
}



</script>


