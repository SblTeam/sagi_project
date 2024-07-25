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
          <h4 class="mb-2">Welcome to Bhagyalakshmi! 🔒</h4>
          <p class="mb-4">Please sign-in to your account and start the sales with Bhagyalakshmi</p>
          @if(session('error'))<div class="alert alert-danger fsize p-1">{{ session('error') }}</div>@endif
          <form id="formAuthentication" class="mb-3" action="{{route('auth-changepass')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('post')
            @if(session('error'))<div class="alert alert-danger fsize p-1">{{ session('error') }}</div>@endif
            @if(session('success'))<div class="alert alert-info fsize p-1">{{ session('success') }}</div>@endif
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="" >
            </div>
            <div class="mb-3"><label class="form-label" for="pass">Old Password</label>
              <div class="input-group input-group-merge">
              <input type="password" id="pass" class="form-control" name="cpass" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="cpass" />
            </div></div>
              <div class="mb-3"><label class="form-label" for="pass">New Password</label>
                <div class="input-group input-group-merge">
                <input type="password" id="pass" class="form-control" name="pass" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="pass" />
              </div></div>
              <div class="mb-3">
                <label class="form-label" for="cfpass">Confirm Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="cfpass" class="form-control" name="cfpass" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="cfpass" />
              </div></div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Change Password</button>
            </div>
            <div class="mb-12">
              <a href="{{url('/auth/login-basic')}}" class="app-brand-link gap-2" style="padding: 0% 0% 2% 40%;"> Go to login</a>
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
