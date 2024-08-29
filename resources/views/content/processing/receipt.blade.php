@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')
@section('page-script')
<script src="{{asset('assets/js/ui-toasts.js')}}"></script>
@endsection
@section('content')
<h4>
  <span class="text-muted fw-light">Sales Processing /</span> Sales  Receipt
  <div style="text-align: end;"><button class="btn btn-info" style="padding-left:25px;padding-right:25px;" onclick="window.location.href='{{route('processing-receipt.add')}}'">Add</button></div>
</h4>
<!-- Bootstrap Table with Header - Dark -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table w-100" id="datatable_set">
      <thead>
        <tr>
          <th style="width:19.28%;" >Date</th>
          <th style="width:19.28%;text-align:left;" >Receipt No</th>
          <th style="width:14.28%;" >Party</th>
          <th style="width:14.28%;" >Amount</th>
          <th style="width:9.28%;" >Made Of Payment</th>
          <th style="width:14.28%;" >Enter Done By</th>
          <th style="width:14.28%;" >Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach ($receipt as $details)
        @php
        $date=date("d.m.Y",strtotime($details->date));
        @endphp
        <tr>
          <td >{{$date}}</td>
          <td style="text-align:left;">{{$details->tid}}</td>
          <td >{{$details->party}}</td>
          <td >{{$details->totalamount}}</td>
          <td >@if($details->paymentmode == 'others' ||$details->paymentmode == 'Others') echo 'Cash';@else {{$details->paymentmode}} @endif</td>
          <td >{{$details->empname}}</td>
          <td >
             <i class="bx bx-trash me-1" style="color: red" onclick="if(confirm('Are you sure you want to delete?')) { window.location.href='{{ route('processing-receipt.delete', $details->tid) }}'; }"></i>
             <i class="bx bx-printer me-1" style="color: #03c3ec" onclick="window.open('{{ route('processing-receipt.print', $details->tid) }}', '_blank')"></i>
             <i class="bx bx-file-find me-1" style="color: #03c3ec" onclick="window.location.href='{{route('processing-receipt.view', $details->tid)}}'"></i>
          </td>
          </tr>
        @endforeach
        @if(session('Fail') || session('Success'))
        <div class="bs-toast toast fade show @if(session('Fail'))bg-danger @else bg-info @endif" role="alert" aria-live="assertive" aria-atomic="true" style="position:absolute;top:30%;left:30%">
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
      </tbody>
    </table>
  </div>
</div>
<!--/ Bootstrap Table with Header Dark -->
@endsection
