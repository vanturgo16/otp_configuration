<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstRawMaterials extends Model
{
    use HasFactory;
    protected $table = 'master_raw_materials';
    protected $guarded=[
        'id'
    ];
}
