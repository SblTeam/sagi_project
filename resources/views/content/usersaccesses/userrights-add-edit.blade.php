@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
@php
    $isEdit = isset($userrights);
 @endphp
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">User Accesses /</span> User Rights
</h4>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills flex-column flex-md-row mb-3">
      <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> User Details</a></li>
      {{-- <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-notifications')}}"><i class="bx bx-universal-access me-1"></i> Profile Access</a></li> --}}
     </ul>
    <div class="card mb-4">
      <h5 class="card-header p-3"><strong style="font-size:25px">User Rights</strong></h5>
      <!-- Account -->
      <hr class="my-0">
      <div class="card-body">
        <form action="{{ $isEdit ? route('usersaccesses-userrights.update', $userrights->id) : route('usersaccesses-userrights.store') }}" id="formAccountSettings" method="POST" enctype="multipart/form-data" onsubmit="$('#submit').prop('disabled', true);">
          @csrf
          @method('post')
          <div class="row">
            <div class="mb-1 col-md-4">

            </div>
            <div class="mb-1 col-md-8">
          <div class="mb-1 col-md-6">
          <label for="username" class="form-label">Username <sup style="color:red;">&#9733;</sup></label>
          <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $isEdit ? $userrights->username : '') }}" placeholder="Enter username" />
          @error('username')
          <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-1 col-md-6">
          <label for="password" class="form-label">Password <sup style="color:red;">&#9733;</sup></label>
          <input type="password" class="form-control" id="password" name="password" value="{{ old('password', $isEdit ? $userrights->password : '') }}" placeholder="Enter password" />
          @error('password')
          <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-1 col-md-6">
          <label for="phone" class="form-label">Mobile <sup style="color:red;">&#9733;</sup></label>
          <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $isEdit ? $userrights->phone : '') }}" placeholder="Enter mobile number" />
          @error('phone')
          <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-1 col-md-6">
          <label for="email" class="form-label">Email <sup style="color:red;">&#9733;</sup></label>
          <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $isEdit ? $userrights->email : '') }}" placeholder="Enter Email" />
          @error('email')
          <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-1 col-md-6">
          <label for="ttokenid" class="form-label">Telegram - Token ID</label>
          <input type="text" class="form-control" id="ttokenid" name="ttokenid" value="{{ old('ttokenid', $isEdit ? $userrights->ttokenid : '') }}" placeholder="Enter Telegram - Token ID" />
          @error('ttokenid')
          <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-1 col-md-6">
          <label for="tchatid" class="form-label">Telegram - Chat ID </label>
          <input type="text" class="form-control" id="tchatid" name="tchatid" value="{{ old('tchatid', $isEdit ? $userrights->tchatid : '') }}" placeholder="Enter Telegram - Chat ID" />
          @error('tchatid')
          <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-1 col-md-6">
          <label for="company" class="form-label">Company <sup style="color:red;">&#9733;</sup></label>
          <input type="text" class="form-control" id="company" name="company" value="{{ old('company', $isEdit ? $userrights->company : '') }}" disabled />
          @error('company')
          <div class="alert alert-danger p-1">{{ $message }}</div>
          @enderror
        </div>
        <div class="mt-2">
          <button type="submit" id="submit" class="btn btn-primary me-2">Save changes</button>
          <button type="reset" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('usersaccesses-userrights')}}'">Cancel</button>
        </div>
            </div>
          </div>
        </form>
      </div>
      <!-- /Account -->
    </div>
  </div>
</div>

@endsection
