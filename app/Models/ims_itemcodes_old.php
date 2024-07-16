<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ims_itemcodes extends Model
{
  use HasFactory;
  protected $fillable = [
    'code',
    'description',
    'cat',
    'type',
    'cm',
    'sunits',
    'cunits',
    'saunits',
    'source',
    'iusage',
    'tax_applicable',
  ];
}
