<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstOperators extends Model
{
    use HasFactory;
    protected $table = 'master_operators';
    protected $guarded=[
        'id'
    ];
}
