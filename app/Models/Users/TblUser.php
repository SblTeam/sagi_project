<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblUser extends Model
{
    use HasFactory;
    protected $connection = 'users_db';
    protected $table = 'tbl_users';
    protected $guarded = [];
}
