<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstWipRefWips extends Model
{
    use HasFactory;
    protected $table = 'master_wip_ref_wips';
    protected $guarded=[
        'id'
    ];
}
