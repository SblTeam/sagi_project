<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contactdetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','company','address','place','pan','email','auth_flag2','phone','state','gstin','files_path','active_flag',
    ];
}
