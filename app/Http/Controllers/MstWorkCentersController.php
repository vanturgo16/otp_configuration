<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstWorkCenters;
use App\Models\MstProcessProductions;

class MstWorkCentersController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request, $id)
    {
        $id = decrypt($id);

        $work_center_code = $request->get('work_center_code');
        $work_center = $request->get('work_center');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstWorkCenters::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_process_productions.process as process_production',
            'master_work_centers.*'
        )
        ->leftjoin('master_process_productions', 'master_work_centers.id_master_process_productions', 'master_process_productions.id')
        ->where('id_master_process_productions', $id);

        if($work_center_code != null){
            $datas = $datas->where('master_work_centers.work_center_code', 'like', '%'.$work_center_code.'%');
        }
        if($work_center != null){
            $datas = $datas->where('master_work_centers.work_center', 'like', '%'.$work_center.'%');
        }
        if($status != null){
            $datas = $datas->where('master_work_centers.status', $status);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('master_work_centers.created_at','>=',$startdate)->whereDate('master_work_centers.created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id', 'id_master_process_productions']);
            return $datas;
        }

        $datas = $datas->paginate(10);

        $procproduction = MstProcessProductions::where('id', $id)->first();
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Work Center From '. $procproduction->process;
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('workcenter.index',compact('datas', 'procproduction', 'id',
            'work_center_code', 'work_center', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request, $id)
    {
        $id = decrypt($id);
        // dd($request->all());

        $request->validate([
            'work_center_code' => 'required',
            'work_center' => 'required',
            'id_master_process_productions' => 'required',
        ]);

        $count= MstWorkCenters::where('work_center',$request->work_center)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Work Center Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstWorkCenters::create([
                    'work_center_code' => $request->work_center_code,
                    'work_center' => $request->work_center,
                    'id_master_process_productions' => $request->id_master_process_productions,
                    'status' => 'Active'
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Work Center ('. $request->work_center . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Work Center']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Create New Work Center!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'work_center_code' => 'required',
            'work_center' => 'required',
        ]);

        $databefore = MstWorkCenters::where('id', $id)->first();
        $databefore->work_center_code = $request->work_center_code;
        $databefore->work_center = $request->work_center;

        if($databefore->isDirty()){
            $count= MstWorkCenters::where('work_center',$request->work_center)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Work Center Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstWorkCenters::where('id', $id)->update([
                        'work_center_code' => $request->work_center_code,
                        'work_center' => $request->work_center,
                    ]);

                    //Audit Log
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Work Center ('. $request->work_center . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Work Center']);
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['fail' => 'Failed to Update Work Center!']);
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
            $data = MstWorkCenters::where('id', $id)->update([
                'status' => 'Active'
            ]);

            $name = MstWorkCenters::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Work Center ('. $name->work_center . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Work Center ' . $name->work_center]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Work Center ' . $name->work_center .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstWorkCenters::where('id', $id)->update([
                'status' => 'Innactive'
            ]);

            $name = MstWorkCenters::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Work Center ('. $name->work_center . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Work Center ' . $name->work_center]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Work Center ' . $name->work_center .'!']);
        }
    }
}
