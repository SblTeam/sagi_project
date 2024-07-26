@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')
<h4>
  <span class="text-muted fw-light">Sales Masters /</span> Sales Order
  <table>
    <tr>
      <td width=1200px></td>
      <td style="text-align: end;"><button class="btn btn-info" style="padding-left:25px;padding-right:25px;" onclick="window.location.href='{{route('transctions-SalesOrder-add')}}'">Add</button></td>
    <tr>
  </table>
</h4>
<!-- Bootstrap Table with Header - Dark -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table w-100" id="datatable_set">
      <thead>
        <tr>
        <th style="width:19.28%;text-align:left">Date</th>
          <th style="width:19.28%;">SO NO.</th>

          <th style="width:14.28%;">Distributor</th>
          <th style="width:9.28%;">Quantity</th>

          <th style="width:14.28%;">Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach ($oc_salesorder as $details)
        <tr>
        <td style="text-align:left">{{$details->date}}</td>
          <td>{{$details->po}}</td>
          <td>{{$details->vendor}}</td>
          <td>{{$details->tquantity}}</td>

          <td>
    <i class="bx bx-edit-alt me-1" style="color: #03c3ec" onclick="window.location.href='{{ route('transctions-SalesOrder.edit', ['id' => $details->po]) }}'"></i>
    <i class="bx bx-trash me-1" style="color: red" onclick="if(confirm('Are you sure you want to delete?')) { window.location.href='{{ route('transctions-SalesOrder.destroy', ['id' => $details->po]) }}'; }"></i>

</td>



        </tr>
        @endforeach
      </tbody>

    </table>
  </div>
</div>
<!--/ Bootstrap Table with Header Dark -->
@endsection
