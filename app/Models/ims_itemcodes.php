<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ims_itemcodes extends Model
{
  use HasFactory;
  protected $fillable = [
    'catgroup',
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
    'pieces',
    'weight',
    'packetweight',
    'tax_applicable',
    'wpac',
    'iac',
    'cogsac',
    'sac',
    'srac',
    'hsn',
    'ean_no',

  ];
}
