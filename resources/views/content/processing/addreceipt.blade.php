@extends('layouts/contentNavbarLayout')
@section('title', 'Account settings - Account')
@section('content')
@php
    $defaultDate = date('Y-m-d');
    $inputDate = old('date', $defaultDate);
@endphp
<head>
  <style>
    .p-0{
      padding: 0px 3px !important;
    }
    .br{
      border:none;
    }
    .loading-spinner {
    border: 8px solid #f3f3f3; 
    border-top: 8px solid #3498db; 
    border-radius: 50%;
    width: 70px;
    height: 70px;
    animation: spin 1s linear infinite;
    position: absolute;
    top: 50%;
    left: 50%;
    z-index: 5;
    margin-left: -25px; 
    margin-top: -25px; 
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.spinm{
    width:30%;
    height:35%;
    background-color:#0b090978;
    border-radius:5px;
    position: absolute;
    top: 50%;
    z-index: 5;
    left: 42%;
}
#loadingSpinner{
    visibility:hidden;
}
  </style>
</head>
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Sales Processing /</span> Sales Receipt
</h4>

<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <h5 class="card-header p-3"><strong style="font-size:25px">Add Sales Receipt</strong></h5>
      <!-- Account -->
      <hr class="my-0">
      <div class="card-body" >
        <form action="{{route('processing-receipt.store')}}" id="formAccountSettings" method="POST" enctype="multipart/form-data" onsubmit="return checklist();$('#submit').prop('disabled', true);">
          @csrf
          @method('post')
          <div class="row" style="justify-content:center">
            <div class="mb-1 col-md-2">
              <label for="name" class="form-label">Date <sup style="color:red;">&#9733;</sup></label>
              <input type="date" id="date" name="date" value="{{ $inputDate }}" class="form-control"   style="width: 200px" />
            </div>
            <div class="mb-1 col-md-3">
              <label class="form-label" for="party">Distributor <sup style="color:red;">&#9733;</sup></label>
              <select id="party" class="form-control search_data" name="party" onchange="getid()">
                <option value="" {{ old('party') == '' ? 'selected' : '' }}>Select</option>
                @foreach($oc_cobi as $cobi)
                <option value="{{$cobi->party}}" {{ old('party') == $cobi->party ? 'selected' : '' }}>{{$cobi->party}}</option>
                @endforeach
               </select>
          @error('party')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
            </div>
            <div class="mb-1 col-md-2">
              <label class="form-label" for="partyid">Distributor ID <sup style="color:red;">&#9733;</sup></label>
              <select id="partyid" class="form-control search_data" name="partyid" onchange="getname()">
                <option value="" {{ old('partyid') == '' ? 'selected' : '' }}>Select</option>
                @foreach($oc_cobi as $cobi)
                <option value="{{$cobi->partycode}}" {{ old('partyid') == $cobi->partycode ? 'selected' : '' }}>{{$cobi->partycode}}</option>
                @endforeach
               </select>
          @error('partyid')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
            </div>
            <div class="mb-1 col-md-2">
              <label for="Docno" class="form-label">Doc No. <sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text"  id="Docno" name="Docno" value="{{ old('Docno', '') }}" placeholder='Enter Doc No.' autofocus />
          @error('Docno')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
            </div>
            <!-- <div class="mb-1 col-md-2">
              <label class="form-label" for="state">Location <sup style="color:red;">&#9733;</sup></label>
              <select id="state" class="form-control search_data" name="state">
                <option value="" {{ old('state', '') == '' ? 'selected' : '' }}>Select</option>
               </select>
          @error('state')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
            </div> -->
          </div>
            <div style="height:20px"></div>
<div class="row" style="justify-content:center">
        <div class="mb-1 col-md-2">
              <label class="form-label" for="paymentmethod">Receipt Method <sup style="color:red;">&#9733;</sup></label>
              <select id="paymentmethod" class="form-control" name="paymentmethod" onChange="paymentmethodfun(this.value);">
                <option value="" {{ old('paymentmethod', '') == '' ? 'selected' : '' }}>Select</option>
                <option value="Receipt" {{ old('paymentmethod', '') == 'Receipt' ? 'selected' : '' }}>Receipt</option>
               </select>
          @error('paymentmethod')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-1 col-md-2">
              <label class="form-label" for="choice">Choice <sup style="color:red;">&#9733;</sup></label>
              <select id="choice" class="form-control" name="choice">
                <option value="" {{ old('choice', '') == '' ? 'selected' : '' }}>Select</option>
                @if(old('choice')) <option value="{{old('choice')}}" selected>{{old('choice')}}</option>@endif
               </select>
          @error('choice')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-1 col-md-2">
              <label class="form-label" for="paymentmode">Reception Mode <sup style="color:red;">&#9733;</sup></label>
              <select id="paymentmode" class="form-control" name="paymentmode" onchange="receptionmode(this);">
                <option value="" {{ old('', '') == '' ? 'selected' : '' }}>Select</option>
                <option value="Cash" {{ old('paymentmode', '') == 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="Cheque" {{ old('paymentmode', '') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                <option value="Transfer" {{ old('paymentmode', '') == 'Transfer' ? 'selected' : '' }}>UPI/Transfer</option>
               </select>
          @error('paymentmode')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-1 col-md-3" id="mcheque" @if(old('paymentmode') && old('paymentmode')=='Cheque') style="display:'';" @else style="display:none;" @endif>
              <label class="form-label" for="cheque">Cheque No. <sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text"  name="cheque" id="cheque" value="{{ old('cheque', '') }}"/>
          @error('cheque')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-1 col-md-3" id="mupi" @if(old('paymentmode') && old('paymentmode')=='Transfer') style="display:'';" @else style="display:none;" @endif>
              <label class="form-label" for="upi">UPI No. <sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text"  name="upi" id="upi" value="{{ old('upi', '') }}"/>
          @error('upi')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
    <div class="mb-1 col-md-2 p-0" id="mcheckdate" @if(old('paymentmode') && old('paymentmode')=='Cheque') style="display:'';" @else style="display:none;" @endif>
              <label for="checkdate" class="form-label">Cheque Date </label>
              <input type="date" id="checkdate" name="checkdate" value="{{ $inputDate }}" class="form-control"   style="width: 100%" />
    </div>
</div>            
<div style="height:20px"></div>

<div class="row" style="justify-content:center" id="cob">
      <div class="mb-1 col-md-1 p-0"><label for="cobiDate" class="form-label">Date </label></div>
      <div class="mb-1 col-md-2 p-0 text-center"><label for="cobi" class="form-label">COBI </label></div>
      <div class="mb-1 col-md-1 p-0"><label for="actualamt" class="form-label">Actual </label></div>
      <div class="mb-1 col-md-1 p-0"><label for="penbal" class="form-label">Balance </label></div>
      <div class="mb-1 col-md-2 p-0"><label for="amountreceived" class="form-label">Received </label></div>
</div> 
<div id="wrappcobi"></div>                   
<div class="row" style="justify-content:center" id="ttl">
      <div class="mb-1 col-md-1 p-0"><label for="name" class="form-label">Total </label></div>
      <div class="mb-1 col-md-2 p-0"></div>
      <div class="mb-1 col-md-1 p-0">
              <input class="form-control br" type="text" id="ttlact" name="ttlact" value="{{ old('ttlact', '0') }}" readOnly/>
      </div>
      <div class="mb-1 col-md-1 p-0">
              <input class="form-control br" type="text" id="ttlbal" name="ttlbal" value="{{ old('ttlbal', '0') }}" readOnly/>
      </div>
      <div class="mb-1 col-md-2 p-0">
              <input class="form-control br" type="text" id="nonclear" name="nonclear" value="{{ old('nonclear', '0') }}" readOnly/>
              <input  type="hidden" id="nonclear_dum" name="nonclear_dum" value="0" readOnly/>
              <input  type="hidden" id="recamt" name="recamt" value="0" readOnly/>
              <label class="form-label">Rest Amount </label>
      </div>
</div>    
@error('amountreceived')
<div class="alert alert-danger p-1">{{ $message }}</div>
@enderror        
<div class="row" style="justify-content:center">
      <div class="mb-1 col-md-4 p-0">
      <label class="form-label">Narration</label>
      <textarea class="form-control" name="narration" id="narration"></textarea>
      </div>
</div>        
<div style="height:20px"></div> 
 <div class="mt-2" style="display: flex; justify-content: center; gap: 10px;">
  <input type="text" id="check_cobi"  value='Check COBIs' class="btn btn-primary me-2" style="width:10%;" readOnly onClick="check_cobis()">
 </div>    
<div style="height:20px"></div> 
          <div class="mt-2" style="display: flex; justify-content: center; gap: 10px;">
            <button type="submit" id="save" class="btn btn-primary me-2" disabled="true">Receive</button>
            <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('processing-receipt')}}'">Cancel</button>
          </div>
<div style="height:20px"></div> 
        </form>
      </div>
    </div>
  </div>
</div>
@if(session('Fail') || session('Success'))
        <div class="bs-toast toast fade show @if(session('Fail'))bg-danger @else bg-info @endif" role="alert" aria-live="assertive" aria-atomic="true" style="position:absolute;top:55%;left:45%">
          <div class="toast-header">
            <i class='bx bx-bell me-5' style="color:balck"></i>
            <div class="me-auto fw-medium" style="color:balck">Alert</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body pt-5 pb-5 text-center">
            {{ session('Fail').session('Success') }}
          </div>
        </div>
@endif
<div class="spinm" id="loadingSpinner"><div class="loading-spinner" ></div></div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
     let today = new Date();
     let day = ("0" + today.getDate()).slice(-2);
     let month = ("0" + (today.getMonth() + 1)).slice(-2);
     let todayDate = today.getFullYear() + "-" + month + "-" + day;
     document.getElementById("date").setAttribute("max", todayDate);
     let fourDaysAgo = new Date(today);
     fourDaysAgo.setDate(today.getDate() - 4);
     let day1 = ("0" + fourDaysAgo.getDate()).slice(-2);
     let month1 = ("0" + (fourDaysAgo.getMonth() + 1)).slice(-2);
     let fourDaysAgoDate = fourDaysAgo.getFullYear() + "-" + month1 + "-" + day1;
     document.getElementById("date").setAttribute("min", fourDaysAgoDate);
     document.getElementById("checkdate").setAttribute("max", todayDate);
 });var index=0;
 function getid() {
        var l = document.getElementById("party").options.selectedIndex;
        document.getElementById("partyid").options[l].selected = "selected";
        $('.search_data').select2();
        assingdata();
    }
function getname() {
        var l = document.getElementById("partyid").options.selectedIndex;
        document.getElementById("party").options[l].selected = "selected";
        $('.search_data').select2();
        assingdata();
    }

function assingdata(){
    let party=document.getElementById("party").value;
    $.ajax({
        url:"{{route('processing-receipt.getinvoicewithparty')}}",
        type:'POST',
        data:{'party':party},
        success:function(data){var k=0;var html='';ttlact=0;ttlbal=0;
        data.data.forEach(function(rw){let inv=rw.invoice;let mn=data.penbal;
            if(Math.round(mn[inv])>0){
const dateObj = new Date(rw.date);
const day = String(dateObj.getDate()).padStart(2, '0');
const month = String(dateObj.getMonth() + 1).padStart(2, '0');
const year = dateObj.getFullYear();
const formattedDateCustom = `${day}-${month}-${year}`;
            html+=`<div class="row" style="justify-content:center" id="cob">
            <div class="mb-1 col-md-1 p-0"><input class="form-control br p-0" type="text"  name="cobiDate[]" value="{{ old('cobiDate.`+k+`', '`+formattedDateCustom+`') }}" readOnly/></div>
            <div class="mb-1 col-md-2 p-0"><input class="form-control br p-0 text-center" type="text"  id="cobi@`+k+`" name="cobi[]" value="{{ old('cobi.`+k+`', '`+rw.invoice+`') }}" readOnly/><input type="hidden"  id="co@`+k+`" name="co[]" value="{{ old('co.`+k+`', '`+rw.co+`') }}" readOnly/></div>
            <div class="mb-1 col-md-1 p-0"><input class="form-control br p-0" type="text"  name="actualamt[]" value="{{ old('actualamt.`+k+`', '`+Math.round(rw.grandtotal)+`') }}" readOnly/></div>
            <div class="mb-1 col-md-1 p-0"><input class="form-control br p-0" type="text"  name="penbal[]" id="penbal@`+k+`" value="{{ old('penbal.`+k+`', '`+Math.round(mn[inv])+`') }}" readOnly/></div>
            <div class="mb-1 col-md-2 p-0"><input class="form-control  p-0" style="width:65%;" type="text"  name="amountreceived[]" id="amountreceived@`+k+`" value="{{ old('amountreceived.`+k+`', '') }}" onkeyup="amtcheck(this)"/></div></div>`;
            ttlact+=Math.round(rw.grandtotal);ttlbal+=Math.round(mn[inv]);
             k++;
        }});
    document.getElementById('nonclear_dum').value=k;
    document.getElementById('wrappcobi').innerHTML=html;
    document.getElementById('ttlbal').value=Math.round(ttlbal);
    document.getElementById('ttlact').value=Math.round(ttlact);

        },
    error: function(xhr, status, error) {
        console.error("AJAX request failed:", status, error);
        console.log("Response:", xhr.responseText);
    }
    });
}
function amtcheck(ths){
    id=ths.id.split('@');
    let penbal= document.getElementById('penbal@'+id[1]).value;
    if(ths.value!='' && !ths.value.match(/^\d+$/)){
        alert("Please enter integer numbers");
        document.getElementById(ths.id).value='';
    }
    if(parseInt(penbal)<parseInt(ths.value)){
        alert("Enetered amount should not be grater then balance");
        document.getElementById(ths.id).value='';
    }let fv=ths.value;if(fv==''){fv=0;}let count=0;
    for(let i=0;i<document.getElementById('nonclear_dum').value;i++){let amt=document.getElementById('amountreceived@'+i).value;if(amt==''){amt=0;}count+=parseInt(amt);}
    document.getElementById('nonclear').value=parseInt(document.getElementById('ttlbal').value)-count;
    document.getElementById('recamt').value=count;
    document.getElementById('save').disabled=true;
}
function paymentmethodfun(paymentmethod) {
        var choice = document.getElementById('choice');
        let options="<option value=''>Select</option>";
        if (paymentmethod == "Advance") {options+="<option value='On A/C'>On A/C</option>";}
        else if (paymentmethod == "Receipt") {
           // options+="<option value='All'> All </option>";
            options+="<option value='COBIs'> COBIs </option>";
           // options+="<option value='CDN'> CDN </option>";
        }
        console.log(options);
        choice.innerHTML=options;
    }
function receptionmode(ths){
  document.getElementById('cheque').value='';
  document.getElementById('upi').value='';
        if(ths.value=='Cash'){
            document.getElementById('mupi').style.display="none";
            document.getElementById('mcheque').style.display="none";
            document.getElementById('mcheckdate').style.display="none";
        }
        else if(ths.value=='Cheque'){
            document.getElementById('mcheque').style.display="";
            document.getElementById('mcheckdate').style.display="";
            document.getElementById('mupi').style.display="none";
        }
        else if(ths.value=='Transfer'){
            document.getElementById('mupi').style.display="";
            document.getElementById('mcheque').style.display="none";
            document.getElementById('mcheckdate').style.display="none";
        }
    }
    function check_cobis(){
        let party=document.getElementById("party").value;
    if(party!=''){
        document.getElementById('loadingSpinner').style.visibility="visible";let hc=parseInt(document.getElementById('nonclear_dum').value)-1;
        for(let i=0;i<document.getElementById('nonclear_dum').value;i++){
    $.ajax({
        url:"{{route('processing-receipt.getinvoicewithparty_check')}}",
        type:'POST',
        data:{'party':party,'invoice':document.getElementById('cobi@'+i).value,'amt':document.getElementById('penbal@'+i).value},
        success:function(data){
            console.log(data);
            if(data.data==0){
            alert("Pending balanace missmatched :"+data.invoice+ " Please refresh receipt once");
            document.getElementById('loadingSpinner').style.visibility="hidden";
            hc++;
            return false;
        }
        if(hc==i){
            document.getElementById('save').disabled=false;
            document.getElementById('loadingSpinner').style.visibility="hidden";
            setTimeout(function(){document.getElementById('save').disabled = true;},20000);
        } 
        }
    });}if(parseInt(document.getElementById('nonclear_dum').value)==0){document.getElementById('loadingSpinner').style.visibility="hidden";}
    }
    }
   function checklist(){
    if(document.getElementById('party').value==''){alert('Please select Distributor ');return false;}
    if(document.getElementById('partyid').value==''){alert('Please select Distributor ID');return false;}
    if(document.getElementById('Docno').value==''){alert('Please enter Doc No');return false;}
    else if(!(/^[A-Za-z0-9-_:/\\/]{1,50}$/.test(document.getElementById('Docno').value))){alert('Doc number Only alphabets (A-Z), numbers (0-9), special characters (-/\:_) are allowed');document.getElementById('Docno').focus();document.getElementById('Docno').value='';return false;}
    if(document.getElementById('paymentmethod').value==''){alert('Please select Receipt Method');return false;}
    if(document.getElementById('choice').value==''){alert('Please select Choice');return false;}
    if(document.getElementById('paymentmode').value==''){alert('Please select Reception Mode');return false;}
    if(document.getElementById('paymentmode').value=='Cheque' && document.getElementById('cheque').value==''){alert('Please enter cheque no');return false;}
    else if(document.getElementById('paymentmode').value=='Cheque' && !(/^[0-9]{6}$/.test(document.getElementById('cheque').value))){alert('Cheque number should be 6 and Only numbers (0-9) are allowed');document.getElementById('cheque').focus();document.getElementById('cheque').value='';return false;}
    if(document.getElementById('paymentmode').value=='Transfer' && document.getElementById('upi').value==''){alert('Please enter Upi no');return false;}
    else if(document.getElementById('paymentmode').value=='Transfer' && !(/^[A-Za-z0-9-@._]{1,50}$/.test(document.getElementById('upi').value))){alert('Upi number Only alphabets (A-Z), numbers (0-9), special characters (-@._) are allowed');document.getElementById('upi').focus();document.getElementById('upi').value='';return false;}
    //et count=document.getElementById('nonclear_dum').value;
    let kt=0;
    for(let i=0;i<document.getElementById('nonclear_dum').value;i++){
      let amt=document.getElementById('amountreceived@'+i).value;
      if(!(/^[0-9]{1,50}$/.test(document.getElementById('amountreceived@'+i).value))){alert('Payable amount should be number');document.getElementById('amountreceived@'+i).value="";document.getElementById('amountreceived@'+i).focus();return false;}
      if(amt>0){kt++;}
    }if(kt==0){
      alert('Please pay the receipt for atleast one');return false;
    }
    return true;
    } 
</script>
@endsection
