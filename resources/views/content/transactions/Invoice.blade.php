@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')
<h4>
  <span class="text-muted fw-light">Sales Transactions /</span> Invoice
  <table>
    <tr>
      <td width=1200px></td>
      <td style="text-align: end;"><button class="btn btn-info" style="padding-left:25px;padding-right:25px;" onclick="window.location.href='{{route('transctions-Invoice-add')}}'">Add</button></td>
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
          <th style="width:19.28%;">Invoice.</th>

          <th style="width:14.28%;">Distributor</th>
          <th style="width:9.28%;">Quantity</th>

          <th style="width:14.28%;">Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach ($oc_cobi as $details)
        <tr>
        <td style="text-align:left">{{$details->date}}</td>
          <td>{{$details->so}}</td>
          <td>{{$details->invoice}}</td>
          <td>{{$details->party}}</td>
          <td>{{$details->totalquantity}}</td>

          <td>
          @if (in_array($details->invoice, $socobiInvoices))
          <i class="bx bx-lock-alt me-1" style="color: #03c3ec" title="Receipt is done"></i>
        
          @else
          <i class="bx bx-edit-alt me-1" style="color: #03c3ec" title = "Update irn and ewaybill number" onclick="window.location.href='{{ route('transctions-Invoice.edit', ['id' => $details->invoice]) }}'"></i>
     
          @endif

         

          <i class="bx bx-printer
-alt me-1" style="color: #03c3ec" title = "print" onclick="window.open('{{ route('generatePDF.print', ['id' => $details->invoice]) }}')"></i>





</td>



        </tr>
        @endforeach
      </tbody>

    </table>
  </div>
</div>
<!--/ Bootstrap Table with Header Dark -->
@endsection
