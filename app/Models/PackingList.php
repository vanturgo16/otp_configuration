<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackingList extends Model
{
    use HasFactory;
    protected $table = 'packing_lists';
    protected $guarded=[
        'id'
    ];
}
