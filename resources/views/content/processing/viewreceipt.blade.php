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
      <h5 class="card-header p-3"><strong style="font-size:25px">View Sales Receipt</strong></h5>
      <!-- Account -->
      <hr class="my-0">
      <div class="card-body" >
        <form action="{{route('processing-receipt.store')}}" id="formAccountSettings" method="POST" enctype="multipart/form-data" onsubmit="return checklist();$('#submit').prop('disabled', true);">
          @csrf
          @method('post')
          <div class="row" style="justify-content:center">
            <div class="mb-1 col-md-2">
              <label for="name" class="form-label">Date <sup style="color:red;">&#9733;</sup></label>
              @php
              $receipt->date=date("d-m-Y",strtotime($receipt->date));
              @endphp
              <input type="text" id="text"  value="{{ $receipt->date }}" class="form-control br"   style="width: 200px" readOnly/>
            </div>
            <div class="mb-1 col-md-3">
              <label class="form-label" for="party">Distributor <sup style="color:red;">&#9733;</sup></label>
              <input type="text" id="text"  value="{{ $receipt->party }}" class="form-control br"   style="width: 200px" readOnly/>
            </div>
            <div class="mb-1 col-md-2">
              <label class="form-label" for="partyid">Distributor ID <sup style="color:red;">&#9733;</sup></label>
              <input type="text" id="text"  value="{{ $receipt->partycode }}" class="form-control br"   style="width: 200px" readOnly/>
            </div>
            <div class="mb-1 col-md-2">
              <label for="Docno" class="form-label">Doc No. <sup style="color:red;">&#9733;</sup></label>
              <input type="text" id="doc"  value="{{ $receipt->doc_no }}" class="form-control br"   style="width: 200px" readOnly/>
            </div>
          </div>
            <div style="height:20px"></div>
<div class="row" style="justify-content:center">
        <div class="mb-1 col-md-2">
              <label class="form-label" for="paymentmethod">Receipt Method <sup style="color:red;">&#9733;</sup></label>
              <input type="text" id="doc"  value="{{ $receipt->paymentmethod }}" class="form-control br"   style="width: 200px" readOnly/>
        </div>
        <div class="mb-1 col-md-2">
              <label class="form-label" for="choice">Choice <sup style="color:red;">&#9733;</sup></label>
              <input type="text" id="doc"  value="{{ $receipt->choice }}" class="form-control br"   style="width: 200px" readOnly/>
        </div>
        <div class="mb-1 col-md-2">
              <label class="form-label" for="paymentmode">Reception Mode <sup style="color:red;">&#9733;</sup></label>
              <input type="text" id="doc"  value="{{ $receipt->paymentmode }}" class="form-control br"   style="width: 200px" readOnly/>
        </div>
        <div class="mb-1 col-md-3" id="mcheque" @if($receipt->paymentmode=='Cheque') style="display:'';" @else style="display:none;" @endif>
              <label class="form-label" for="cheque">Cheque No. <sup style="color:red;">&#9733;</sup></label>
              <input type="text" id="doc"  value="{{ $receipt->cheque }}" class="form-control br"   style="width: 200px" readOnly/>
        </div>
        <div class="mb-1 col-md-3" id="mupi" @if($receipt->paymentmode=='Transfer') style="display:'';" @else style="display:none;" @endif>
              <label class="form-label" for="upi">UPI No. <sup style="color:red;">&#9733;</sup></label>
              <input type="text" id="doc"  value="{{ $receipt->upi }}" class="form-control br"   style="width: 200px" readOnly/>
        </div>
    <div class="mb-1 col-md-2 p-0" id="mcheckdate" @if($receipt->paymentmode=='Cheque') style="display:'';" @else style="display:none;" @endif>
              <label for="checkdate" class="form-label">Cheque Date </label>
              @php
              $receipt->cdate=date("d-m-Y",strtotime($receipt->cdate));
              @endphp
              <input type="text" id="doc"  value="{{ $receipt->cdate }}" class="form-control br"   style="width: 200px" readOnly/>
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
@php
$ttlact=$ttlbal=$ttlrec=0;
@endphp
@foreach($receiptall as $data)
@php
$data->date=date("d-m-Y",strtotime($data->date));
@endphp
<div class="row" style="justify-content:center" id="cob">
      <div class="mb-1 col-md-1 p-0"><label for="cobiDate" class="form-label">{{$data->date}} </label></div>
      <div class="mb-1 col-md-2 p-0 text-center"><label for="cobi" class="form-label">{{$data->socobi}} </label></div>
      <div class="mb-1 col-md-1 p-0"><label for="actualamt" class="form-label">{{$data->actualamount}} </label></div>
      <div class="mb-1 col-md-1 p-0"><label for="penbal" class="form-label">{{$data->balance+$data->amountreceived}} </label></div>
      <div class="mb-1 col-md-2 p-0"><label for="amountreceived" class="form-label">{{$data->amountreceived}} </label></div>
</div> 
@php
$ttlact+=$data->actualamount;
$ttlbal+=$data->balance+$data->amountreceived;
$ttlrec+=$data->balance+$data->amountreceived-$data->amountreceived;
@endphp
@endforeach
<div id="wrappcobi"></div>                   
<div class="row" style="justify-content:center" id="ttl">
      <div class="mb-1 col-md-1 p-0"><label for="name" class="form-label">Total </label></div>
      <div class="mb-1 col-md-2 p-0"></div>
      <div class="mb-1 col-md-1 p-0">
              <input class="form-control br" type="text" id="ttlact" name="ttlact" value="{{ $ttlact }}" readOnly/>
      </div>
      <div class="mb-1 col-md-1 p-0">
              <input class="form-control br" type="text" id="ttlbal" name="ttlbal" value="{{ $ttlbal }}" readOnly/>
      </div>
      <div class="mb-1 col-md-2 p-0">
              <input class="form-control br" type="text" id="nonclear" name="nonclear" value="{{ $ttlrec }}" readOnly/>
              <label class="form-label">Rest Amount </label>
      </div>
</div>    
@error('amountreceived')
<div class="alert alert-danger p-1">{{ $message }}</div>
@enderror        
<div class="row" style="justify-content:center">
      <div class="mb-1 col-md-4 p-0">
      <label class="form-label">Narration</label>
      <textarea class="form-control br" name="narration" id="narration" readOnly>{{$receipt->remarks}}</textarea>
      </div>
</div>           
<div style="height:20px"></div> 
          <div class="mt-2" style="display: flex; justify-content: center; gap: 10px;">
            <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('processing-receipt')}}'">Cancel</button>
          </div>
<div style="height:20px"></div> 
        </form>
      </div>
    </div>
  </div>
</div>
<div class="spinm" id="loadingSpinner"><div class="loading-spinner" ></div></div>
    
@endsection
