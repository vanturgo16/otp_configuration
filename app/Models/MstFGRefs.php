<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstFGRefs extends Model
{
    use HasFactory;
    protected $table = 'master_product_fg_refs';
    protected $guarded=[
        'id'
    ];
}
