@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Import Item Masters')

@section('page-script')

@endsection

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Sales Module /</span> Import Item Masters
</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header p-3"><strong style="font-size:25px">Import Item Masters</strong></h5>
            <div class="card-body">
                <form class="form" id="complex_form" style="height:auto" enctype="multipart/form-data" method="post" onsubmit="return checkform();" action="{{route('items.import')}}">
                    @csrf
                    <center>
                        <br />
                        <table id="tab1" align="center" width="750px">
                            <tr>
                                <td align="center"><strong>Instructions for Importing Item Masters</strong></td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td> 1. The Excel file should be in 2003 format only.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td> 2. The order of the columns should be same as in Sample Format File.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td> 3. No other data should be there in the excel file except the data that is to be entered into the software.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td> 4. The first row in the excel file should contain the header names.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td> 5. The data should start from 2nd row onwards. Please don't give empty rows in the middle of the data.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">
                                <td> 6. The data like Category, Warehouse and COA Codes should be existing in the software in same format including Capitalization.</td>
                            </tr>
                            <tr height="15px"></tr>
                            <tr align="left">


                                <td>
                                    7. You can also download the sample format
                                    <a href="/assets/GLHelp/sample1.xlsx" class="btn btn-primary me-2" download>Download</a>
                                </td>



                            </tr>
                            <tr height="15px"></tr>
                            <tr align="center">
                                <td><strong>Excel File</strong>&nbsp;&nbsp;<input type="file" id="file" name="file"></td>
                            </tr>
                            <tr height="30px"></tr>
                            <tr align="center">
                                <td>
                                    <input class="btn btn-primary me-2" type="submit" value="Import" id="import" name="import" />
                                    &nbsp;<input class="btn btn-outline-secondary" type="button" value="Cancel" onClick="javascript: history.go(-1)" />
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