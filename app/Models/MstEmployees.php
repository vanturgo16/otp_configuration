<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstEmployees extends Model
{
    use HasFactory;
    protected $table = 'master_employees';
    protected $guarded=[
        'id'
    ];
}
