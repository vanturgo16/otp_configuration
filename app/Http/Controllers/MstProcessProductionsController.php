<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstProcessProductions;
use App\Models\MstWorkCenters;

class MstProcessProductionsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $process_code = $request->get('process_code');
        $process = $request->get('process');
        $result_location_code = $request->get('result_location_code');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $idUpdated = $request->get('idUpdated');

        $datas = MstProcessProductions::select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
                'master_process_productions.*'
            );

        if($process_code != null){
            $datas = $datas->where('process_code', 'like', '%'.$process_code.'%');
        }
        if($process != null){
            $datas = $datas->where('process', 'like', '%'.$process.'%');
        }
        if($result_location_code != null){
            $datas = $datas->where('result_location_code', 'like', '%'.$result_location_code.'%');
        }
        if($status != null){
            $datas = $datas->where('status', $status);
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
                    return view('processproduction.action', compact('data'));
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
        $this->auditLogsShort('View List Mst Process Production');

        return view('processproduction.index',compact('datas',
            'process_code', 'process', 'result_location_code', 'status', 'searchDate', 'startdate', 'enddate', 'flag', 'idUpdated', 'page_number'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'process_code' => 'required',
            'process' => 'required',
            'result_location_code' => 'required',
        ]);

        $count= MstProcessProductions::where('process',$request->process)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Process Production Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstProcessProductions::create([
                    'process_code' => $request->process_code,
                    'process' => $request->process,
                    'result_location_code' => $request->result_location_code,
                    'status' => 'Active'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Process Production ('. $request->process . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Process Production']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Process Production!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'process_code' => 'required',
            'process' => 'required',
            'result_location_code' => 'required',
        ]);

        $databefore = MstProcessProductions::where('id', $id)->first();
        $databefore->process_code = $request->process_code;
        $databefore->process = $request->process;
        $databefore->result_location_code = $request->result_location_code;

        if($databefore->isDirty()){
            $count= MstProcessProductions::where('process',$request->process)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->route('processproduction.index', ['idUpdated' => $id])->with('warning','Process Production Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstProcessProductions::where('id', $id)->update([
                        'process_code' => $request->process_code,
                        'process' => $request->process,
                        'result_location_code' => $request->result_location_code,
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Process Production ('. $request->process . ')');

                    DB::commit();
                    return redirect()->route('processproduction.index', ['idUpdated' => $id])->with(['success' => 'Success Update Process Production']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->route('processproduction.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Update Process Production!']);
                }
            }
        } else {
            return redirect()->route('processproduction.index', ['idUpdated' => $id])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstProcessProductions::where('id', $id)->update([
                'status' => 'Active'
            ]);

            $name = MstProcessProductions::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Process Production ('. $name->process . ')');

            DB::commit();
            return redirect()->route('processproduction.index', ['idUpdated' => $id])->with(['success' => 'Success Activate Process Production ' . $name->process]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('processproduction.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Activate Process Production ' . $name->process .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstProcessProductions::where('id', $id)->update([
                'status' => 'Innactive'
            ]);

            $name = MstProcessProductions::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Process Production ('. $name->process . ')');

            DB::commit();
            return redirect()->route('processproduction.index', ['idUpdated' => $id])->with(['success' => 'Success Deactivate Process Production ' . $name->process]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('processproduction.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Deactivate Process Production ' . $name->process .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $process_code = MstProcessProductions::where('id', $id)->first()->process_code;
            MstProcessProductions::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Process Production : '  . $process_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $process_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $process_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $process_code = MstProcessProductions::whereIn('id', $idselected)->pluck('process_code')->toArray();;
            $delete = MstProcessProductions::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Process Production Selected : ' . implode(', ', $process_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $process_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
