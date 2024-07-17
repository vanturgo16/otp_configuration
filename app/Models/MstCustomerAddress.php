<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstCustomerAddress extends Model
{
    use HasFactory;
    protected $table = 'master_customer_addresses';
    protected $guarded=[
        'id'
    ];
}
