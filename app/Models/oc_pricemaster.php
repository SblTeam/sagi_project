<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class oc_pricemaster extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [

        'date',
        'cat',
        'code',
        'desc',
        'units',
        'price',
        'empname',
        'updated',
        'client',
        'importkey',
        'updated_at',
        'created_at',
        'incr',
        'pm'
    ];

    // Optionally, if you want to disable automatic timestamps
    public $timestamps = true; // Set this to false if you want to manage timestamps manually
}

