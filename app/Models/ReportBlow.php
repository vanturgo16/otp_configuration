<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportBlow extends Model
{
    use HasFactory;
    protected $table = 'report_blows';
    protected $guarded=[
        'id'
    ];
}
