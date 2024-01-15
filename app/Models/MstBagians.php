<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstBagians extends Model
{
    use HasFactory;
    protected $table = 'master_bagians';
    protected $guarded=[
        'id'
    ];
}
