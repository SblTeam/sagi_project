@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')



<h4>
  <span class="text-muted fw-light">Sales Masters /</span> Price Master
  <table>
    <tr>
      <td width="1200px"></td>
      <td style="text-align: end;">
        <button class="btn btn-info" style="padding-left:25px;padding-right:25px;" onclick="window.location.href='{{ route('masters.PriceMaster.add') }}'">Add</button>
      </td>
    </tr>
  </table>
</h4>

<!-- Bootstrap Table with Header - Dark -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table w-80" id="datatable_set">
      <thead>
        <tr>
          <th style="width:19.28%;text-align: left;">Date</th>
          <th style="width:19.28%;">Category</th>
          <th style="width:14.28%;">Item code</th>
          <th style="width:14.28%;">Description</th>
          <th style="width:9.28%;">Units</th>
          <th style="width:9.28%;">Price</th>
          <th style="width:14.28%;">Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach ($oc_pricemaster as $details)
        <tr>
          <td style="text-align: left;">{{ $details->date }}</td>
          <td>{{ $details->cat }}</td>
          <td>{{ $details->code }}</td>
          <td>{{ $details->desc }}</td>
          <td>{{ $details->units }}</td>
          <td>{{ $details->price }}</td>
          <td>
            @php
              $apiDataParts = explode('&', $apiData);
              $canDelete = false;

              foreach ($apiDataParts as $part) {
                  $apiCodeDate = explode('@', $part); 
                  $apiCode = $apiCodeDate[0] ?? '';
                  $apiDate = $apiCodeDate[1] ?? '';

                  if ($details->code == $apiCode && $apiDate < $details->updated_at) {
                      $canDelete = true;
                      break;
                  }
              }
     
            @endphp

            @if (!in_array($details->code, array_column(array_map(fn($p) => explode('@', $p), $apiDataParts), 0)))
              <i class="bx bx-trash me-1" style="color: red" onclick="if(confirm('Are you sure you want to delete?')) { window.location.href='{{ route('masters-PriceMaster.destroy', ['incr' => $details->incr, 'code' => $details->code]) }}'; }"></i>

              @elseif($canDelete)
              <i class="bx bx-trash me-1" style="color: red" onclick="if(confirm('Are you sure you want to delete?')) { window.location.href='{{ route('masters-PriceMaster.destroy', ['incr' => $details->incr, 'code' => $details->code]) }}'; }"></i>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<!--/ Bootstrap Table with Header Dark -->

@endsection
