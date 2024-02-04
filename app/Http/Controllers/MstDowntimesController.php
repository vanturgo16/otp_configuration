<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstDowntimes;

class MstDowntimesController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $downtime_code = $request->get('downtime_code');
        $downtime = $request->get('downtime');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstDowntimes::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_downtimes.*'
        );

        if($downtime_code != null){
            $datas = $datas->where('downtime_code', 'like', '%'.$downtime_code.'%');
        }
        if($downtime != null){
            $datas = $datas->where('downtime', 'like', '%'.$downtime.'%');
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
        $activity='View List Mst Downtime';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('downtime.index',compact('datas',
            'downtime_code', 'downtime', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'downtime_code' => 'required',
            'downtime' => 'required',
        ]);

        $count= MstDowntimes::where('downtime',$request->downtime)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Downtime Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstDowntimes::create([
                    'downtime_code' => $request->downtime_code,
                    'downtime' => $request->downtime,
                    'is_active' => '1'
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Downtime ('. $request->downtime . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Downtime']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Create New Downtime!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'downtime_code' => 'required',
            'downtime' => 'required',
        ]);

        $databefore = MstDowntimes::where('id', $id)->first();
        $databefore->downtime_code = $request->downtime_code;
        $databefore->downtime = $request->downtime;

        if($databefore->isDirty()){
            $count= MstDowntimes::where('downtime',$request->downtime)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Downtime Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstDowntimes::where('id', $id)->update([
                        'downtime_code' => $request->downtime_code,
                        'downtime' => $request->downtime,
                    ]);

                    //Audit Log
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Downtime ('. $request->downtime . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Downtime']);
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['fail' => 'Failed to Update Downtime!']);
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
            $data = MstDowntimes::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstDowntimes::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Downtime ('. $name->downtime . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Downtime ' . $name->downtime]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Downtime ' . $name->downtime .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstDowntimes::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstDowntimes::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Downtime ('. $name->downtime . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Downtime ' . $name->downtime]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Downtime ' . $name->downtime .'!']);
        }
    }
}
