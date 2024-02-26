<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstApprovals;
use App\Models\MstEmployees;

class MstApprovalsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Initiate Variable
        $emp = MstEmployees::where('status', 'Active')->get();
        $allemp = MstEmployees::get();
        
        // Search Variable
        $type = $request->get('type');
        $id_master_employees = $request->get('id_master_employees');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstApprovals::select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
                'master_approvals.*',
                'master_employees.name as employeename'
            )
            ->leftjoin('master_employees', 'master_approvals.id_master_employees', 'master_employees.id');

        if($type != null){
            $datas = $datas->where('type', 'like', '%'.$type.'%');
        }
        if($id_master_employees != null){
            $datas = $datas->where('id_master_employees', 'like', '%'.$id_master_employees.'%');
        }
        if($status != null){
            $datas = $datas->where('is_active', $status);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('created_at','>=',$startdate)->whereDate('created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id']);
            return $datas;
        }

        $datas = $datas->get();
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($emp, $allemp, $type){
                    return view('approval.action', compact('data', 'emp', 'allemp', 'type'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Approval');

        return view('approval.index',compact('datas', 'emp', 'allemp',
            'type', 'id_master_employees', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'type' => 'required',
            'id_master_employees' => 'required',
        ]);

        DB::beginTransaction();
        try{
            $data = MstApprovals::create([
                'type' => $request->type,
                'id_master_employees' => $request->id_master_employees,
                'status' => 'Active'
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Approval ('. $request->type . ')');

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Approval']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Approval!']);
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'type' => 'required',
            'id_master_employees' => 'required',
        ]);

        $databefore = MstApprovals::where('id', $id)->first();
        $databefore->type = $request->type;
        $databefore->id_master_employees = $request->id_master_employees;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $data = MstApprovals::where('id', $id)->update([
                    'type' => $request->type,
                    'id_master_employees' => $request->id_master_employees,
                ]);

                //Audit Log
                $this->auditLogsShort('Update Approval ('. $request->type . ')');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Approval']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update Approval!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstApprovals::where('id', $id)->update([
                'status' => "Active"
            ]);

            $name = MstApprovals::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Approval ('. $name->type . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Approval ' . $name->type]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Approval ' . $name->type .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstApprovals::where('id', $id)->update([
                'status' => "Innactive"
            ]);

            $name = MstApprovals::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Approval ('. $name->type . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Approval ' . $name->type]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Approval ' . $name->type .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $type = MstApprovals::where('id', $id)->first()->type;
            MstApprovals::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Approval : '  . $type);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $type]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $type .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $type = MstApprovals::whereIn('id', $idselected)->pluck('type')->toArray();
            $delete = MstApprovals::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Approval Selected : ' . implode(', ', $type));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $type), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
