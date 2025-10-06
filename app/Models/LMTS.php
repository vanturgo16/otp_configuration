<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LMTS extends Model
{
    use HasFactory;
    protected $table = 'lmts';
    protected $guarded=[
        'id'
    ];
}
