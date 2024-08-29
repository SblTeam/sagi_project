<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        body, html { font-family: 'Arial', sans-serif; font-size: 12px; }
        table, th, tr, td { border: 1px solid black; border-collapse: collapse; }
        td { padding: 5px; }
        .header-row, .info-row, .total-row { background-color: #f2f2f2; }
        .no-border { border: 0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .width-30 { width: 30%; }
        .width-70 { width: 70%; }
        .footer { margin-top: 20px; }
    </style>
</head>
@php 

$distcompany = explode('##' ,$apiData[0])[2];
$distaddress = explode('##' ,$apiData[0])[0];
$state = explode('##' ,$apiData[0])[1];
$distgst = explode('##' ,$apiData[0])[3];

 $statecode = substr($distgst, 0, 2); 
@endphp
<body>
    <center><b style="font-size: 2em;">Tax Invoice</b></center>
    <br>

    <!-- Company Logo and Details -->
<table width="100%" class="header" style="border-collapse: collapse; font-family: Arial, sans-serif;">
    <tr>
        <td class="border" width="50%" style="padding: 10px; border: 1px solid #000;">
            <div style="display: flex; align-items: center;">
            <img src="{{ asset($logo) }}" style="width:80px; height:80px; margin-right: 10px;">

                <div>
                    <b>{{$contctcompany}}</b><br>
                 {{$contactaddress}}<br>
                    <b>Phone no:</b>{{$contactphoneno}}<br>
                    <b>Email:</b>{{$contactemail}}<br>
                    <b>GSTIN:</b>{{$contactgst}}<br>
                    <b>State:</b>{{$contactstate}}
                </div>
            </div>
        </td>
        <td class="border" width="25%" style="padding: 10px; border: 1px solid #000; vertical-align: top;">
            <b>Invoice No.</b>{{$id}}<br>
            <b>Place of supply:</b> {{$statecode." ".$state}}
        </td>
        <td class="border" width="25%" style="padding: 10px; border: 1px solid #000; vertical-align: top;">
            <b>Date:</b> {{$date1}}
        </td>
    </tr>
    <tr>
        <td class="border" colspan="3" style="padding: 10px; border: 1px solid #000;">
            <b>Bill To</b><br>
          {{$distcompany}}<br><br>
          {{$distaddress}}<br>
            <b>GSTIN:</b>{{$distgst}}<br>
            <b>State:</b> {{$state}}
        </td>
    </tr>
</table>


    <br>

    <!-- Items Table -->
    <table width="100%">
        <tr>
            <th>S.No</th>
            <th>Item name</th>
            <th>HSN/SAC</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Price/ Unit</th>
            <th>Taxable amount</th>
            <th>GST</th>
            <th>Final Rate</th>
            <th>Amount</th>
        </tr>

        @php
            $sumQuantity = 0;
            $sumTaxableAmount = 0;
            $sumTotalwithtax = 0;
        @endphp

        @foreach($htmlData as $index => $data)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $data['description'] }}</td>
            <td>{{ $data['hsn'] }}</td>
            <td>{{ $data['quantity'] }}</td>
            <td>{{ $data['units'] }}</td>
            <td>{{ $data['taxable_price'] }}</td>
            <td>{{ $data['taxable_amount'] }}</td>
            <td>{{ $data['taxamount'] }}</td>
            <td>{{ $data['price'] }}</td>
            <td>{{ $data['totalwithtax'] }}</td>
        </tr>

        @php
            $sumQuantity += $data['quantity'];
            $sumTaxableAmount += $data['taxable_amount'];
            $sumTotalwithtax += $data['totalwithtax'];
            
            if(($sumTotalwithtax - round($sumTotalwithtax)) != 0)
            {
                if(($sumTotalwithtax) >= round($sumTotalwithtax) ) {
    
                    $totalamount = round($sumTotalwithtax);
                    $roff = round(($sumTotalwithtax - round($sumTotalwithtax)),2);
             
                } else {
         
                    $totalamount = round($sumTotalwithtax);
                    $roff = round((round($sumTotalwithtax) - $sumTotalwithtax) ,2);
                   
                }
            }
            else
            
            {
            
            $totalamount = $sumTotalwithtax;
            $roff = 0;
            }
  







        @endphp

        @endforeach

        <tr class="total-row">
            <td></td>
            <td>Total</td>
            <td></td>
            <td>{{ $sumQuantity }}</td>
            <td></td>
            <td></td>
            <td>{{ number_format($sumTaxableAmount, 2) }}</td>
            <td></td>
            <td></td>
            <td>{{ number_format($sumTotalwithtax, 2) }}</td>
        </tr>
    </table>

    <br>

    Invoice Amount in Words and Total Calculation
    <table width="100%">
        <tr>
            <td>Invoice Amount In Words</td>
            <td>Amounts:</td>
        </tr>
        <tr>
            <td>{{convertNumberToWords($totalamount)}}</td>
            <td>
                <table width="100%" style="border: none;">
                    <tr>
                        <td style="border: none;">Sub Total</td>
                        <td style="border: none; text-align: right;">{{ number_format($sumTotalwithtax, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: none;">Round off</td>
                        <td style="border: none; text-align: right;">{{ number_format($roff, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="border: none;">Total</td>
                        <td style="border: none; text-align: right;">{{ number_format($totalamount, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <br>

    <!-- Tax Summary Table -->
    <table width="100%" border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th rowspan="2">HSN/ SAC</th>
                <th rowspan="2">Taxable amount</th>
                <th colspan="2">CGST</th>
                <th colspan="2">SGST</th>
                <th rowspan="2">Total Tax Amount</th>
            </tr>
            <tr>
                <th>Rate</th>
                <th>Amount</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
        @php
            $totalamt = 0;
            $sumTaxvalueamt = 0;
            $sumTotalwithtaxamt = 0;
          
        @endphp
        @foreach($data123 as $datatest)
            <tr>
                <td>{{ $datatest['hsn11'] }}</td>
                <td>{{ $datatest['amt'] }}</td>
                <td>{{ number_format($datatest['taxvalue11'] / 2, 2) }}</td>
                <td>{{ number_format($datatest['taxamount1'] / 2, 2) }}</td>
                <td>{{ number_format($datatest['taxvalue11'] / 2, 2) }}</td>
                <td>{{ number_format($datatest['taxamount1'] / 2, 2) }}</td>
                <td>{{ number_format($datatest['taxamount1'], 2) }}</td>
            </tr>
            @php
                $totalamt += $datatest['amt'];
                $sumTaxvalueamt += $datatest['taxamount1'] / 2;
                $sumTotalwithtaxamt += $datatest['taxamount1'];
            @endphp
        @endforeach
            <tr>
                <td>Total</td>
                <td></td>
                <td></td>
                <td style="font-weight: bold;">{{ number_format($sumTaxvalueamt, 2) }}</td>
                <td></td>
                <td style="font-weight: bold;">{{ number_format($sumTaxvalueamt, 2) }}</td>
                <td style="font-weight: bold;">{{ number_format($sumTotalwithtaxamt, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br>

    <!-- Terms and Conditions and Bank Details -->
    <table width="100%" border="1" cellpadding="10" cellspacing="0">
        <tr>
            <td width="50%">
                <strong>Terms and conditions:</strong><br>
                Thanks for doing business with us!
            </td>
            <td width="50%">
                <strong>Company's Bank details:</strong><br>
                Bank Name: {{$bank_name}}<br>
                Branch Name: {{$branch_name}}<br>
                Bank Account No.: {{$account_no}}<br>
                Bank IFSC code: {{$IFSC}}<br>
                Account holder's name: {{$holder_name}}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right;">
                For, : {{$contctcompany}}<br><br><br>
                <br>
                <br>
                <br>
                <br>
                <br>
                Authorized Signatory
                
            </td>
        </tr>
    </table>

    <script>
        window.print(); // Automatically print the document
    </script>
</body>
</html>
