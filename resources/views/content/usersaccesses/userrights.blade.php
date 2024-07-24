@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')
@section('page-script')
<script src="{{asset('assets/js/ui-toasts.js')}}"></script>
@endsection
@section('content')
<h4>
  <span class="text-muted fw-light">User Accesses /</span> User Rights
  <div style="text-align: end;"><button class="btn btn-info" style="padding-left:25px;padding-right:25px;" onclick="window.location.href='{{route('usersaccesses-userrights.add')}}'">Add</button></div>
</h4>
<!-- Bootstrap Table with Header - Dark -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table w-100" id="datatable_set">
      <thead>
        <tr>
          <th style="width:19.28%;">UserName</th>
          <th style="width:19.28%;">Company</th>
          <th style="width:14.28%;">Email</th>
          <th style="width:14.28%;">PhoneNumber</th>
          {{-- <th style="width:9.28%;">Details</th> --}}
          <th style="width:9.28%;">Status</th>
          <th style="width:14.28%;">Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach ($userrights as $details)
        <tr>
          <td>{{$details->username}}</td>
          <td>{{$details->company}}</td>
          <td>{{$details->email}}</td>
          <td>{{$details->phone}}</td>
          {{-- <td style="text-align:center"><i class="bx bx-file-blank me-1" style="color: #03c3ec"></i> </td> --}}
          <td><span class="badge me-1">@if($details->active==1) <span class="bg-label-primary">Active</span> @else <span style="color: red">Inactive</span> @endif</span></td>
          <td><i class="bx bx-edit-alt me-1" style="color: #03c3ec" onclick="window.location.href='{{ route('usersaccesses-userrights.edit', $details->id)}}'"></i> 
            @if($details->active==1)<i class="bx bx-user-check me-1" style="color: #03c3ec" onclick="if(confirm('Are you sure you want to Inactivate?')) { window.location.href='{{ route('usersaccesses-userrights.active',[$details->id,0]) }}'; }"></i> 
            @else<i class="bx bx-user-x me-1" style="color: red" onclick="if(confirm('Are you sure you want to Activate?')) { window.location.href='{{ route('usersaccesses-userrights.active',[$details->id,1]) }}'; }"></i> 
            @endif</td>
          </tr>
        @endforeach
        @if(session('Fail') || session('success'))
        <div class="bs-toast toast fade show @if(session('Fail'))bg-danger @else bg-info @endif" role="alert" aria-live="assertive" aria-atomic="true" style="position:absolute;top:30%;left:30%">
          <div class="toast-header">
            <i class='bx bx-bell me-5'></i>
            <div class="me-auto fw-medium">Alert</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body pt-5 pb-5">
            {{ session('Fail').session('success') }}
          </div>
        </div>
        @endif
      </tbody>
    </table>
  </div>
</div>
<!--/ Bootstrap Table with Header Dark -->
@endsection
