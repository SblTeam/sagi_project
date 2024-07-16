require('select2');
import $ from 'jquery';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'bootstrap';
import 'datatables.net';
import 'datatables.net-bs5';

$(document).ready(function () {
  $('#datatable_set').DataTable({
    dom: '<"top"f>rt<"bottom"lip><"clear">',
    columnDefs: [{ targets: [-1], orderable: false }]
  });
});

$(document).ready(function () {
  $('.search_data').select2();
});
