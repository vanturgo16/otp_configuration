<?php

namespace App\Http\Controllers;

use App\Models\MstEmployees;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
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
        
        // Initiate Variable
        $flag = $request->get('flag');
        $rg = MstRegus::where('id', $id)->first();
        $emp = MstEmployees::where('status', 'Active')->get();
        $allemp = MstEmployees::get();

        $datas = MstOperators::select('master_operators.*', 'master_regus.regu', 'master_employees.name')
            ->leftjoin('master_regus', 'master_operators.id_master_regus', 'master_regus.id')
            ->leftjoin('master_employees', 'master_operators.id_master_employees', 'master_employees.id')
            ->where('master_operators.id_master_regus', $id);

        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id', 'id_master_regus', 'id_master_employees']);
            return $datas;
        }

        $datas = $datas->orderBy('created_at', 'desc');
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use($rg){
                    return view('operator.action', compact('data', 'rg'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Operator From '. $rg->regu);

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
            $this->auditLogsShort('Create New Operator ('. $emp->name . ')');

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Operator']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Operator!']);
        }
    }

    public function delete($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            MstOperators::where('id', $id)->delete();
            
            //Audit Log
            $this->auditLogsShort('Delete Operator');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Operator']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Operator']);
        }
    }
    
    public function deleteselected(Request $request, $id)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $delete = MstOperators::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Operator Selected');

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data', 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
