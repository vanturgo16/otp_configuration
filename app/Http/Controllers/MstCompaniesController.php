<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Model
use App\Models\User;
use App\Models\MstCompanies;

class MstCompaniesController extends Controller
{
    public function index(){
        $companies=MstCompanies::get();
        // dd($companies);
        
        return view('company.index', compact('companies'));
    }
}
