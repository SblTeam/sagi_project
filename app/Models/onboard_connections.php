<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class onboard_connections extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'onboard_connections';
    protected $fillable = [
        'profile_name','type','company', 'primary_db', 'secondary_db', 'place','state','phone','email','db_name','tbl_name','localip','publicip','active','auth_flag1','auth_flag2','profile_flag','item_flag',
    ];  
}
