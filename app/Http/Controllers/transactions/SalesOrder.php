<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\oc_salesorder;
use Illuminate\Support\Facades\Http;

class SalesOrder extends Controller
{

  public function index()
  {
      $oc_salesorder = oc_salesorder::select('oc_salesorders.*')
                                     ->join(DB::raw('(SELECT MIN(id) as id FROM oc_salesorders GROUP BY po) as grouped'), 'oc_salesorders.id', '=', 'grouped.id')
                                     ->get();

      return view('content.transactions.SalesOrder', compact('oc_salesorder'));
  }

    public function add(Request $request)
    {
        $vendor = 'DST-7463';
        $date = date("d.m.Y");
        $yearFull = (int)explode('.', $date)[2];
        $year = (int)substr($yearFull, -2);
        $month = (int)explode('.', $date)[1];

        if ($month < 4) {
            $prevYear = $year - 1;
            $fyid = " and ((m >= '4' and y = '$prevYear') or (m <= '3' and y='$year')) ";
            $fy = $prevYear . $year;
        } else {
            $nextYear = $year + 1;
            $fyid = " and ((m >= '4' and y = '$year') or (m <= '3' and y = '$nextYear')) ";
            $fy = $year . $nextYear;
        }

        $table = "oc_salesorders";

        // Ensure query string is constructed properly before passing to DB::select



            $qry2 = "SELECT count(DISTINCT po) as total FROM $table";
            $result2 = DB::select($qry2);
            $poincr = $result2[0]->total + 1;

            $vendorids = explode("-", $vendor);
            $vendorid = $vendorids[1];

            if ($poincr < 10) {
                $po = 'SO' . '-' . $fy . '-000' . $poincr;
            } elseif ($poincr < 100) {
                $po = 'SO' .'-' . $fy . '-00' . $poincr;
            } else {
                $po = 'SO' .'-' . $fy . '-0' . $poincr;
            }

        $response = Http::get('https://secondary.sbl1972.in/secondarysales/apidistnameid.php');
        $data = $response->json();

        return view('content.transactions.SalesOrder1-add', compact('po','data'));
    }

    public function store(Request $request)
    {




      $date = date("d.m.Y");
      $yearFull = (int)explode('.', $date)[2];
      $year = (int)substr($yearFull, -2);
      $month = (int)explode('.', $date)[1];

      if ($month < 4) {
          $prevYear = $year - 1;
          $fyid = " and ((m >= '4' and y = '$prevYear') or (m <= '3' and y='$year')) ";
          $fy = $prevYear . $year;
      } else {
          $nextYear = $year + 1;
          $fyid = " and ((m >= '4' and y = '$year') or (m <= '3' and y = '$nextYear')) ";
          $fy = $year . $nextYear;
      }

      $table = "oc_salesorders";

 $qry2 = "SELECT count(DISTINCT po) as total FROM $table";
      $result2 = DB::select($qry2);
      $poincr = $result2[0]->total + 1;

        $validatedData = $request->validate([
            'so' => 'required',
             'id' => 'required',
            'date' => 'required',
            'distributor' => 'required',
            'po' => 'required',
            'category' => 'required|array',
            'description' => 'required|array',
            'code' => 'required|array',
            'quantity' => 'required|array',
            'enteredquantity' => 'array',
            'price' => 'required|array',
            'taxType' => 'required|array',
            'tax' => 'required|array',
            'tquantity' => 'required',
            'total' => 'required',
        ]);

        // Iterate through the rows and insert into the database
        foreach ($request->category as $index => $category) {
            if (isset($request->check[$index])) {
                $nn = new oc_salesorder();
                $nn->po = $request->so;
                $nn->vendorid = $request->id;
                $nn->date = $request->date;
                $nn->vendor = $request->distributor;
                $nn->pono = $request->po;


                $nn->tquantity = $request->tquantity;
                $nn->total = $request->total;


                $nn->category = $category;
                $nn->description = $request->description[$index];
                $nn->code = $request->code[$index];
                $nn->quantity = $request->quantity[$index];
                $nn->squantity = $request->enteredquantity[$index];
                $nn->sprice = $request->price[$index];
                $nn->taxcode = $request->taxType[$index];
                $nn->taxvalue = $request->tax[$index];
                $nn->poincr = $poincr;

                $nn->save();
            }
        }

        return redirect()->route('transctions-SalesOrder');

    }


    public function update(Request $request, $code)
    {
        // Fetch the existing sales orders
        $oc_salesorders = oc_salesorder::where('po', $code)->get();

        // Validate the incoming request data
        $validatedData = $request->validate([
          'so' => 'required',
            'id' => 'required',
            'date' => 'required',
            'distributor' => 'required',
            'po' => 'required',
            'category' => 'required|array',
            'description' => 'required|array',
            'code' => 'required|array',
            'quantity' => 'required|array',
            'enteredquantity' => 'array',
            'price' => 'required|array',
            'taxType' => 'required|array',
            'tax' => 'required|array',
            'tquantity' => 'required',
            'total' => 'required',
        ]);

        // Delete existing sales orders
        oc_salesorder::where('po', $code)->delete();

        // Iterate through the rows and insert the updated data into the database
        foreach ($request->category as $index => $category) {
            if (isset($request->check[$index])) {
                $oc_salesorder = new oc_salesorder();
                $oc_salesorder->po = $request->so;
                $oc_salesorder->vendorid = $request->id;
                $oc_salesorder->date = $request->date;
                $oc_salesorder->vendor = $request->distributor;
                $oc_salesorder->pono = $request->po;
                $oc_salesorder->tquantity = $request->tquantity;
                $oc_salesorder->total = $request->total;
                $oc_salesorder->category = $category;
                $oc_salesorder->description = $request->description[$index];
                $oc_salesorder->code = $request->code[$index];
                $oc_salesorder->quantity = $request->quantity[$index];
                $oc_salesorder->squantity = $request->enteredquantity[$index] ?? null;
                $oc_salesorder->sprice = $request->price[$index];
                $oc_salesorder->taxcode = $request->taxType[$index];
                $oc_salesorder->taxvalue = $request->tax[$index];
                $oc_salesorder->save();
            }
        }

        // Redirect back with a success message
        return redirect()
            ->route('transctions-SalesOrder')
            ->with('success', 'Sales order updated successfully!');
    }



    public function edit($id)
    {
        // Retrieve sales order rows for the given PO ID
        $oc_salesorder = oc_salesorder::where('po', $id)->get();
$data = [];
        // Initialize the associative array
        $salesOrderDetails = [];

        // Loop through each sales order row
        foreach ($oc_salesorder as $order) {
            $salesOrderDetails[] = [
                'category' => $order->category,
                'description' => $order->description,
                'code' => $order->code,
                'taxtype' => $order->taxtype,
                'tax' => $order->tax,
                'quantity' => $order->quantity,
                'squantity' => $order->squantity,
                'price' => $order->sprice,
                'taxcode' => $order->taxcode,
                'taxvalue' => $order->taxvalue,
                // Add other fields as necessary
            ];
        }

        // Get other required details
        $pono = oc_salesorder::where('po', $id)->first()->pono;
        $vendor = oc_salesorder::where('po', $id)->first()->vendor;
        $vendorid = oc_salesorder::where('po', $id)->first()->vendorid;

        $tquantity = oc_salesorder::where('po', $id)->first()->tquantity;
        $total = oc_salesorder::where('po', $id)->first()->total;

        // Pass data to the view
        return view('content.transactions.SalesOrder-edit', compact('salesOrderDetails', 'id', 'pono', 'vendor', 'vendorid', 'data', 'tquantity', 'total'));
    }


    public function destroy($id)
    {
        oc_salesorder::where('po', $id)->delete();

        return redirect()
            ->route('transctions-SalesOrder')
            ->with('success', 'Item(s) deleted successfully!');
    }



  }
