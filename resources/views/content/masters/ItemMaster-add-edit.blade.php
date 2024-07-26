@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')

<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')

@php

$cat_array = [];
foreach ($categoryGroups as $group) {
$cat_array[] = ['catgroup' => $group->catgroup, 'type' => $group->type];
}
$cat_array_json = json_encode($cat_array);


$isEdit = isset($ims_itemcodes);

@endphp
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Sales Masters/</span> Item Master
</h4>

<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <h5 class="card-header"><strong style="font-size: 25px;">Item Details<strong></h5>
      <hr class="my-0">
      <!-- Account -->
      <div class="card-body">
        <form method="POST" action="{{ $isEdit ? route('masters-ItemMaster.update', $ims_itemcodes->id) : route('masters.ItemMaster.store') }}">
          @csrf

          @method('post')
          <div class="row">
            <div class="mb-0 col-md-4">
              <label for="itemcode" class="form-label">Item Code <sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" id="code" name="code" value="{{ old('code', $isEdit ? $ims_itemcodes->code : '') }}" onKeyPress="onlyNumbers12(event);" placeholder="Enter itemCode" style="width: 50%" autofocus />
              @error('code')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-0 col-md-5">

              <label for="description" class="form-label">Description <sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" name="description" id="description" onKeyPress="onlyNumbers123(event);" placeholder="Enter Description" style="width: 75%" value="{{ old('description', $isEdit ? $ims_itemcodes->description : '') }}" />
              @error('description')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-0 col-md-4">
              <label class="form-label" for="category_group">Category Group <sup style="color:red;">&#9733;</sup></label>
              <select id="catgroup" name="catgroup" class="select2 form-select" style="width: 75%" onchange="getcategory();">
                <option value="">Select</option>
                @foreach ($categoryGroups as $group)
                <option value="{{ $group->catgroup }}" {{ old('catgroup', $isEdit ? $ims_itemcodes->catgroup : '') == $group->catgroup ? 'selected' : '' }}>{{ $group->catgroup }}</option>
                @endforeach
              </select>
              @error('catgroup')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-0 col-md-5">

              @if($isEdit)

              <label class="form-label" for="category">Category<sup style="color:red;">&#9733;</sup></label>

              <select id="cat" name="cat" class="select2 form-select" style="width: 60%">
                <option value="">Select</option>
                @foreach ($types as $group)
                  <option value="{{ $group->type }}" {{ old('cat') == $group->type ? 'selected' : '' }}>
                    {{ $group->type }}
                  </option>
                @endforeach
              </select>
              

              @else

              <label class="form-label" for="category">Category <sup style="color:red;">&#9733;</sup></label>

              <select id="cat" name="cat" class="select2 form-select" style="width: 60%">
                <option value="">Select</option>
                    @foreach ($categorytypes as $group)
                  <option value="{{ $group->type }}" {{ old('cat') == $group->type ? 'selected' : '' }}>
                    {{ $group->type }}
                  </option>
                @endforeach
              </select>
              @endif

              @error('cat')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-0 col-md-4">
              <label class="form-label" for="type">Type <sup style="color:red;">&#9733;</sup></label>
              <select id="type" name="type" class="select2 form-select " style="width: 75%">
                <option value="">Select</option>

                <option value="Consumed" {{ old('type', $isEdit ? $ims_itemcodes->type : '') == 'Consumed' ? 'selected' : '' }}>Consumed</option>
                <option value="Finished Goods" {{ old('type', $isEdit ? $ims_itemcodes->type : '') == 'Finished Goods' ? 'selected' : '' }}>Finished Goods</option>
                <option value="Packing Material" {{ old('type', $isEdit ? $ims_itemcodes->type : '') == 'Packing Material' ? 'selected' : '' }}>Packing Material</option>
                <option value="Raw Material" {{ old('type', $isEdit ? $ims_itemcodes->type : '') == 'Raw Material' ? 'selected' : '' }}>Raw Material</option>
                <option value="By Product" {{ old('type', $isEdit ? $ims_itemcodes->type : '') == 'By Product' ? 'selected' : '' }}>By Product</option>
                <option value="By Product Packets" {{ old('type', $isEdit ? $ims_itemcodes->type : '') == 'By Product Packets' ? 'selected' : '' }}>By Product Packets</option>
                <option value="Others" {{ old('type', $isEdit ? $ims_itemcodes->type : '') == 'Others' ? 'selected' : '' }}>Others</option>
                <option value="Wastage" {{ old('type', $isEdit ? $ims_itemcodes->type : '') == 'Wastage' ? 'selected' : '' }}>Wastage</option>
              </select>
              @error('type')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-0 col-md-5">
              <label class="form-label" for="type">No.Of Pieces<sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" id="piece1" name="pieces" value="{{ old('pieces', $isEdit ? $ims_itemcodes->pieces : '') }}" placeholder="Enter NO.OF Pieces" style="width: 50%" autofocus />
              @error('type')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-0 col-md-4">
              <label class="form-label" for="type">Bag Weight<sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" id="weight1" name="weight" value="{{ old('weight', $isEdit ? $ims_itemcodes->weight : '') }}" placeholder="Enter Bag Weight" style="width: 50%" autofocus />
              @error('weight')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-0 col-md-5">
              <label class="form-label" for="type">Packet Weight<sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" id="packetweight" name="packetweight" value="{{ old('packetweight', $isEdit ? $ims_itemcodes->weight : '') }}" placeholder="Enter Packet Weight" style="width: 50%" autofocus />
              @error('packetweight')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-0 col-md-4">
              <label class="form-label" for="sum">Storage Units Of Measure <sup style="color:red;">&#9733;</sup></label>
              <select id="sunits" name="sunits" class="select2 form-select " style="width: 75%">
                <option value="">Select</option>
                @foreach ($sunits as $group)
                <option value="{{ $group->sunits }}" {{ old('sunits', $isEdit ? $ims_itemcodes->sunits : '') == $group->sunits ? 'selected' : '' }}>{{ $group->sunits }}</option>
                @endforeach
              </select>
              @error('sunits')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-0 col-md-5">
              <label class="form-label" for="cum">Consumption Units Of Measure <sup style="color:red;">&#9733;</sup></label>
              <select id="cunits" name="cunits" class="select2 form-select " style="width: 60%">
                <option value="">Select</option>
                @foreach ($sunits as $group)
                <option value="{{ $group->sunits }}" {{ old('cunits', $isEdit ? $ims_itemcodes->sunits : '') == $group->sunits ? 'selected' : '' }}>{{ $group->sunits }}</option>
                @endforeach
              </select>
              @error('cunits')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-0 col-md-4">
              <label class="form-label" for="Sales Units Of Measure">Sales Units Of Measure <sup style="color:red;">&#9733;</sup></label>
              <select id="saunits" name="saunits" class="select2 form-select" style="width: 75%">
                <option value="">Select</option>
                @foreach ($sunits1 as $group)
                <option value="{{ $group->sunits }}" {{ old('saunits', $isEdit ? $ims_itemcodes->sunits : '') == $group->sunits ? 'selected' : '' }}>{{ $group->sunits }}</option>
                @endforeach
              </select>
              @error('saunits')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-0 col-md-5">
              <label class="form-label" for="country">Source <sup style="color:red;">&#9733;</sup></label>
              <select id="source" name="source" class="select2 form-select " style="width: 60%">
                <option value="">Select</option>
                <option value="Produced" {{ old('source', $isEdit ? $ims_itemcodes->source : '') == 'Produced' ? 'selected' : '' }}>Produced</option>
                <option value="Purchased" {{ old('source', $isEdit ? $ims_itemcodes->source : '') == 'Purchased' ? 'selected' : '' }}>Purchased</option>
                <option value="Produced or Purchased" {{ old('source', $isEdit ? $ims_itemcodes->source : '') == 'Produced or Purchased' ? 'selected' : '' }}>Produced or Purchased</option>
              </select>
              @error('source')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-0 col-md-4">
              <label class="form-label" for="country">Usage <sup style="color:red;">&#9733;</sup></label>
             <select id="iusage" name="iusage" class="select2 form-select" style="width: 75%" onchange="checkUsage();">
  <option value="">Select</option>
  <option value="General Consumption" {{ old('iusage', $isEdit ? $ims_itemcodes->iusage : '') == 'General Consumption' ? 'selected' : '' }}>General Consumption</option>
  <option value="Sale" {{ old('iusage', $isEdit ? $ims_itemcodes->iusage : '') == 'Sale' ? 'selected' : '' }}>Sale</option>
  <option value="Rejected" {{ old('iusage', $isEdit ? $ims_itemcodes->iusage : '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
  <option value="Produced or Sale" {{ old('iusage', $isEdit ? $ims_itemcodes->iusage : '') == 'Produced or Sale' ? 'selected' : '' }}>Produced or Sale</option>
  <option value="Rejected or Sale" {{ old('iusage', $isEdit ? $ims_itemcodes->iusage : '') == 'Rejected or Sale' ? 'selected' : '' }}>Rejected or Sale</option>
  <option value="Produced or Rejected" {{ old('iusage', $isEdit ? $ims_itemcodes->iusage : '') == 'Produced or Rejected' ? 'selected' : '' }}>Produced or Rejected</option>
  <option value="Produced or Sale or Rejected" {{ old('iusage', $isEdit ? $ims_itemcodes->iusage : '') == 'Produced or Sale or Rejected' ? 'selected' : '' }}>Produced or Sale or Rejected</option>
</select>

              @error('iusage')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-0 col-md-5">
              <label class="form-label" for="country">Tax Applicable</label>
              <select id="tax_applicable" name="tax_applicable" class="select2 form-select" style="width: 60%">
                <option value="">Select</option>
                @foreach ($taxcode as $group)
                <option value="{{ $group->code }}" {{ old('tax_applicable', $isEdit ? $ims_itemcodes->tax_applicable : '') == $group->code ? 'selected' : '' }}>{{ $group->code }}</option>
                @endforeach
              </select>


              @error('tax_applicable')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>


            <div class="mb-0 col-md-4">
              <label class="form-label" for="cum">Item A/C</label>
              <select id="iac" name="iac" class="select2 form-select " style="width: 60%">
                <option value="">Select</option>
                @foreach ($codec as $group)
                <option value="{{ $group->code }}" {{ old('iac', $isEdit ? $ims_itemcodes->iac : '') == $group->code ? 'selected' : '' }}>{{ $group->description}}</option>
                @endforeach
              </select>
              @error('iac')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-0 col-md-5">
              @if($isEdit && old('iusage', $isEdit ? $ims_itemcodes->iusage : '') == 'General Consumption')
              <label id="expcal" class="form-label" for="cum" style="display: block;">Consumption A/C</label>
              <select id="expca" name="expca" class="select2 form-select" style="width: 60%; display: block;">
                <option value="">Select</option>
                @foreach ($codee as $group)
                <option value="{{ $group->code }}" {{ old('expca', $isEdit ? $ims_itemcodes->wpac : '') == $group->code ? 'selected' : '' }}>{{ $group->description }}</option>
                @endforeach
              </select>
              @else
              <label id="expcal" class="form-label" for="cum" style="display: none;">Consumption A/C</label>
              <select id="expca" name="expca" class="select2 form-select" style="width: 60%; display: none;">
                <option value="">Select</option>
                @foreach ($codee as $group)
                <option value="{{ $group->code }}" {{ old('expca', $isEdit ? $ims_itemcodes->wpac : '') == $group->code ? 'selected' : '' }}>{{ $group->description }}</option>
                @endforeach
              </select>
              @endif

              @error('expca')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>







            <div class="mb-0 col-md-4">
              @if($isEdit && old('iusage', $isEdit ? $ims_itemcodes->iusage : '') == 'Sale')
              <label id="cogsacl" class="form-label" for="cum" style="display: block;">COGS A/C</label>
              <select id="cogsac" name="cogsac" class="select2 form-select" style="width: 60%; display: block;">
                <option value="">Select</option>
                @foreach ($codee as $group)
                <option value="{{ $group->code }}" {{ old('cogsac', $isEdit ? $ims_itemcodes->cogsac : '') == $group->code ? 'selected' : '' }}>{{ $group->description }}</option>
                @endforeach
              </select>
              @else
              <label id="cogsacl" class="form-label" for="cum" style="display: none;">COGS A/C<sup style="color:red;">&#9733;</sup></label>
              <select id="cogsac" name="cogsac" class="select2 form-select" style="width: 60%; display: none;">
                <option value="">Select</option>
                @foreach ($codee as $group)
                <option value="{{ $group->code }}" {{ old('cogsac', $isEdit ? $ims_itemcodes->cogsac : '') == $group->code ? 'selected' : '' }}>{{ $group->description }}</option>
                @endforeach
              </select>
              @endif

              @error('cogsac')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>








            <div class="mb-0 col-md-5">
              @if($isEdit && old('iusage', $isEdit ? $ims_itemcodes->iusage : '') == 'Sale')
              <label id="sacl" class="form-label" for="cum" style="display: block;">Sales A/C</label>
              <select id="sac" name="sac" class="select2 form-select" style="width: 60%; display: block;">
                <option value="">Select</option>
                @foreach ($codes as $group)
                <option value="{{ $group->code }}" {{ old('sac', $isEdit ? $ims_itemcodes->sac : '') == $group->code ? 'selected' : '' }}>{{ $group->description }}</option>
                @endforeach
              </select>
              @else
              <label id="sacl" class="form-label" for="cum" style="display: none;">Sales A/C</label>
              <select id="sac" name="sac" class="select2 form-select" style="width: 60%; display: none;">
                <option value="">Select</option>
                @foreach ($codes as $group)
                <option value="{{ $group->code }}" {{ old('sac', $isEdit ? $ims_itemcodes->sac : '') == $group->code ? 'selected' : '' }}>{{ $group->description }}</option>
                @endforeach
              </select>
              @endif

              @error('sac')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>








            <div class="mb-0 col-md-4">
              @if($isEdit && old('iusage', $isEdit ? $ims_itemcodes->iusage : '') == 'Sale')
              <label id="sractdl" class="form-label" for="cum" style="display: block;">Sales Return A/C</label>
              <select id="sractd" name="sractd" class="select2 form-select" style="width: 60%; display: block;">
                <option value="">Select</option>
                @foreach ($codes as $group)
                <option value="{{ $group->code }}" {{ old('sac', $isEdit ? $ims_itemcodes->srac : '') == $group->code ? 'selected' : '' }}>{{ $group->description }}</option>
                @endforeach
              </select>
              @else
              <label id="sractdl" class="form-label" for="cum" style="display: none;">Sales Return A/C</label>
              <select id="sractd" name="sractd" class="select2 form-select" style="width: 60%; display: none;">
                <option value="">Select</option>
                @foreach ($codes as $group)
                <option value="{{ $group->code }}" {{ old('sac', $isEdit ? $ims_itemcodes->srac : '') == $group->code ? 'selected' : '' }}>{{ $group->description }}</option>
                @endforeach
              </select>
              @endif

              @error('sractd')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>






            <div class="mb-0 col-md-5">
              <label class="form-label" for="cum">EAN No<sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" name="ean" id="ean" onKeyPress="onlyNumberse(event);"  placeholder="Enter ean code" style="width: 75%" value="{{ old('ean', $isEdit ? $ims_itemcodes->ean_no : '') }}" />
              @error('ean')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>



            <div class="mb-0 col-md-4">
              <label class="form-label" for="cum">HSN/SAC<sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" name="hsn" id="hsn" onKeyPress="onlyNumbersh(event);" placeholder="Enter hsn code" style="width: 75%" value="{{ old('hsn', $isEdit ? $ims_itemcodes->hsn : '') }}" />
              @error('hsn')
              <div class="alert alert-danger p-1">{{ $message }}</div>
              @enderror
            </div>




            <div class="mt-2">
              <button type="submit" class="btn btn-primary me-2">Save changes</button>
              <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('masters-ItemMaster')}}'">Cancel</button>
            </div>
        </form>
      </div>
      <!-- /Account -->
    </div>
  </div>
</div>
@endsection

<script>

function onlyNumbers123(e) {
  
  var code = e.charCode || e.keyCode;
  var input = e.target.value;


  if ((code >= 65 && code <= 90) || (code >= 97 && code <= 122)|| (code >= 48 && code <= 57)|| (code == 32) ) {
    return;
  }
 e.preventDefault();
}

function onlyNumbers12(e) {
  
  var code = e.charCode || e.keyCode;
  var input = e.target.value;


  if ((code >= 65 && code <= 90) || (code >= 97 && code <= 122)|| (code >= 48 && code <= 57)) {
    return;
  }
 e.preventDefault();
}

function onlyNumberse(e) {
  var code = e.charCode || e.keyCode;
  var input = e.target.value;

  // Allow digits (0-9) and check input length
  if (code >= 48 && code <= 57) {
    // Check if the length of the input exceeds 10 characters
    if (input.length >= 13) {
      e.preventDefault(); // Prevent further input
    }
    return; // Allow input if within limit
  }

  // Prevent input for non-digit characters
  e.preventDefault();
}

function onlyNumbersh(e) {
  var code = e.charCode || e.keyCode;
  var input = e.target.value;

  // Allow digits (0-9) and check input length
  if (code >= 48 && code <= 57) {
    // Check if the length of the input is within 6 to 10 characters
    if (input.length <= 10) {
      return; // Allow input if within limit
    } else {
      e.preventDefault(); // Prevent further input if outside the limit
    }
  } else {
    // Prevent input for non-digit characters
    e.preventDefault();
  }
}


  document.getElementById("cat").value = '';

  function getcategory() {

    var cat_arry = <?php echo empty($cat_array_json) ? '[]' : $cat_array_json; ?>;

    // Ensure the 'catgroup' and 'cat' elements exist
    var catgroupElement = document.getElementById("catgroup");
    var categoryDropdown = document.getElementById("cat");

    if (!catgroupElement || !categoryDropdown) {
      console.error("Required elements are missing from the DOM");
      return;
    }

    var catgroup11 = catgroupElement.value;


    // Clear existing options in the dropdown
    categoryDropdown.innerHTML = '';

    var l = cat_arry.length;


    var option = new Option("Select", "");
    option.title = "Select";
    categoryDropdown.options.add(option);
    for (var i = 0; i < l; i++) {
      if (cat_arry[i].catgroup === catgroup11) {
        var type = cat_arry[i].type;

        var type1 = type.split(",");
        for (var j = 0; j < type1.length; j++) {
          var type2 = type1[j];
          var option = new Option(type2, type2);
          option.title = type;
          categoryDropdown.options.add(option);
        }


      }
    }
  }


  function onlyNumbers123(e) {
  var code = e.charCode || e.keyCode;
  var input = e.target.value;


  if ((code >= 65 && code <= 90) || (code >= 97 && code <= 122)||(code >= 48 && code <= 57)||(code == 32) ) {
    return;
  }
 e.preventDefault();
}



  consumptionAcContainer.style.display = 'none';

  function checkUsage() {
    const usageDropdown = document.getElementById('iusage').value;
    const consumptionAcContainer = document.getElementById('expca');
    const consumptionAcContainerl = document.getElementById('expcal');

    const salesAcContainer = document.getElementById('sac');
    const salesAcContainerl = document.getElementById('sacl');


    const cogsAcContainer = document.getElementById('cogsac');
    const cogsAcContainerl = document.getElementById('cogsacl');



    const salesreturnAcContainer = document.getElementById('sractd');
    const salesreturnAcContainerl = document.getElementById('sractdl');


    if (usageDropdown === 'General Consumption') {
      consumptionAcContainer.style.display = 'block';
      consumptionAcContainerl.style.display = 'block';
    } else {
      consumptionAcContainer.style.display = 'none';
      consumptionAcContainerl.style.display = 'none';
    }


    if ((usageDropdown === 'Sale') || (usageDropdown === 'Produced or Sale') || (usageDropdown === 'Rejected or Sale') || (usageDropdown === 'Produced or Sale or Rejected')) {
      salesAcContainer.style.display = 'block';
      salesAcContainerl.style.display = 'block';
      cogsAcContainer.style.display = 'block';
      cogsAcContainerl.style.display = 'block';
      salesreturnAcContainer.style.display = 'block';
      salesreturnAcContainerl.style.display = 'block';
    } else {
      salesAcContainer.style.display = 'none';
      salesAcContainerl.style.display = 'none';
      cogsAcContainer.style.display = 'none';
      cogsAcContainerl.style.display = 'none';
      salesreturnAcContainer.style.display = 'none';
      salesreturnAcContainerl.style.display = 'none';
    }

  }

</script>
</script>
