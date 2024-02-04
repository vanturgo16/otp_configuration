<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstSalesmans;

class MstSalesmansController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
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

        $datas = $datas->paginate(10);
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Salesman';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

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
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Salesman ('. $request->name . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Salesman']);
            } catch (\Exception $e) {
                dd($e);
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
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Salesman ('. $request->name . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Salesman']);
                } catch (\Exception $e) {
                    dd($e);
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
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Salesman ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Salesman ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
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
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Salesman ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Salesman ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Salesman ' . $name->name .'!']);
        }
    }
}
