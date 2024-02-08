<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstWastes;

class MstWastesController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $waste_code = $request->get('waste_code');
        $waste = $request->get('waste');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

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

        $datas = $datas->paginate(10);
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Waste';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('waste.index',compact('datas',
            'waste_code', 'waste', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
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
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Waste ('. $request->waste . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Waste']);
            } catch (\Exception $e) {
                dd($e);
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
                return redirect()->back()->with('warning','Waste Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstWastes::where('id', $id)->update([
                        'waste_code' => $request->waste_code,
                        'waste' => $request->waste,
                    ]);

                    //Audit Log
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Waste ('. $request->waste . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Waste']);
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['fail' => 'Failed to Update Waste!']);
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
            $data = MstWastes::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstWastes::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Waste ('. $name->waste . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Waste ' . $name->waste]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Waste ' . $name->waste .'!']);
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
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Waste ('. $name->waste . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Waste ' . $name->waste]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Waste ' . $name->waste .'!']);
        }
    }
}
