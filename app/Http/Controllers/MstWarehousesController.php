<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstWarehouses;

class MstWarehousesController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $warehouse_code = $request->get('warehouse_code');
        $warehouse = $request->get('warehouse');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstWarehouses::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_warehouses.*'
        );

        if($warehouse_code != null){
            $datas = $datas->where('warehouse_code', 'like', '%'.$warehouse_code.'%');
        }
        if($warehouse != null){
            $datas = $datas->where('warehouse', 'like', '%'.$warehouse.'%');
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

        $datas = $datas->paginate(10);
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Warehouse';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('warehouse.index',compact('datas',
            'warehouse_code', 'warehouse', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'warehouse_code' => 'required',
            'warehouse' => 'required',
        ]);

        $count= MstWarehouses::where('warehouse',$request->warehouse)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Warehouse Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstWarehouses::create([
                    'warehouse_code' => $request->warehouse_code,
                    'warehouse' => $request->warehouse,
                    'is_active' => '1'
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Warehouse ('. $request->warehouse . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Warehouse']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Create New Warehouse!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'warehouse_code' => 'required',
            'warehouse' => 'required',
        ]);

        $databefore = MstWarehouses::where('id', $id)->first();
        $databefore->warehouse_code = $request->warehouse_code;
        $databefore->warehouse = $request->warehouse;

        if($databefore->isDirty()){
            $count= MstWarehouses::where('warehouse',$request->warehouse)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Warehouse Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstWarehouses::where('id', $id)->update([
                        'warehouse_code' => $request->warehouse_code,
                        'warehouse' => $request->warehouse,
                    ]);

                    //Audit Log
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Warehouse ('. $request->warehouse . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Warehouse']);
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['fail' => 'Failed to Update Warehouse!']);
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
            $data = MstWarehouses::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstWarehouses::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Warehouse ('. $name->warehouse . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Warehouse ' . $name->warehouse]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Warehouse ' . $name->warehouse .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstWarehouses::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstWarehouses::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Warehouse ('. $name->warehouse . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Warehouse ' . $name->warehouse]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Warehouse ' . $name->warehouse .'!']);
        }
    }
}
