<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class oc_receipt extends Model
{
  use HasFactory;
  protected $table='oc_receipt';
  protected $fillable = [
'tid', 'date', 'doc_no', 'party', 'partycode', 'paymentmethod', 'department', 'tds', 'tdscode', 'tdsdescription', 'tdsdr', 'tdsamount', 'tdsamount1', 'paymentmode', 'code', 'code1', 'description', 'dr', 'amount', 'totalamount', 'bank', 'branch', 'cheque', 'upi', 'cdate', 'choice', 'socobi', 'actualamount', 'amountreceived', 'balance', 'flag', 'unit', 'adate', 'aempid', 'aempname', 'asector', 'remarks', 'updated', 'client', 'empname', 'allocation', 'oldflag', 'sd_status', 'old_department', 'rct_id', 'receipt_source'
  ];
}
