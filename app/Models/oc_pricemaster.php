<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class oc_pricemaster extends Model
{
    use HasFactory;
    protected $fillable = [
        'fromdate',
        'todate',
        'customer',
        'catgroup',
        'cat',
        'code',
        'desc',
        'units',
        'price',
        'empname',
        'updated',
        'client',
        'importkey',


    ];
}
