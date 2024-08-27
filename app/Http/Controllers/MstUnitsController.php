<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstUnits;

class MstUnitsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $unit_code = $request->get('unit_code');
        $unit = $request->get('unit');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstUnits::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_units.*'
        );

        if($unit_code != null){
            $datas = $datas->where('unit_code', 'like', '%'.$unit_code.'%');
        }
        if($unit != null){
            $datas = $datas->where('unit', 'like', '%'.$unit.'%');
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
                    return view('unit.action', compact('data'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Unit');

        return view('unit.index',compact('datas',
            'unit_code', 'unit', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'unit_code' => 'required',
            'unit' => 'required',
        ]);

        $count= MstUnits::where('unit',$request->unit)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Unit Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstUnits::create([
                    'unit_code' => $request->unit_code,
                    'unit' => $request->unit,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Unit ('. $request->unit . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Unit']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Unit!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'unit_code' => 'required',
            'unit' => 'required',
        ]);

        $databefore = MstUnits::where('id', $id)->first();
        $databefore->unit_code = $request->unit_code;
        $databefore->unit = $request->unit;

        if($databefore->isDirty()){
            $count= MstUnits::where('unit',$request->unit)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Unit Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstUnits::where('id', $id)->update([
                        'unit_code' => $request->unit_code,
                        'unit' => $request->unit,
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Unit ('. $request->unit . ')');

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Unit']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with(['fail' => 'Failed to Update Unit!']);
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
            $data = MstUnits::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstUnits::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Unit ('. $name->unit . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Unit ' . $name->unit]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Unit ' . $name->unit .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstUnits::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstUnits::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Unit ('. $name->unit . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Unit ' . $name->unit]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Unit ' . $name->unit .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $unit_code = MstUnits::where('id', $id)->first()->unit_code;
            MstUnits::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Unit : '  . $unit_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $unit_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $unit_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $unit_code = MstUnits::whereIn('id', $idselected)->pluck('unit_code')->toArray();
            $delete = MstUnits::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Unit Selected : ' . implode(', ', $unit_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $unit_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
