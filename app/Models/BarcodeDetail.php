<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarcodeDetail extends Model
{
    use HasFactory;
    protected $table = 'barcode_detail';
    protected $guarded=[
        'id'
    ];
}
