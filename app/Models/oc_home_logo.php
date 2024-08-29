<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class oc_home_logo extends Model
{

  protected $table = 'oc_home_logo';
    use HasFactory;
    protected $fillable = [
   'idPrimary','company','companycode','address','image','signature','fssl','updated','client','empname','adate','noninventory','pan_card','bank_name','branch_name','ifsc_code','acc_no'



       ];
}

