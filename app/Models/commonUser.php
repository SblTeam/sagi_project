<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commonUser extends Model
{
    use HasFactory;
    protected $connection = 'dynamic';
    protected $table = 'common_useraccess';
    protected $guarded = [];
}
