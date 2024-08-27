<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstReasons;

class MstReasonsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $reason_code = $request->get('reason_code');
        $reason = $request->get('reason');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstReasons::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_reasons.*'
        );

        if($reason_code != null){
            $datas = $datas->where('reason_code', 'like', '%'.$reason_code.'%');
        }
        if($reason != null){
            $datas = $datas->where('reason', 'like', '%'.$reason.'%');
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

        $datas = $datas->orderBy('created_at', 'desc');
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data){
                    return view('reason.action', compact('data'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Reason');

        return view('reason.index',compact('datas',
            'reason_code', 'reason', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'reason_code' => 'required',
            'reason' => 'required',
        ]);

        $count= MstReasons::where('reason',$request->reason)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Reason Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstReasons::create([
                    'reason_code' => $request->reason_code,
                    'reason' => $request->reason,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Reason ('. $request->reason . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Reason']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Reason!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'reason_code' => 'required',
            'reason' => 'required',
        ]);

        $databefore = MstReasons::where('id', $id)->first();
        $databefore->reason_code = $request->reason_code;
        $databefore->reason = $request->reason;

        if($databefore->isDirty()){
            $count= MstReasons::where('reason',$request->reason)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Reason Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstReasons::where('id', $id)->update([
                        'reason_code' => $request->reason_code,
                        'reason' => $request->reason,
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Reason ('. $request->reason . ')');

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Reason']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with(['fail' => 'Failed to Update Reason!']);
                }
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstReasons::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstReasons::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Reason ('. $name->reason . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Reason ' . $name->reason]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Reason ' . $name->reason .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstReasons::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstReasons::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Reason ('. $name->reason . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Reason ' . $name->reason]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Reason ' . $name->reason .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $reason_code = MstReasons::where('id', $id)->first()->reason_code;
            MstReasons::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Reason : '  . $reason_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $reason_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $reason_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $reason_code = MstReasons::whereIn('id', $idselected)->pluck('reason_code')->toArray();
            $delete = MstReasons::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Reason Selected : ' . implode(', ', $reason_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $reason_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
