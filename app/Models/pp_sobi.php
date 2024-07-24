<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pp_sobi extends Model
{
  use HasFactory;
  protected $fillable = [
'id', 'date', 'sobiincr', 'm', 'y', 'so', 'invoice', 'vendorid', 'vendor', 'vendorcode', 'code', 'description', 'rateperunit', 'itemunits', 'taxcode', 'taxvalue', 'taxie', 'taxamount', 'taxformula', 'tcs_percentage', 'tcs_tax', 'tds_amt', 'tandccode', 'tandc', 'tonnage', 'totalquantity', 'totalamount', 'round_off_type', 'round_off_cost', 'grandtotal', 'balance', 'empid', 'empname', 'sector', 'flag', 'aempid', 'aempname', 'asector', 'remarks', 'vno', 'driver', 'freighttype', 'viaf', 'datedf', 'cashbankcode', 'cno', 'dflag', 'coa', 'warehouse', 'department', 'addupdated', 'updated', 'client', 'category',


  ];
}
