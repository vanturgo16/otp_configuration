<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstWastes;

class MstWastesController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $waste_code = $request->get('waste_code');
        $waste = $request->get('waste');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $idUpdated = $request->get('idUpdated');

        $datas = MstWastes::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_wastes.*'
        );

        if($waste_code != null){
            $datas = $datas->where('waste_code', 'like', '%'.$waste_code.'%');
        }
        if($waste != null){
            $datas = $datas->where('waste', 'like', '%'.$waste.'%');
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
                    return view('waste.action', compact('data'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        // Get Page Number
        $page_number = 1;
        if ($idUpdated) {
            $page_size = 5;
            $datas = $datas->get();
            $item = $datas->firstWhere('id', $idUpdated);
            if ($item) {
                $index = $datas->search(function ($value) use ($idUpdated) {
                    return $value->id == $idUpdated;
                });
                $page_number = (int) ceil(($index + 1) / $page_size);
            } else {
                $page_number = 1;
            }
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Waste');

        return view('waste.index',compact('datas',
            'waste_code', 'waste', 'status', 'searchDate', 'startdate', 'enddate', 'flag', 'idUpdated', 'page_number'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'waste_code' => 'required',
            'waste' => 'required',
        ]);

        $count= MstWastes::where('waste',$request->waste)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Waste Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstWastes::create([
                    'waste_code' => $request->waste_code,
                    'waste' => $request->waste,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Waste ('. $request->waste . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Waste']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Waste!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'waste_code' => 'required',
            'waste' => 'required',
        ]);

        $databefore = MstWastes::where('id', $id)->first();
        $databefore->waste_code = $request->waste_code;
        $databefore->waste = $request->waste;

        if($databefore->isDirty()){
            $count= MstWastes::where('waste',$request->waste)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->route('waste.index', ['idUpdated' => $id])->with('warning','Waste Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstWastes::where('id', $id)->update([
                        'waste_code' => $request->waste_code,
                        'waste' => $request->waste,
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Waste ('. $request->waste . ')');

                    DB::commit();
                    return redirect()->route('waste.index', ['idUpdated' => $id])->with(['success' => 'Success Update Waste']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->route('waste.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Update Waste!']);
                }
            }
        } else {
            return redirect()->route('waste.index', ['idUpdated' => $id])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstWastes::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstWastes::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Waste ('. $name->waste . ')');

            DB::commit();
            return redirect()->route('waste.index', ['idUpdated' => $id])->with(['success' => 'Success Activate Waste ' . $name->waste]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('waste.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Activate Waste ' . $name->waste .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstWastes::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstWastes::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Waste ('. $name->waste . ')');

            DB::commit();
            return redirect()->route('waste.index', ['idUpdated' => $id])->with(['success' => 'Success Deactivate Waste ' . $name->waste]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('waste.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Deactivate Waste ' . $name->waste .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $waste_code = MstWastes::where('id', $id)->first()->waste_code;
            MstWastes::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Waste : '  . $waste_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $waste_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $waste_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $waste_code = MstWastes::whereIn('id', $idselected)->pluck('waste_code')->toArray();
            $delete = MstWastes::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Waste Selected : ' . implode(', ', $waste_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $waste_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
