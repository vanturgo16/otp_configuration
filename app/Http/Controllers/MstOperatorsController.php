<?php

namespace App\Http\Controllers;

use App\Models\MstEmployees;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstOperators;
use App\Models\MstRegus;

class MstOperatorsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request, $id)
    {
        $id = decrypt($id);

        $flag = $request->get('flag');

        $datas = MstOperators::select('master_operators.*', 'master_regus.regu', 'master_employees.name')
            ->leftjoin('master_regus', 'master_operators.id_master_regus', 'master_regus.id')
            ->leftjoin('master_employees', 'master_operators.id_master_employees', 'master_employees.id')
            ->where('master_operators.id_master_regus', $id);

        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id', 'id_master_regus', 'id_master_employees']);
            return $datas;
        }

        $datas = $datas->paginate(10);

        $rg = MstRegus::where('id', $id)->first();
        $emp = MstEmployees::where('status', 'Active')->get();
        $allemp = MstEmployees::get();
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Operator From '. $rg->regu;
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('operator.index',compact('datas', 'rg', 'emp', 'allemp', 'id', 'flag'));
    }

    public function store(Request $request, $id)
    {
        $id = decrypt($id);
        // dd($request->all());

        $request->validate([
            'id_master_regus' => 'required',
            'id_master_employees' => 'required',
        ]);
        
        DB::beginTransaction();
        try{
            $data = MstOperators::create([
                'id_master_regus' => $request->id_master_regus,
                'id_master_employees' => $request->id_master_employees
            ]);

            $emp = MstEmployees::where('id', $request->id_master_employees)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Operator ('. $emp->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Operator']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Operator!']);
        }
    }

    public function delete($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            MstOperators::where('id', $id)->delete();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Delete Operator';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Operator']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Delete Operator']);
        }
    }
}
