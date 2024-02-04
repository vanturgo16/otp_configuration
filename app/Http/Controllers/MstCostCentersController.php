<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstCostCenters;

class MstCostCentersController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
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

        $datas = $datas->paginate(10);

        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Cost Center';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

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
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Cost Center ('. $request->cost_center . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Cost Center']);
            } catch (\Exception $e) {
                dd($e);
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
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Cost Center ('. $request->cost_center . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Cost Center']);
                } catch (\Exception $e) {
                    dd($e);
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
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Cost Center ('. $name->cost_center . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Cost Center ' . $name->cost_center]);
        } catch (\Exception $e) {
            dd($e);
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
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Cost Center ('. $name->cost_center . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Cost Center ' . $name->cost_center]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Cost Center ' . $name->cost_center .'!']);
        }
    }
}
