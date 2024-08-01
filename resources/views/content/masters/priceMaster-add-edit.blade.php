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
                rowNumber++;
                const newRow = document.createElement('div');
                newRow.classList.add('row', 'dynamic-row');
                newRow.id = `dynamic-row-${rowNumber}`;
                newRow.innerHTML = `
                <div class="mb-1 col-md-3">
                    <label for="category${rowNumber}" class="form-label">Category</label>
                    <select id="category${rowNumber}" name="category[]" class="form-control" onchange="getdescription(this.id);">
                        <option value="">-select-</option>
                        @foreach ($items as $group)
                            <option value="{{ $group->cat }}" {{ old('cat', $isEdit ? $oc_pricemaster->cat : '') == $group->cat ? 'selected' : '' }}>{{ $group->cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-1 col-md-3">
                    <label for="description${rowNumber}" class="form-label">Description</label>
                    <select id="description${rowNumber}" name="description[]" class="form-control" onchange="getcode(this.id);">
                        <option value=""></option>
                    </select>
                </div>
                <div class="mb-1 col-md-2">
                    <label for="code${rowNumber}" class="form-label">Code</label>
                    <select id="code${rowNumber}" name="code[]" class="form-control" onchange="getdesc(this.id);">
                        <option value=""></option>
                    </select>
                </div>
                <div class="mb-1 col-md-2">
                    <label for="units${rowNumber}" class="form-label">Units</label>
                    <input type="text" id="units${rowNumber}" name="units[]" class="form-control" value="" readonly/>
                </div>
                <div class="mb-1 col-md-2">
                    <label for="price${rowNumber}" class="form-label">Price / Unit</label>
                    <input type="text" id="price${rowNumber}" name="price[]" class="form-control price-input" placeholder="Enter Price" onKeyPress="onlyNumbers2(event);"/>
                </div>
                <div class="mb-1 col-md-2">
                    <input type="hidden" id="client${rowNumber}" name="client[]" class="form-control" value="{{$activeContacts}}"/>
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

    function onlyNumbers2(e) {
        var code = e.charCode || e.keyCode;
        var input = e.target.value;

        // Allow numbers (0-9)
        if (code >= 48 && code <= 57) {
            return;
        }

        // Allow one decimal point
        if (code === 46 && !input.includes('.')) {
            return;
        }

        // Prevent any other input
        e.preventDefault();
    }

    function getdescription(a) {
        let id = a.substring(8, 9);

        var cat_arry = <?php echo empty($cat_array_json) ? '[]' : $cat_array_json; ?>;

        var catgroupElement = document.getElementById("category" + id);
        var descriptionDropdown = document.getElementById("description" + id);
        var codeDropdown = document.getElementById("code" + id);
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
                        descriptionOption.title = type;
                        descriptionDropdown.options.add(descriptionOption);
                    }

                    // Add code if not already added
                    if (!codesSet.has(type3)) {
                        codesSet.add(type3);
                        var codeOption = new Option(type3, type3);
                        codeOption.title = type;
                        codeDropdown.options.add(codeOption);
                    }

                    // Set the units value (assuming it's the same for all entries)
                    if (!unitsDropdown.value) { // Set only if not already set
                        unitsDropdown.value = type4;
                    }
                }
            }
        }
    }

    function getcode(a) {
        var id = a.substring(11, 12);

        var codeSelect = document.getElementById('code'+id);
        var descriptionSelect = document.getElementById('description'+id);

        var selectedIndex = descriptionSelect.selectedIndex;
        codeSelect.selectedIndex = selectedIndex;

        var index = document.getElementsByName("description[]").length;

        for(i = 1; i <= index; i++) {
            if (i != Number(id)) {
                if (document.getElementById('description' + id).value == document.getElementById('description' + i).value) {
                    alert("Same Code should not be selected");

                    document.getElementById('description' + id).options[0].selected = "selected";
                    document.getElementById('code' + id).options[0].selected = "selected";
                    document.getElementById('units' + id).value = '';
                }
            }
        }
    }

    function getdesc(a) {
        var id = a.substring(4, 5);

        var codeSelect = document.getElementById('code'+id);
        var descriptionSelect = document.getElementById('description'+id);

        var selectedIndex = codeSelect.selectedIndex;
        descriptionSelect.selectedIndex = selectedIndex;
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
                <form action="{{ $isEdit ? route('masters-PriceMaster.update', ['incr' => $oc_pricemaster->incr, 'code' => $oc_pricemaster->code]) : route('masters.PriceMaster.store') }}" id="formAccountSettings" method="POST" enctype="multipart/form-data">
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
                                <select id="category1" name="category[]" class="form-control" onchange="getdescription(this.id);">
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
                                    <label for="description1" class="form-label">Description</label>
                                    <select id="description1" name="description[]" class="form-control" onchange="getcode(this.id);">
                                        <option value="">-select-</option>
                                        @foreach ($description as $group)
                                            <option value="{{ $group->description }}" {{ old('description1', $isEdit ? $oc_pricemaster->desc : '') == $group->description ? 'selected' : '' }}>{{ $group->description }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <label for="description1" class="form-label">Description</label>
                                    <select id="description1" name="description[]" class="form-control" onchange="getcode(this.id);">
                                        <option value="">-select-</option>
                                    </select>
                                @endif
                            </div>
                            <div class="mb-1 col-md-2">
                                @if($isEdit)
                                    <label for="code1" class="form-label">Code</label>
                                    <select id="code1" name="code[]" class="form-control" onchange="getdesc(this.id);">
                                        <option value="">-select-</option>
                                        @foreach ($codep as $group)
                                            <option value="{{ $group->code }}" {{ old('code1', $isEdit ? $oc_pricemaster->code : '') == $group->code ? 'selected' : '' }}>{{ $group->code }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <label for="code1" class="form-label">Code</label>
                                    <select id="code1" name="code[]" class="form-control" onchange="getdesc(this.id);">
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
                                <input type="text" id="price1" name="price[]" class="form-control price-input" placeholder="Enter Price" value="{{ old('price', $isEdit ? $oc_pricemaster->price : '') }}" onKeyPress="onlyNumbers2(event);" />
                            </div>
                            <div class="mb-1 col-md-2">
                                <input type="hidden" id="client" name="client[]" class="form-control" value="{{$activeContacts}}" />
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
    document.getElementById("cat").value = '';
    function getdescription(a) {
    let id = a.substring(8, 9);

    var cat_arry = <?php echo empty($cat_array_json) ? '[]' : $cat_array_json; ?>;

    var catgroupElement = document.getElementById("category" + id);
    var descriptionDropdown = document.getElementById("description" + id);
    var codeDropdown = document.getElementById("code" + id);
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
                    descriptionOption.title = type;
                    descriptionDropdown.options.add(descriptionOption);
                }

                // Add code if not already added
                if (!codesSet.has(type3)) {
                    codesSet.add(type3);
                    var codeOption = new Option(type3, type3);
                    codeOption.title = type;
                    codeDropdown.options.add(codeOption);
                }

                // Set the units value (assuming it's the same for all entries)
                if (!unitsDropdown.value) { // Set only if not already set
                    unitsDropdown.value = type4;
                }
            }
        }
    }
}

function getcode(a) {


  var id = a.substring(11, 12);

  var codeSelect = document.getElementById('code'+id);
    var descriptionSelect = document.getElementById('description'+id);

    var selectedIndex = descriptionSelect.selectedIndex;

    codeSelect.selectedIndex = selectedIndex;

var index = document.getElementsByName("description[]").length;

    for(i= 1 ;i<=index;i++)
{

  if(i!=Number(id))
  {
    if(document.getElementById('description' + id).value==document.getElementById('description' + i).value)

   {
     alert("Same Code should not be selected");



	   document.getElementById('description' + id).options[0].selected="selected";

	   document.getElementById('code' + id).options[0].selected="selected";
     document.getElementById('units' + id).value = '';


   }


 }

}


}

function getdesc(a) {


var id = a.substring(4, 5);

var codeSelect = document.getElementById('code'+id);
  var descriptionSelect = document.getElementById('description'+id);

  var selectedIndex = codeSelect.selectedIndex;

  descriptionSelect.selectedIndex = selectedIndex;


}


function onlyNumbers2(e) {
  var code = e.charCode || e.keyCode;
  var input = e.target.value;

  // Allow numbers (0-9)
  if (code >= 48 && code <= 57) {
    return;
  }

  // Allow one decimal point
  if (code === 46 && !input.includes('.')) {
    return;
  }

  // Prevent any other input
  e.preventDefault();
}




</script>
