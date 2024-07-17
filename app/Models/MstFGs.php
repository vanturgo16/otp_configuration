<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstFGs extends Model
{
    use HasFactory;
    protected $table = 'master_product_fgs';
    protected $guarded=[
        'id'
    ];
}
