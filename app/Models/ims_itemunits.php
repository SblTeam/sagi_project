<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ims_itemunits extends Model
{
    use HasFactory;
    protected $fillable = [
        'sunits', 'cunits',
    ];
}
