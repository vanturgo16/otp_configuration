<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstCostCenters;

class MstCostCentersController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $cost_center_code = $request->get('cost_center_code');
        $cost_center = $request->get('cost_center');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstCostCenters::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_cost_centers.*'
        );

        if($cost_center_code != null){
            $datas = $datas->where('cost_center_code', 'like', '%'.$cost_center_code.'%');
        }
        if($cost_center != null){
            $datas = $datas->where('cost_center', 'like', '%'.$cost_center.'%');
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
                ->addColumn('action', function ($data){
                    return view('costcenter.action', compact('data'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Cost Center');

        return view('costcenter.index',compact('datas',
            'cost_center_code', 'cost_center', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'cost_center_code' => 'required',
            'cost_center' => 'required',
        ]);

        $count= MstCostCenters::where('cost_center',$request->cost_center)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Cost Center Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstCostCenters::create([
                    'cost_center_code' => $request->cost_center_code,
                    'cost_center' => $request->cost_center,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Cost Center ('. $request->cost_center . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Cost Center']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Cost Center!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'cost_center_code' => 'required',
            'cost_center' => 'required',
        ]);

        $databefore = MstCostCenters::where('id', $id)->first();
        $databefore->cost_center_code = $request->cost_center_code;
        $databefore->cost_center = $request->cost_center;

        if($databefore->isDirty()){
            $count= MstCostCenters::where('cost_center',$request->cost_center)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Cost Center Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstCostCenters::where('id', $id)->update([
                        'cost_center_code' => $request->cost_center_code,
                        'cost_center' => $request->cost_center,
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Cost Center ('. $request->cost_center . ')');

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Cost Center']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with(['fail' => 'Failed to Update Cost Center!']);
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
            $data = MstCostCenters::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstCostCenters::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Cost Center ('. $name->cost_center . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Cost Center ' . $name->cost_center]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Cost Center ' . $name->cost_center .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstCostCenters::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstCostCenters::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Cost Center ('. $name->cost_center . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Cost Center ' . $name->cost_center]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Cost Center ' . $name->cost_center .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $cost_center_code = MstCostCenters::where('id', $id)->first()->cost_center_code;
            MstCostCenters::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Cost Center : '  . $cost_center_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $cost_center_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $cost_center_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $cost_center_code = MstCostCenters::whereIn('id', $idselected)->pluck('cost_center_code')->toArray();;
            $delete = MstCostCenters::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Cost Center Selected : ' . implode(', ', $cost_center_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $cost_center_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
