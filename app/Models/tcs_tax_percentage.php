<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tcs_tax_percentage extends Model
{
  use HasFactory;
  protected $fillable = [
'id', 'date', 'document', 'min_value', 'max_value', 'module', 'tax_percent', 'active', 'empname', 'updated', 'client',



  ];
}
