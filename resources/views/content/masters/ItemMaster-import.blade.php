@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Import Item Masters')

@section('page-script')

@endsection

@section('content')
<!-- Include Bootstrap CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

@if (session('error') || session('import_errors'))
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('import_errors'))
        <!-- Modal -->
        <div class="modal fade" id="importErrorsModal" tabindex="-1" aria-labelledby="importErrorsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importErrorsModalLabel">Import Errors</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <strong>Import Errors:</strong>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        @foreach (session('import_errors') as $index => $error)
                                            <th>Row: {{ $index + 1 }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @foreach (session('import_errors') as $index => $error)
                                            <td>
                                                <ul style="color: red;">
                                                    @foreach ($error['errors'] as $err)
                                                        <li>{{ $err }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var importErrorsModal = new bootstrap.Modal(document.getElementById('importErrorsModal'), {
                    keyboard: false
                });
                importErrorsModal.show();
            });
        </script>
    @endif
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Sales Module /</span> Import Item Masters
</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header p-3"><strong style="font-size:25px">Import Item Masters</strong></h5>
            <div class="card-body">
                <form class="form" id="complex_form" style="height:auto" enctype="multipart/form-data" method="post" onsubmit="return checkform();" action="{{ route('items.import') }}">
                    @csrf
                    <center>
                        <br />
                        <table id="tab1" align="center" width="750px">
                            <tr>
                                <td align="center"><strong>Instructions for Importing Item Masters</strong></td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td>1. The Excel file should be in 2003 format only.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td>2. The order of the columns should be the same as in the Sample Format File.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td>3. No other data should be in the excel file except the data to be entered into the software.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td>4. The first row in the excel file should contain the header names.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td>5. The data should start from the 2nd row onwards. Please don't leave empty rows in the middle of the data.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td>6. Data such as Category, Warehouse, and COA Codes should be existing in the software in the same format including capitalization.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td>7. You can also download the sample format<a href="/sagi_project/assets/GLHelp/sample1.xlsx" class="btn btn-primary me-2" download="">Download</a></td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="center">
                                <td><strong>Excel File</strong>&nbsp;&nbsp;<input type="file" id="file" name="file"></td>
                            </tr>
                            <tr height="30px"></tr>
                            <tr align="center">
                                <td>
                                    <input class="btn btn-primary me-2" type="submit" value="Import" id="import" name="import" />
                                    &nbsp;<input class="btn btn-outline-secondary" type="button" value="Cancel" onClick="window.location.href='{{ route('masters-ItemMaster') }}'" />
                                </td>
                            </tr>
                        </table>
                    </center>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
