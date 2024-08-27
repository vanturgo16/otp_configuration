<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstSalesmans;

class MstSalesmansController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $salesman_code = $request->get('salesman_code');
        $name = $request->get('name');
        $address = $request->get('address');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstSalesmans::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_salesmen.*'
        );

        if($salesman_code != null){
            $datas = $datas->where('vehicle_number', 'like', '%'.$salesman_code.'%');
        }
        if($name != null){
            $datas = $datas->where('name', 'like', '%'.$name.'%');
        }
        if($address != null){
            $datas = $datas->where('address', 'like', '%'.$address.'%');
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
                    return view('salesman.action', compact('data'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Salesman');

        return view('salesman.index',compact('datas',
            'salesman_code', 'name', 'address', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'salesman_code' => 'required',
            'address' => 'required',
            'name' => 'required',
        ]);

        $count= MstSalesmans::where('name',$request->name)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Salesman Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstSalesmans::create([
                    'salesman_code' => $request->salesman_code,
                    'address' => $request->address,
                    'name' => $request->name,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Salesman ('. $request->name . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Salesman']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Salesman!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'salesman_code' => 'required',
            'address' => 'required',
            'name' => 'required',
        ]);

        $databefore = MstSalesmans::where('id', $id)->first();
        $databefore->salesman_code = $request->salesman_code;
        $databefore->address = $request->address;
        $databefore->name = $request->name;

        if($databefore->isDirty()){
            $count= MstSalesmans::where('name',$request->name)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Salesman Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstSalesmans::where('id', $id)->update([
                        'salesman_code' => $request->salesman_code,
                        'address' => $request->address,
                        'name' => $request->name,
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Salesman ('. $request->name . ')');

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Salesman']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with(['fail' => 'Failed to Update Salesman!']);
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
            $data = MstSalesmans::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstSalesmans::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Salesman ('. $name->name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Salesman ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Salesman ' . $name->name .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstSalesmans::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstSalesmans::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Salesman ('. $name->name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Salesman ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Salesman ' . $name->name .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $salesman_code = MstSalesmans::where('id', $id)->first()->salesman_code;
            MstSalesmans::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Salesman : '  . $salesman_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $salesman_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $salesman_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $salesman_code = MstSalesmans::whereIn('id', $idselected)->pluck('salesman_code')->toArray();;
            $delete = MstSalesmans::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Salesman Selected : ' . implode(', ', $salesman_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $salesman_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
