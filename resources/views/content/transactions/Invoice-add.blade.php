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
                    var category = values[0];
                    var code = values[1];
                    var description = values[2];
                    var quantity = values[3];
                    var price = values[4];
                    var taxable = values[5];
                    var basic = values[6];
                    var tax = values[7];
                    var tax_value = values[8];
                    var tax_amount = values[9];
                    var Total = values[10];

                    // Add new row
                    addNewRow(index + 1, category, description, code, quantity, price, taxable, basic, tax, tax_value, tax_amount, Total);
                } else {
                    console.error('Invalid data format:', values);
                }
            });gettotal();
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
  }

  function addNewRow(rowNumber,category, description, code, quantity, price, taxable, basic, tax, tax_value, tax_amount, Total) {
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
                <form action="{{ route('transctions-invoice-store') }}" id="formSalesOrder" method="POST" enctype="multipart/form-data">
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
                        @error('id')
                        <div class="alert alert-danger p-1">{{ $message }}</div>
                    @enderror
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
                        @error('dist')
                        <div class="alert alert-danger p-1">{{ $message }}</div>
                    @enderror
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
                            <label for="invoice" class="form-label"><strong>Invoice</strong></label>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="invoice" name="invoice" class="form-control" value="{{ $inv }}" readonly/>
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
                                    <th>Taxable Price</th>
                                    <th>Basic Total</th>
                                    <th>Tax</th>
                                    <th>Tax value</th>
                                    <th>Tax amount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="dynamic-rows">
                                <tr class="dynamic-row mb-1" id="dynamic-row-1">
                                    <td>
                                        <select id="category1" name="category[]"  style="border: none;pointer-events: none;-webkit-appearance: none;" required>
                                       
                                        </select>
                                    </td>
                                    <td>
                                        <select id="description1" name="description[]" style="border: none;pointer-events: none;-webkit-appearance: none;" required>
                                     
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="code1" name="code[]" style="border: none;"  readonly/>
                                    </td>
                                    <td>
                                        <input type="number" id="quantity1" name="quantity[]" style="border: none;"   readonly/>
                                    </td>
                                    <td>
                                        <input type="number" id="price1" name="price[]"  style="border: none;" readonly/>
                                    </td>
                                    <td>
                                       <input type="number" id="taxable1" name="taxable[]" style="border: none;"   readonly/>
                                     </td>
                                     <td>
                                      <input type="number" id="basic1" name="basic[]" style="border: none;"  readonly/>
                                     </td>
                                     <td>
                                      <select id="tax1" name="tax[]" style="border: none;pointer-events: none;-webkit-appearance: none;" required>
                                         <option value="">-select-</option>
                                      </select>
                                      </td>
                                      <td>
            <input type="number" id="tax_value1" name="tax_value[]" style="border: none;" value="${tax_value}" readonly/>
        </td>
         <td>
            <input type="number" id="taxamount1" name="taxamount[]"  style="border: none;" value="${tax_amount}" readonly/>
        </td>
         <td>
            <input type="number" id="Total1" name="Total[]" style="border: none;" value="${Total}" readonly/>
        </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
<table>
    <center>
                    <tr class="row mb-4">
                        <td class="col-md-3 offset-md-1">
                            <label class="form-label"><strong>Total Quantity</strong></label>
                            <input type="text" name="tquantity" id="tquantity" class="form-control" readonly value="0"/>
                        </td>

               

                        <td class="col-md-3">
                            <label class="form-label"><strong>GSTIN</strong></label>
                            <input type="text" name="gstin" id="gstin" class="form-control" readonly value="{{$gstin}}"/>
                        </td>

                     

    <!-- TCS Tax Radio Buttons -->


    <td class="col-md-3">
                            <label class="form-label"><strong>Grand Total</strong></label>
                            <input type="text" name="total" id="total" class="form-control" readonly value="0"/>
                        </td>

                        </tr>
                        <tr class="row mb-4">
                        <td class="col-md-12">
                            <label class="form-label"><strong>Narration</strong></label>
                            <textarea name="narration" id="narration" class="form-control" rows="3"></textarea>
                        </td>
                    </tr>
                        <center>
</table>
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
function apply_tcs_tax(a){

var a1 = parseFloat(a/100);

  if(document.getElementById("btotal").value > 0)
{
  var x = parseFloat(document.getElementById("btotal").value);
  var tcsamount = parseFloat(a1 * x);
  var grandt = parseFloat(x+parseFloat(a1 * x));

  document.getElementById("tcsamount").value = tcsamount;

}
}
function gettotal() 
{

    var sum = 0 ;
    var sum1 = 0;


var index = $('[name="category[]"]').length;

for (var i = 1; i <= index; i++) {
    var enteredQuantity = parseFloat(document.getElementById("quantity" + i).value);
var price = parseFloat(document.getElementById("price" + i).value);
var Total = parseFloat(document.getElementById("Total" + i).value);
sum = parseFloat(sum + enteredQuantity);
sum1 = parseFloat(sum1 + Total);
}


document.getElementById("tquantity").value = sum.toFixed(2);
document.getElementById("total").value = sum1.toFixed(2);

}





</script>


