<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSf extends Model
{
    use HasFactory;
    protected $table = 'report_sfs';
    protected $guarded=[
        'id'
    ];
}
