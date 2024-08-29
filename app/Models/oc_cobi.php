<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class oc_cobi extends Model
{

  protected $table = 'oc_cobi';
    use HasFactory;
    protected $fillable = [
      'id', 'date', 'cobiincr', 'm', 'y', 'companycode', 'party', 'partycode', 'company', 'department', 'salestype', 'pono', 'podate', 'esungam', 'credittermcode', 'credittermdescription', 'credittermvalue', 'ps', 'so', 'invoice', 'bookinvoice', 'cattype', 'catgroup', 'cat', 'code', 'description', 'aliasname', 'quantity', 'quantity_in_su', 'tonnage', 'extra_quantity', 'cquantity', 'freequantity', 'units', 'price', 'pricec', 'price_ex_freight', 'cunits', 'convunits', 'taxcode', 'taxvalue', 'taxamount', 'taxformula', 'taxie', 'taxtype', 'tcs_percentage', 'tcs_tax', 'cess_tax', 'cess_value', 'freightcat', 'freightcode', 'freightvalue', 'freightamount', 'freightformula', 'freightie', 'totalfreightamount', 'cashcode', 'brokeragecode', 'brokeragevalue', 'brokerageamount', 'brokerageformula', 'discountcode', 'discountvalue', 'discountamount', 'discountformula', 'idiscount', 'itype', 'total', 'totalquantity', 'totalweight', 'coacode', 'finaltotal', 'balance', 'flag', 'niflag', 'iflag', 'raflag', 'adate', 'aempid', 'aempname', 'asector', 'vno', 'driver', 'loadedby', 'broker', 'freighttype', 'viaf', 'datedf', 'dflag', 'bags', 'bagtype', 'empid', 'empname', 'empdate', 'sector', 'cashbankcode', 'cno', 'warehouse', 'individualdiscount', 'farm', 'flock', 'weight', 'birds', 'age', 'dc', 'remarks', 'unit', 'salesmanordistributor', 'salesexecutive', 'asm', 'asmflag', 'managerflag', 'authorized_asm', 'authorized_manager', 'cobi_flag', 'addupdated', 'updated', 'client', 'cs_no', 'cobi_image', 'cs_flag', 'oldflag', 'status', 'ni_flag', 'fy', 'term_code', 'tr_amount', 'print_status', 'routeplan_no', 'response_data', 'irn', 'ewaybillno', 'old_department'

       ];
}

