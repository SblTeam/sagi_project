@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
@php
$cat_array=[];
$isEdit = isset($oc_pricemaster);

foreach ($items as $group) {
    $cat_array[] = ['cat' => $group->cat, 'cd' => $group->cd];
}

$cat_array_json = json_encode($cat_array);
@endphp
<script>
    document.addEventListener("DOMContentLoaded", function() {
     
     let today = new Date();
     let day = ("0" + today.getDate()).slice(-2);
     let month = ("0" + (today.getMonth() + 1)).slice(-2);
     let todayDate = today.getFullYear() + "-" + month + "-" + day;


     document.getElementById("date").setAttribute("min", todayDate);
 });
</script>
@if(!$isEdit)
<script>


    document.addEventListener('DOMContentLoaded', function() {
        let rowNumber = 1;

        // Function to add a new row
        function addNewRow() {
            const currentRow = document.getElementById(`dynamic-row-${rowNumber}`);
            const inputs = currentRow.querySelectorAll('select, input');
            let isFilled = true;
            inputs.forEach(input => {
                if (!input.value) {
                    isFilled = false;
                }
            });

            // If all fields are filled, add a new row
            if (isFilled) {
                console.log(rowNumber);
                rowNumber++;
                const newRow = document.createElement('div');
                newRow.classList.add('row', 'dynamic-row');
                newRow.id = `dynamic-row-${rowNumber}`;
                newRow.innerHTML = `
                <div class="mb-1 col-md-3">
                   
                    <select id="category@${rowNumber}" name="category[]" class="form-control" onchange="getdescription(this.id); ">
                        <option value="">-select-</option>
                        @foreach ($items as $group)
                            <option value="{{ $group->cat }}" {{ old('cat', $isEdit ? $oc_pricemaster->cat : '') == $group->cat ? 'selected' : '' }}>{{ $group->cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-1 col-md-3">
               
                    <select id="description@${rowNumber}" name="description[]" class="form-control" onchange="getcode(this.id);getunits(this.id);">
                        <option value="">-select-</option>
                    </select>
                </div>
                <div class="mb-1 col-md-2">
             
                    <select id="code@${rowNumber}" name="code[]" class="form-control" onchange="getdesc(this.id);getunits(this.id);">
                        <option value="">-select-</option>
                    </select>
                </div>
                <div class="mb-1 col-md-2">
      
                    <input type="text" id="units${rowNumber}" name="units[]" class="form-control" value="" readonly/>
                </div>
                <div class="mb-1 col-md-2">
            
                    <input type="text" id="price${rowNumber}" name="price[]" class="form-control price-input" placeholder="Enter Price" oninput="validateInput(event);"ondrop="handlePasteOrDrop(event);" onpaste="handlePasteOrDrop(event);"/>
                </div>
                <div class="mb-1 col-md-2">
                    <input type="hidden" id="client${rowNumber}" name="client[]" class="form-control" value="{{$activeContacts}}"/>
                </div>
                   <div class="mb-1 col-md-2">
                    <input type="hidden" id="empname${rowNumber}" name="empname[]" class="form-control" value="{{$empname}}"/>
                </div>
                `;
                document.getElementById('dynamic-rows').appendChild(newRow);

                // Attach the event listener to the new price input
                document.getElementById(`price${rowNumber}`).addEventListener('input', addNewRow);
            }
        }

        // Attach the event listener to the initial price input
        document.getElementById('price1').addEventListener('input', addNewRow);
    });

    function validateInput(event) {
    let input = event.target.value;

    // Remove any characters that are not digits or a decimal point
    input = input.replace(/[^0-9.]/g, '');

    // Ensure only one decimal point is present
    const decimalIndex = input.indexOf('.');
    if (decimalIndex !== -1) {
        // Keep digits before and after the decimal point but limit to 2 digits after the decimal point
        input = input.slice(0, decimalIndex + 1) + input.slice(decimalIndex + 1).replace(/[^0-9]/g, '').slice(0, 2);
    } else {
        // If no decimal point, remove any non-digit characters
        input = input.replace(/[^0-9]/g, '');
    }

    event.target.value = input;
}



    function getdescription(a) {
   var id = a.split('@')[1];



        var cat_arry = <?php echo empty($cat_array_json) ? '[]' : $cat_array_json; ?>;

        var catgroupElement = document.getElementById("category@" + id);
        var descriptionDropdown = document.getElementById("description@" + id);
        var codeDropdown = document.getElementById("code@" + id);
        var unitsDropdown = document.getElementById("units" + id);

        if (!catgroupElement || !descriptionDropdown || !codeDropdown || !unitsDropdown) {
            console.error("Required elements are missing from the DOM");
            return;
        }

        var catgroup11 = catgroupElement.value;

        // Clear existing options in the dropdowns
        descriptionDropdown.innerHTML = '';
        codeDropdown.innerHTML = '';

        var l = cat_arry.length;

        // Use a Set to track added descriptions and codes
        var descriptionsSet = new Set();
        var codesSet = new Set();

        // Add default "-select-" option at the beginning
        var defaultOption = new Option("-select-", "");
        descriptionDropdown.options.add(defaultOption.cloneNode(true));
        codeDropdown.options.add(defaultOption.cloneNode(true));
        unitsDropdown.value = '';
        for (var i = 0; i < l; i++) {
            if (cat_arry[i].cat === catgroup11) {
                var type = cat_arry[i].cd;
                var type1 = type.split(",");

                for (var j = 0; j < type1.length; j++) {
                    var typeParts = type1[j].split('@');
                    var type2 = typeParts[1].trim(); // Description
                    var type3 = typeParts[0].trim(); // Code
                    var type4 = typeParts[2].trim(); // Units

                    // Add description if not already added
                    if (!descriptionsSet.has(type2)) {
                        descriptionsSet.add(type2);
                        var descriptionOption = new Option(type2, type2);
                       
                        descriptionDropdown.options.add(descriptionOption);
                    }

                    // Add code if not already added
                    if (!codesSet.has(type3)) {
                        codesSet.add(type3);
                        var codeOption = new Option(type3, type3);
                   
                        codeDropdown.options.add(codeOption);
                    }

       
                    // if (!unitsDropdown.value) {
                    //     unitsDropdown.value = type4;
                    // }

                   
                }
            }
        }
    }

    function getcode(a) {
        var id = a.split('@')['1'];

        var codeSelect = document.getElementById('code@'+id);
        var descriptionSelect = document.getElementById('description@'+id);

        var selectedIndex = descriptionSelect.selectedIndex;
        codeSelect.selectedIndex = selectedIndex;

        var index = document.getElementsByName("description[]").length;

        for(i = 1; i <= index; i++) {
            if (i != Number(id)) {
                if (document.getElementById('description@' + id).value == document.getElementById('description@' + i).value) {
                    alert("Same Code should not be selected");

                    document.getElementById('description@' + id).options[0].selected = "selected";
                    document.getElementById('code@' + id).options[0].selected = "selected";
                    document.getElementById('units' + id).value = '';
                }
            }
        }
    }

    function getdesc(a) {


var id = a.split('@')[1];

var codeSelect = document.getElementById('code@'+id);
  var descriptionSelect = document.getElementById('description@'+id);

  var selectedIndex = codeSelect.selectedIndex;

  descriptionSelect.selectedIndex = selectedIndex;

  var index = document.getElementsByName("code[]").length;

  for(i= 1 ;i<=index;i++)
{

  if(i!=Number(id))
  {
    if(document.getElementById('code@' + id).value==document.getElementById('code@' + i).value)

   {
     alert("Same Code should not be selected");



	   document.getElementById('description@' + id).options[0].selected="selected";

	   document.getElementById('code@' + id).options[0].selected="selected";
     document.getElementById('units' + id).value = '';


   }


 }

}
}
</script>
@endif
@endsection

@section('content')
@php
$isEdit = isset($oc_pricemaster);
foreach ($items as $group) {
    $cat_array[] = ['cat' => $group->cat, 'cd' => $group->cd];
}
$cat_array_json = json_encode($cat_array);

@endphp
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Sales Module /</span> Price Master
</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header p-3"><strong style="font-size:25px">Price Master</strong></h5>
            <hr class="my-0">
            <div class="card-body">
                <form action="{{ $isEdit ? route('masters-PriceMaster.update', ['incr' => $oc_pricemaster->incr, 'code' => $oc_pricemaster->code]) : route('masters.PriceMaster.store') }}" onsubmit = "return checkpricemaster(this);" id="formAccountSettings" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('post')
                    <label for="date" class="form-label">Date</label>
                    @php
    $defaultDate = date('Y-m-d');
    $inputDate = old('date', $isEdit ? $oc_pricemaster->date : $defaultDate);
@endphp

<input type="date" id="date" name="date" value="{{ $inputDate }}" class="form-control"   style="width: 200px" onchange="getdescription(this.id);" />
<input type="hidden" id="incr" name="incr" value="{{ $incr }}" class="form-control"   style="width: 200px" />

                    <div class="row mb-4" id="dynamic-rows">
                        <div class="row dynamic-row" id="dynamic-row-1">
                            <div class="mb-1 col-md-3">
                                <label for="category1" class="form-label">Category</label>
                                <select id="category@1" name="category[]" class="form-control" onchange="getdescription(this.id);"   @if ($isEdit)
        style="pointer-events: none; -webkit-appearance: none;"
    @endif>
                                    <option value="">-select-</option>
                                    @foreach ($items as $group)
                                        <option value="{{ $group->cat }}" {{ old('cat', $isEdit ? $oc_pricemaster->cat : '') == $group->cat ? 'selected' : '' }}>{{ $group->cat }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="alert alert-danger p-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-1 col-md-3">
                                @if($isEdit)
                                    <label for="description" class="form-label">Description</label>
                                    <select id="description@1" name="description[]" class="form-control" onchange="getcode(this.id);" style="pointer-events: none;-webkit-appearance: none;" >
                                        <option value="">-select-</option>
                                        @foreach ($description as $group)
                                            <option value="{{ $group->description }}" {{ old('description1', $isEdit ? $oc_pricemaster->desc : '') == $group->description ? 'selected' : '' }}>{{ $group->description }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <label for="description1" class="form-label">Description</label>
                                    <select id="description@1" name="description[]" class="form-control" onchange="getunits(this.id);getcode(this.id);">
                                        <option value="">-select-</option>
                                    </select>
                                @endif
                            </div>
                            <div class="mb-1 col-md-2">
                                @if($isEdit)
                                    <label for="code1" class="form-label">Code</label>
                                    <select id="code@1" name="code[]" class="form-control" onchange="getdesc(this.id);getunits(this.id);" style="pointer-events: none;-webkit-appearance: none;">
                                        <option value="">-select-</option>
                                        @foreach ($codep as $group)
                                            <option value="{{ $group->code }}" {{ old('code1', $isEdit ? $oc_pricemaster->code : '') == $group->code ? 'selected' : '' }}>{{ $group->code }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <label for="code1" class="form-label">Code</label>
                                    <select id="code@1" name="code[]" class="form-control" onchange="getdesc(this.id);">
                                        <option value="">-select-</option>
                                    </select>
                                @endif
                            </div>
                            <div class="mb-1 col-md-2">
                                <label for="units1" class="form-label">Units</label>
                                <input type="text" id="units1" name="units[]" class="form-control" value="{{ old('units', $isEdit ? $oc_pricemaster->units : '') }}" readonly />
                            </div>
                            <div class="mb-1 col-md-2">
                                <label for="price1" class="form-label">Price / Unit</label>
                                <input type="text" id="price1" name="price[]" class="form-control price-input" placeholder="Enter Price" value="{{ old('price', $isEdit ? $oc_pricemaster->price : '') }}" oninput="validateInput(event);"ondrop="handlePasteOrDrop(event);" onpaste="handlePasteOrDrop(event);" />
                            </div>
                            <div class="mb-1 col-md-2">
                                <input type="hidden" id="client" name="client[]" class="form-control" value="{{$activeContacts}}" />
                            </div>
                            <div class="mb-1 col-md-2">
                                <input type="hidden" id="empname" name="empname[]" class="form-control" value="{{$empname}}" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-2">Save</button>
                        <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('masters-PriceMaster') }}'">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


<script>

    function getdescription(a) {
        alert(a);
let id = a.split('@')[1];

    var cat_arry = <?php echo empty($cat_array_json) ? '[]' : $cat_array_json; ?>;

    var catgroupElement = document.getElementById("category@" + id);
    var descriptionDropdown = document.getElementById("description@" + id);
    var codeDropdown = document.getElementById("code@" + id);
    var unitsDropdown = document.getElementById("units" + id);

    if (!catgroupElement || !descriptionDropdown || !codeDropdown || !unitsDropdown) {
        console.error("Required elements are missing from the DOM");
        return;
    }

    var catgroup11 = catgroupElement.value;

    // Clear existing options in the dropdowns
    descriptionDropdown.innerHTML = '';
    codeDropdown.innerHTML = '';

    var l = cat_arry.length;

    // Use a Set to track added descriptions and codes
    var descriptionsSet = new Set();
    var codesSet = new Set();

    // Add default "-select-" option at the beginning
    var defaultOption = new Option("-select-", "");
    descriptionDropdown.options.add(defaultOption.cloneNode(true));
    codeDropdown.options.add(defaultOption.cloneNode(true));
    unitsDropdown.value = '';

    for (var i = 0; i < l; i++) {
        if (cat_arry[i].cat === catgroup11) {
            var type = cat_arry[i].cd;
            var type1 = type.split(",");

            for (var j = 0; j < type1.length; j++) {
                var typeParts = type1[j].split('@');
                var type2 = typeParts[1].trim(); // Description
                var type3 = typeParts[0].trim(); // Code
                var type4 = typeParts[2].trim(); // Units

                // Add description if not already added
                if (!descriptionsSet.has(type2)) {
                    descriptionsSet.add(type2);
                    var descriptionOption = new Option(type2, type2);
                 
                    descriptionDropdown.options.add(descriptionOption);
                }

                // Add code if not already added
                if (!codesSet.has(type3)) {
                    codesSet.add(type3);
                    var codeOption = new Option(type3, type3);
                
                    codeDropdown.options.add(codeOption);
                }

                // Set the units value (assuming it's the same for all entries)
                // if (!unitsDropdown.value) { // Set only if not already set
                //     unitsDropdown.value = type4;
                // }
            }
        }
    }
}


function getunits(a)
{
    

    let id = a.split('@')[1];
    if(document.getElementById("description@" + id).value == '')
{
    document.getElementById("units" + id).value = '';
}

    var cat_arry = <?php echo empty($cat_array_json) ? '[]' : $cat_array_json; ?>;

    var catgroupElement = document.getElementById("category@" + id);
    var descriptionDropdown = document.getElementById("description@" + id);

    var unitsDropdown = document.getElementById("units" + id);

 

    var catgroup11 = catgroupElement.value;



    var l = cat_arry.length;



    for (var i = 0; i < l; i++) {
        if (cat_arry[i].cat === catgroup11) {
            var type = cat_arry[i].cd;
            var type1 = type.split(",");

            for (var j = 0; j < type1.length; j++) {
                var typeParts = type1[j].split('@');
                var type2 = typeParts[1].trim(); // Description
                var type3 = typeParts[0].trim(); // Code
                var type4 = typeParts[2].trim(); // Units

           
          
if(descriptionDropdown.value == type2)
{

    if (!unitsDropdown.value) { // Set only if not already set
                    unitsDropdown.value = type4;
                }
}
         
            }
        }
    }

}







function handlePasteOrDrop(e) {
  e.preventDefault();
  var paste = e.clipboardData || window.clipboardData;
  var text = paste.getData('text');
  
  // Ensure the pasted text is numeric and contains only one decimal point
  if (/^\d*\.?\d*$/.test(text)) {
    // Get the current value in the input
    var currentValue = e.target.value;
    
    // Concatenate the current value with the pasted text
    var newValue = currentValue + text;

    // Validate that the result still only contains at most one decimal point
    if (/^\d*\.?\d*$/.test(newValue)) {
      e.target.value = newValue;
    }
  }
}

function checkpricemaster(){
 

    var index = $('[name="category[]"]').length; 
  for(var k = 1;k<=index;k++) 
  { 
    
if(k==1)
	{
	
	var category= document.getElementById("category@"+k).value;
	var description= document.getElementById("description@"+k).value;
	var code= document.getElementById("code@"+k).value;
	var price= document.getElementById("price"+k).value;
	if(category=="" )
	{
        alert("please select category");
        return false;
	
	
	}
    if(description=="" )
	{
        alert("please select description");
        return false;
	}
    if(code=="" )
	{
        alert("please select code");
        return false;
	}
    if(price=="" )
	{
        alert("please enter price");
        return false;
	}
}
if(k>1)
{
    var category= document.getElementById("category@"+k).value;
	var description= document.getElementById("description@"+k).value;
	var code= document.getElementById("code@"+k).value;
	var price= document.getElementById("price"+k).value;
    if(category!="" ) 
    {
        if(description=="" )
	{
        alert("please select description");
        return false;
	}
    if(code=="" )
	{
        alert("please select code");
        return false;
	}
    if(price=="" )
	{
        alert("please enter price");
        return false;
	}
    }  
    if(description!="" ) 
    {
        if(category=="" )
	{
        alert("please select description");
        return false;
	}
    if(code=="" )
	{
        alert("please select code");
        return false;
	}
    if(price=="" )
	{
        alert("please enter price");
        return false;
	}
    }  
    if(price!="" ) 
    {
        if(category=="" )
	{
        alert("please select category");
        return false;
	}
    if(description=="" )
	{
        alert("please select description");
        return false;
	}
    if(code=="" )
	{
        alert("please select code");
        return false;
	}
    }
}
	
	}

  }




</script>
