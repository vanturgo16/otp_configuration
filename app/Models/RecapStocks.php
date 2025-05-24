<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecapStocks extends Model
{
    use HasFactory;
    protected $table = 'recap_stocks';
    protected $guarded=[
        'id'
    ];
}
