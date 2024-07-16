<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ims_taxcodes extends Model
{
    use HasFactory;
    protected $fillable = [
        'code', 'description',
    ];
}
