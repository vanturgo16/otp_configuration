<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportBag extends Model
{
    use HasFactory;
    protected $table = 'report_bags';
    protected $guarded=[
        'id'
    ];
}
