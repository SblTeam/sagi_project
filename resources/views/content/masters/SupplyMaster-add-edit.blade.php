@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('content')
@php
    $isEdit = isset($contactDetail);
    if($isEdit && $itemcount>0){
      $prof_edit="readOnly";
    }else{
      $prof_edit="";
    }
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
              <input class="form-control" type="text" {{$auth_edit}}  id="supplyerName" name="name" value="{{ old('name', $isEdit ? $contactDetail->name : '') }}" placeholder='Enter Name' autofocus />
          @error('name')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
            </div>
            <div class="mb-1 col-md-6">
              <label for="company" class="form-label">Company Name <sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" {{$prof_edit}} {{$auth_edit}} name="company" id="companyName" value="{{ old('company', $isEdit ? $contactDetail->company : '') }}" placeholder='Enter CompanyName' />
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
              <label for="holder_name" class="form-label">Bank Holder Name <sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" id="holder_name" name="holder_name" value="{{ old('holder_name', $isEdit ? $contactDetail->holder_name : '') }}" placeholder="Enter Bank Holder Name" />
          @error('holder_name')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
         </div>
            <div class="mb-1 col-md-6">
              <label class="form-label" for="account_no">Bank Account Number <sup style="color:red;">&#9733;</sup></label>
                <input type="text" id="account_no" name="account_no" class="form-control" placeholder="Enter Bank Account Number" value="{{ old('account_no', $isEdit ? $contactDetail->account_no : '') }}"/>
            @error('account_no')
                <div class="alert alert-danger p-1">{{ $message }}</div>
            @enderror
              </div>
              <div class="mb-1 col-md-6">
              <label for="bank_name" class="form-label">Bank Name <sup style="color:red;">&#9733;</sup></label>
              <input class="form-control" type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $isEdit ? $contactDetail->bank_name : '') }}" placeholder="Enter Bank Name" />
          @error('bank_name')
              <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
         </div>
            <div class="mb-1 col-md-6">
              <label class="form-label" for="branch_name">Branch Name <sup style="color:red;">&#9733;</sup></label>
                <input type="text" id="branch_name" name="branch_name" class="form-control" placeholder="Enter Branch Name" value="{{ old('branch_name', $isEdit ? $contactDetail->branch_name : '') }}"/>
            @error('branch_name')
                <div class="alert alert-danger p-1">{{ $message }}</div>
            @enderror
              </div>
              <div class="mb-1 col-md-6">
              <label class="form-label" for="IFSC">IFSC Code <sup style="color:red;">&#9733;</sup></label>
                <input type="text" id="IFSC" name="IFSC" class="form-control" placeholder="Enter IFSC Code" value="{{ old('IFSC', $isEdit ? $contactDetail->IFSC : '') }}"/>
            @error('IFSC')
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
          <div class="mb-1 col-md-6">
          <label class="form-label" for="logo">Logo <sup style="color:red;">&#9733;</sup></label>
        <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4" >
          <div id="imgblocklogo"><div id='remlogo' style="float:left;position:relative;">
          <img src="{{asset('assets/img/avatars/file.png')}}" alt="demo file" class="d-block rounded"  height="100" width="100" id="uploadedAvatarlogo@0"  /></div></div>
          <div class="button-wrapper">
            <label for="uploadlogo" class="btn btn-primary me-2 mb-4" tabindex="0">
              <span class="d-none d-sm-block">Upload logo</span>
              <i class="bx bx-upload d-block d-sm-none"></i>
              <input type="file" id="uploadlogo" class="account-file-input1" name="files_pathlogo" hidden accept=".png, .jpeg, .jpg" value="{{old('files_pathlogo_dum', $isEdit ? $contactDetail->logo : '')}}"/>
              <input type="hidden" id="fileuploadlogo" class="account-file-input1" name="files_pathlogo_dum" value="{{old('files_pathlogo_dum', $isEdit ? $contactDetail->logo : '')}}"/>
            </label>
          </div>
        </div>
      </div>
      @error('files_pathlogo')
                <div class="alert alert-danger p-1">{{ $message }}</div>
            @enderror
      </div>     
          <div class="mb-1 col-md-6">
          <label class="form-label" for="logo">Documents</label>
        <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-4" >
          <div id="imgblock"><div id='rem' style="float:left;position:relative;">
          <img src="{{asset('assets/img/avatars/file.png')}}" alt="demo file" class="d-block rounded"  height="100" width="100" id="uploadedAvatar@0"  /></div></div>
          <div class="button-wrapper">
            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
              <span class="d-none d-sm-block">Upload Files</span>
              <i class="bx bx-upload d-block d-sm-none"></i>
              <input type="file" id="upload" class="account-file-input" name="files_path[]" hidden accept=".png, .jpeg, .jpg, .pdf, .doc, .docx, .xls, .xlsx" value="{{old('files_path_dum', $isEdit ? $contactDetail->files_path : '')}}" multiple/>
              <input type="hidden" id="fileupload" class="account-file-input" name="files_path_dum" value="{{old('files_path_dum', $isEdit ? $contactDetail->files_path : '')}}"/>
            </label>
          </div>
        </div></div>
      </div>
          <div class="mt-2 buttons-center" >
            <button type="submit" id="submit" class="btn btn-primary me-2">Save changes</button>
            <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('masters-SupplyMaster')}}'">Cancel</button>
          </div>
        </form>
      </div>
      <!-- /Account -->
    </div>
  </div>
</div>
<script>
@if (old('files_path_dum', $isEdit ? $contactDetail->files_path : ''))
  //var oldFilePath="{{old('files_path_dum', $isEdit ? $contactDetail->files_path : '')}}";
  @if(old('files_path_dum') && $isEdit)
  var oldFilePath="{{$contactDetail->files_path}}";
  @else
  var oldFilePath="{{old('files_path_dum', $isEdit ? $contactDetail->files_path : '')}}";
  @endif
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
          if(afterLastDot=='pdf'){imgdata='{{asset("assets/img/avatars/pdf.png")}}';}
          else if(afterLastDot=='xlsx' || afterLastDot=='xls'){imgdata='{{asset("assets/img/avatars/excel.jpg")}}';}
          else if(afterLastDot=='doc' || afterLastDot=='docx'){imgdata='{{asset("assets/img/avatars/doc.png")}}';}
          else{imgdata='/sagi_project/'+oldfile[i];}

          accountUserImage.src = imgdata;
          accountUserImage.onclick = function (event) {
          window.open(('/sagi_project/'+oldfile[i]), '_blank', `width=800,height=600`);}
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
  @endif
  @if (old('files_pathlogo_dum', $isEdit ? $contactDetail->logo : ''))
  @if(old('files_pathlogo_dum') && $isEdit)
  var oldFilePath1="{{$contactDetail->logo}}";
  @else
  var oldFilePath1="{{old('files_pathlogo_dum', $isEdit ? $contactDetail->logo : '')}}";
  @endif
let oldfile1=oldFilePath1.split(',');
 
      if (document.getElementById('remlogo')) {
        document.getElementById('remlogo').remove();
      } 
      let imgBlock1 = document.querySelector('#imgblocklogo');
        if(oldfile1!=''){
        var accountUserImage1 = document.createElement('img');
        var div_block1 = document.createElement('div');
        var dummyblock1 = document.createElement('input');
        dummyblock1.type='hidden';
        dummyblock1.name='dummypathlogo';
        dummyblock1.value=oldfile1[0];
        dummyblock1.id='dummypathlogo@0';
          accountUserImage1.className = 'd-block rounded';
          accountUserImage1.id = 'uploadedAvatarlogo@0';
          accountUserImage1.width = 100;
          accountUserImage1.height = 100;
          accountUserImage1.style.padding = '5px';

          imgdata1='/sagi_project/'+oldfile1[0];

          accountUserImage1.src = imgdata1;
          accountUserImage1.onclick = function (event) {
          window.open(('/sagi_project/'+oldfile1[0]), '_blank', `width=800,height=600`);}
        div_block1.style.cssText = 'float:left;position:relative;';
        div_block1.id = 'blockidlogo@0';
        div_block1.appendChild(accountUserImage1);
        div_block1.appendChild(dummyblock1);
        imgBlock1.appendChild(div_block1);
      }
@endif
document.addEventListener('DOMContentLoaded',function(e){(function(){
  let imgBlock1 = document.querySelector('#imgblocklogo');
  const fileInput1 = document.querySelector('.account-file-input1');
    fileInput1.onchange = () => {
      if((fileInput1.files[0].size / (1024 * 1024))>3){
        alert("Logo File size should be less than 3mb");
        document.querySelector('#uploadlogo').value='';
        return false;
      }
      if (document.getElementById('remlogo')){document.getElementById('remlogo').remove();}
      if(document.getElementById('blockidlogo@0')){document.getElementById('blockidlogo@0').remove();}
      let accountUserImage = document.createElement('img');
      let div_block = document.createElement('div');
      let fileType = fileInput1.files[0].type;
      let fileName = fileInput1.files[0].name;
      let imageTypes = /^image\//;
      if (fileInput1.files) {
        accountUserImage.className = 'd-block rounded';
        accountUserImage.id = 'uploadedAvatarlogo@0';
        accountUserImage.width = 100;
        accountUserImage.height = 100;
        accountUserImage.style.padding = '5px';
        document.querySelector('#fileuploadlogo').value = window.URL.createObjectURL(fileInput1.files[0]);
        if (imageTypes.test(fileType)){
          accountUserImage.src = window.URL.createObjectURL(fileInput1.files[0]);
          accountUserImage.onclick = function (event) {window.open(window.URL.createObjectURL(fileInput1.files[0]), '_blank', `width=800,height=600`);};}}
      div_block.style.cssText = 'float:left;position:relative;';
      div_block.id = 'blockidlogo@0';
      div_block.appendChild(accountUserImage);
      imgBlock1.appendChild(div_block);
  }})();
});
/**
 * multi image settings
 */

var kt = 0;
var filesData = [];
document.addEventListener('DOMContentLoaded', function (e) {
  (function () {
    const deactivateAcc = document.querySelector('#formAccountDeactivation');
    const fileInput = document.querySelector('.account-file-input'),
      resetFileInput = document.querySelector('.account-image-reset');
    let imgBlock = document.querySelector('#imgblock');
    fileInput.onchange = () => {
      datahandlefun();
    };
    const datahandlefun = () => {
      var ttl=0;
      for (let i = 0; i < fileInput.files.length; i++) {
        ttl+=(fileInput.files[i].size / (1024 * 1024));
      }if(ttl>5){
        alert("All Files size should be less than 8mb");
        document.querySelector('#upload').value='';
        document.querySelector('#fileupload').value='';
        return false;
      }
      if (document.getElementById('rem')) {
        document.getElementById('rem').remove();
      }
      for (let i = 0; i < fileInput.files.length; i++) {
        kt++;
        var accountUserImage = document.createElement('img');
        var div_block = document.createElement('div');

        var fileType = fileInput.files[i].type;
        var fileName = fileInput.files[i].name;
        filesData.push(fileInput.files[i]);
        console.log(fileType + '//' + fileName);
        var imageTypes = /^image\//;
        var docTypes =
          /^(application\/msword|application\/vnd\.openxmlformats-officedocument\.wordprocessingml\.document)$/;
        var pdfTypes = /^application\/pdf$/;
        var excelTypes =
          /^(application\/vnd\.ms-excel|application\/vnd\.openxmlformats-officedocument\.spreadsheetml\.sheet)$/;
        var docExtensions = /\.(doc|docx)$/i;
        var excelExtensions = /\.(xls|xlsx)$/i;

        if (fileInput.files[i]) {
          accountUserImage.className = 'd-block rounded';
          accountUserImage.id = 'uploadedAvatar@' + kt;
          accountUserImage.width = 100;
          accountUserImage.height = 100;
          accountUserImage.style.padding = '5px';
          document.querySelector('#fileupload').value =
            document.querySelector('#fileupload').value + ',' + window.URL.createObjectURL(fileInput.files[i]);
          if (imageTypes.test(fileType)) {
            accountUserImage.src = window.URL.createObjectURL(fileInput.files[i]);
            accountUserImage.onclick = function (event) {
              window.open(window.URL.createObjectURL(fileInput.files[i]), '_blank', `width=800,height=600`);
            };
          } else if (docTypes.test(fileType) || docExtensions.test(fileName)) {
            accountUserImage.src = '/sagi_project/assets/img/avatars/doc.png';
            accountUserImage.onclick = function (event) {
              window.open(window.URL.createObjectURL(fileInput.files[i]), '_blank', `width=800,height=600`);
            };
          } else if (pdfTypes.test(fileType)) {
            accountUserImage.src = '/sagi_project/assets/img/avatars/pdf.png';
            accountUserImage.onclick = function (event) {
              window.open(window.URL.createObjectURL(fileInput.files[i]), '_blank', `width=800,height=600`);
            };
          } else if (excelTypes.test(fileType) || excelExtensions.test(fileName)) {
            accountUserImage.src = '/sagi_project/assets/img/avatars/excel.jpg';
            accountUserImage.onclick = function (event) {
              window.open(window.URL.createObjectURL(fileInput.files[i]), '_blank', `width=800,height=600`);
            };
          } else {
            alert('Unsupported file type.');
          }
        }
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
        imgBlock.appendChild(div_block);
      }
      var fileList = new DataTransfer();
      for (var i = 0; i < filesData.length; i++) {
        fileList.items.add(filesData[i]);
      }
      console.log(fileList);
      fileInput.files = fileList.files;
      console.log(fileInput.files);
    };

    // resetFileInput.onclick = () => {
    //   fileInput.value = '';
    //   accountUserImage.src = resetImage;
    // };
    // }
  })();
});

</script>
@endsection
