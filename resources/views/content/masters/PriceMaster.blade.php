@extends('layouts/contentNavbarLayout')

@section('title', 'Tables - Basic Tables')

@section('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.ajax({
        url: "{{ route('masters.getitemflag') }}", // replace with your route name
        type: 'GET',
        success: function(response) {
            var responseCodes = [];

            // Parse response if it's a string
            if (typeof response === 'string') {
                try {
                    response = JSON.parse(response);
                } catch (e) {
                    console.error('Parsing error:', e);
                    return;
                }
            }

            // Ensure response is an array
            if (Array.isArray(response)) {
                responseCodes = response.map(item => item.code);
            } else {
                console.error('Unexpected response format:', response);
                return;
            }

            // Loop through each row in the table
            $('#datatable_set tbody tr').each(function() {
                var code = $(this).find('td:eq(1)').text().trim(); // Assuming the code is in the second column
                if (responseCodes.includes(code)) {
                    // Disable edit and delete actions
                    $(this).find('.bx-edit-alt').remove(); // Remove edit icon
                    $(this).find('.bx-trash').remove(); // Remove delete icon
                }
            });
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});
</script>

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
          <td>{{ $details->cat }}</td>
          <td>{{ $details->code }}</td>
          <td>{{ $details->desc }}</td>
          <td>{{ $details->units }}</td>
          <td>{{ $details->price }}</td>
          <td>
            <i class="bx bx-edit-alt me-1" style="color: #03c3ec" onclick="window.location.href='{{ route('masters-PriceMaster.edit', ['incr' => $details->incr, 'code' => $details->code]) }}'"></i>
            <i class="bx bx-trash me-1" style="color: red" onclick="if(confirm('Are you sure you want to delete?')) { window.location.href='{{ route('masters-PriceMaster.destroy', ['incr' => $details->incr, 'client' => $details->client]) }}'; }"></i>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<!--/ Bootstrap Table with Header Dark -->

@endsection
