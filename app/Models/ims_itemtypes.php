<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ims_itemtypes extends Model
{
    use HasFactory;
    protected $fillable = [
        'catgroup', 'type',
    ];
}
class onboard_connections extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'extended_table';
    protected $fillable = [
        'catgroup', 'type',
    ];
}
