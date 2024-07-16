@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')
<h4>
  <span class="text-muted fw-light">Sales Masters /</span> Item Master
  <table>
    <tr>
      <td width=1200px></td>
      <td style="text-align: end;"><button class="btn btn-info" style="padding-left:25px;padding-right:25px;" onclick="window.location.href='{{route('masters.ItemMaster.import')}}'">Import</button></td>
      <td style="text-align: end;"><button class="btn btn-info" style="padding-left:25px;padding-right:25px;" onclick="window.location.href='{{route('masters.ItemMaster.add')}}'">Add</button></td>
    <tr>
  </table>
</h4>
<!-- Bootstrap Table with Header - Dark -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table w-100" id="datatable_set">
      <thead>
        <tr>
          <th style="width:19.28%;">Category Group</th>
          <th style="width:19.28%;">Category </th>
          <th style="width:14.28%;">Item code</th>
          <th style="width:14.28%;">Description</th>
          <th style="width:9.28%;">Source</th>
          <th style="width:9.28%;">units</th>
          <th style="width:14.28%;">Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach ($ims_itemcodes as $details)
        <tr>
          <td>{{$details->catgroup}}</td>
          <td>{{$details->cat}}</td>
          <td>{{$details->code}}</td>
          <td>{{$details->description}}</td>
          <td>{{$details->source}}</td>
          <td>{{$details->sunits}}</td>
          <td>
            <i class="bx bx-edit-alt me-1" style="color: #03c3ec" onclick="window.location.href='{{ route('masters-ItemMaster.edit', $details->id)}}'"></i>
            <i class="bx bx-trash me-1" style="color: red" onclick="if(confirm('Are you sure you want to delete?')) { window.location.href='{{ route('masters-ItemMaster.destroy', $details->id) }}'; }"></i>
            @if($details->halt_flag == 1)
            <i class="bx bx-play me-1" style="color: red" onclick="if(confirm('Are you sure,want to Halt this Item')) { window.location.href='{{ route('masters-ItemMaster.activeinactive', $details->id) }}'; }"></i>
            @else
            <i class="bx bx-pause me-1" style="color: red" onclick="if(confirm('Are you sure,want to Resume this Item')) { window.location.href='{{ route('masters-ItemMaster.activeinactive', $details->id) }}'; }"></i>
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