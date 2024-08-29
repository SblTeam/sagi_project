<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ac_financialpostings extends Model
{
  use HasFactory;
  protected $table='ac_financialpostings';
  protected $fillable = [
'date', 'itemcode', 'crdr', 'coacode', 'quantity', 'amount', 'trnum', 'type', 'venname', 'venid', 'warehouse', 'department', 'cash', 'bank', 'cashcode', 'bankcode', 'schedule', 'closed_tr', 'updated', 'client', 'empname', 'adate', 'old_department'
 ];
}
