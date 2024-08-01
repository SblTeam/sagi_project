@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
@php
    $isEdit = isset($contactDetail);
    if($isEdit && $contactDetail->auth_flag2==1){
      $auth_edit="readOnly";
    }else{
      $auth_edit="";
    }
 @endphp
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Sales Module /</span> Profile Master
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account Details</a></li>
      {{-- <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-notifications')}}"><i class="bx bx-universal-access me-1"></i> Profile Access</a></li> --}}
     </ul>
    <div class="card mb-4">
      <h5 class="card-header p-3"><strong style="font-size:25px">Profile Details</strong></h5>
      <!-- Account -->
      <hr class="my-0">
      <div class="card-body">
        <form action="{{ $isEdit ? route('masters-SupplyMaster.update', $contactDetail->id) : route('masters-SupplyMaster.store') }}" id="formAccountSettings" method="POST" enctype="multipart/form-data" onsubmit="$('#submit').prop('disabled', true);">
          @csrf
          @method('post')
          <div class="row">
            <div class="mb-1 col-md-6">
              <label for="name" class="form-label">Profile Name <sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" {{$auth_edit}} id="supplyerName" name="name" value="{{ old('name', $isEdit ? $contactDetail->name : '') }}" placeholder='Enter Name' autofocus />
          @error('name')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
            </div>
            <div class="mb-1 col-md-6">
              <label for="company" class="form-label">Company Name <sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" {{$auth_edit}} name="company" id="companyName" value="{{ old('company', $isEdit ? $contactDetail->company : '') }}" placeholder='Enter CompanyName' />
          @error('company')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
            </div>
            <div class="mb-1 col-md-6">
              <label for="address" class="form-label">Address <sup style="color:red;">&#9733;</sup></label>
              <!-- <input type="text" class="form-control" id="address" name="address" placeholder="Address" /> -->
            <textarea name="address" id="address" cols="30" {{$auth_edit}} rows="2" class="form-control"  placeholder="Address">{{ old('address', $isEdit ? $contactDetail->address : '') }}</textarea>
          @error('address')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
          </div>
            <div class="mb-1 col-md-6">
              <label for="place" class="form-label">Place <sup style="color:red;">&#9733;</sup></label>
              <input type="text" class="form-control" id="place" {{$auth_edit}} name="place" value="{{ old('place', $isEdit ? $contactDetail->place : '') }}" placeholder="Enter Place" />
          @error('place')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
            <div class="mb-1 col-md-6">
              <label class="form-label" for="pan">Pan <sup style="color:red;">&#9733;</sup></label>
                <input type="text" id="pan" name="pan" {{$auth_edit}} class="form-control" placeholder="Enter PanNumber" value="{{ old('pan', $isEdit ? $contactDetail->pan : '') }}"/>
            @error('pan')
                <div class="alert alert-danger p-1">{{ $message }}</div>
            @enderror
          </div>
            <div class="mb-1 col-md-6">
              <label for="email" class="form-label">E-mail <sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" id="email" name="email" value="{{ old('email', $isEdit ? $contactDetail->email : '') }}" placeholder="Enter Email" />
          @error('email')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
         </div>
            <div class="mb-1 col-md-6">
              <label class="form-label" for="phone">Phone Number <sup style="color:red;">&#9733;</sup></label>
                <input type="text" id="phoneNumber" name="phone" class="form-control" placeholder="Enter PhoneNumber" value="{{ old('phone', $isEdit ? $contactDetail->phone : '') }}"/>
            @error('phone')
                <div class="alert alert-danger p-1">{{ $message }}</div>
            @enderror
              </div>
            <div class="mb-1 col-md-6">
              <label class="form-label" for="state">State <sup style="color:red;">&#9733;</sup></label>
              <select id="state" class="form-control search_data" name="state">
                <option value="" {{ old('state', $isEdit ? $contactDetail->state : '') == '' ? 'selected' : '' }}>Select</option>
                @foreach ($statecodes as $snames)
                @if($isEdit && $contactDetail->auth_flag2==1)
                @if($contactDetail->state==$snames)
                <option value="{{$snames}}" {{ old('state', $isEdit ? $contactDetail->state : '') == $snames ? 'selected' : '' }}>{{$snames}}</option>
                @endif
                @else
                <option value="{{$snames}}" {{ old('state', $isEdit ? $contactDetail->state : '') == $snames ? 'selected' : '' }}>{{$snames}}</option>
                @endif
                @endforeach
               </select>
          @error('state')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
            </div>
            <div class="mb-1 col-md-6">
              <label class="form-label" for="gstin">GST <sup style="color:red;">&#9733;</sup></label>
                <input type="text" id="gst" name="gstin" {{$auth_edit}} class="form-control" placeholder="Enter GSTNumber" value="{{ old('gstin', $isEdit ? $contactDetail->gstin : '') }}"/>
           @error('gstin')
                <div class="alert alert-danger p-1">{{ $message }}</div>
            @enderror
          </div>
          <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4" >
          <div id="imgblock"><div id='rem' style="float:left;position:relative;">
          <img src="/assets/img/avatars/file.png" alt="demo file" class="d-block rounded"  height="100" width="100" id="uploadedAvatar@0"  /></div></div>
          <div class="button-wrapper">
            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
              <span class="d-none d-sm-block">Upload Files</span>
              <i class="bx bx-upload d-block d-sm-none"></i>
              <input type="file" id="upload" class="account-file-input" name="files_path[]" hidden accept=".png, .jpeg, .jpg, .pdf, .doc, .docx, .xls, .xlsx" value="{{old("files_path_dum", $isEdit ? $contactDetail->files_path : '')}}" multiple/>
              <input type="hidden" id="fileupload" class="account-file-input" name="files_path_dum" value="{{old("files_path_dum", $isEdit ? $contactDetail->files_path : '')}}"/>
              <input type="hidden" id="dummypath@0" name="dummypath[]" value=""/>
            </label>
            {{-- <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
              <i class="bx bx-reset d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Reset</span>
            </button> --}}
          </div>
      @error('files_path.*')
          <div class="alert alert-danger p-1">{{ $message }}</div>
      @enderror
        </div>
      </div>
          <div class="mt-2">
            <button type="submit" id="submit" class="btn btn-primary me-2">Save changes</button>
            <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('masters-SupplyMaster')}}'">Cancel</button>
          </div>
        </form>
      </div>
      <!-- /Account -->
    </div>
  </div>
</div>
@if (old('files_path_dum', $isEdit ? $contactDetail->files_path : ''))
<script>
  var oldFilePath='{{old('files_path_dum', $isEdit ? $contactDetail->files_path : '')}}';
let oldfile=oldFilePath.split(',');
  if(oldfile.length>0){
      if (document.getElementById('rem')) {
        document.getElementById('rem').remove();
      }    let imgBlock = document.querySelector('#imgblock');
let kt=0;
      for (let i = 0; i < oldfile.length; i++) {
        if(oldfile[i]!=''){
        console.log(oldFilePath);
        kt++;
        var accountUserImage = document.createElement('img');
        var div_block = document.createElement('div');
        var dummyblock = document.createElement('input');
        dummyblock.type='hidden';
        dummyblock.name='dummypath[]';
        dummyblock.value=oldfile[i];
        dummyblock.id='dummypath@'+ kt;
          accountUserImage.className = 'd-block rounded';
          accountUserImage.id = 'uploadedAvatar@' + kt;
          accountUserImage.width = 100;
          accountUserImage.height = 100;
          accountUserImage.style.padding = '5px';

          let lastDotIndex = oldfile[i].lastIndexOf(".");
          let afterLastDot = oldfile[i].substring(lastDotIndex + 1);
          console.log("afterLastDot",afterLastDot);
          let imgdata='';
          if(afterLastDot=='pdf'){imgdata='/assets/img/avatars/pdf.png';}
          else if(afterLastDot=='xlsx'){imgdata='/assets/img/avatars/excel.jpg';}
          else if(afterLastDot=='doc' || afterLastDot=='docx'){imgdata='/assets/img/avatars/doc.png';}
          else{imgdata=oldfile[i];}

          accountUserImage.src = imgdata;
          accountUserImage.onclick = function (event) {
          window.open(                                                                                                                                      (oldfile[i]), '_blank', `width=800,height=600`);}
        var cross = document.createElement('span');
        cross.className = 'bx bx-message-square-x';
        cross.style.cssText = 'position:absolute;top:0;right:0;color:red;';
        cross.onclick = function (event) {
          event.target.closest('div').remove();
        };
        div_block.style.cssText = 'float:left;position:relative;';
        div_block.id = 'blockid@' + kt;
        div_block.appendChild(accountUserImage);
        div_block.appendChild(cross);
        div_block.appendChild(dummyblock);
        imgBlock.appendChild(div_block);
      }}
  }
</script>
@endif 
@endsection
