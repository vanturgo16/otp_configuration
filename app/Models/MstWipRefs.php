<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstWipRefs extends Model
{
    use HasFactory;
    protected $table = 'master_wip_refs';
    protected $guarded=[
        'id'
    ];
}
