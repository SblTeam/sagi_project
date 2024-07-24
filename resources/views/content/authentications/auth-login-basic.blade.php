@extends('layouts/blankLayout')

@section('title', 'Login Basic - Pages')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('content')
<div class="container-xxl" style="background-color: color-mix(in srgb, #00e6ff1a 100%, white);">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="{{url('/')}}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo" ><img src="{{asset('assets\img\avatars\sbl.png')}}" alt="main company logo" style="width:60px"></span>
              <span class="app-brand-text text-body fw-bold" style="font-weight: 850 !important;">SRI BHAGYALAKSHMI ENTERPRISES</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-2">Welcome to Bhagyalakshmi! 👋</h4>
          <p class="mb-4">Please sign-in to your account and start the sales with Bhagyalakshmi</p>
          @if(session('error'))<div class="alert alert-danger fsize p-1">{{ session('error') }}</div>@endif
          @if(session('success'))<div class="alert alert-info fsize p-1">{{ session('success') }}</div>@endif
          <form id="formAuthentication" class="mb-3" action="{{route('auth-login')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="" >
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
                <a href="{{url('auth/forgot-password-basic')}}">
                  <small>Change Password</small>
                </a>
              </div>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>

          {{-- <p class="text-center">
            <span>New on our platform?</span>
            <a href="{{url('auth/register-basic')}}">
              <span>Create an account</span>
            </a>
          </p> --}}
        </div>
      </div>
    </div>
    <!-- /Register -->
  </div>
</div>
</div>
@endsection
