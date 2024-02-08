<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstGroups;

class MstGroupsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $group_code = $request->get('group_code');
        $name = $request->get('name');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstGroups::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_groups.*'
        );

        if($group_code != null){
            $datas = $datas->where('group_code', 'like', '%'.$group_code.'%');
        }
        if($name != null){
            $datas = $datas->where('name', 'like', '%'.$name.'%');
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
        $activity='View List Mst Group';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('group.index',compact('datas',
            'group_code', 'name', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'group_code' => 'required',
            'name' => 'required',
        ]);

        $count= MstGroups::where('name',$request->name)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Group Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstGroups::create([
                    'group_code' => $request->group_code,
                    'name' => $request->name,
                    'is_active' => '1'
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Group ('. $request->name . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Group']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Create New Group!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'group_code' => 'required',
            'name' => 'required',
        ]);

        $databefore = MstGroups::where('id', $id)->first();
        $databefore->group_code = $request->group_code;
        $databefore->name = $request->name;

        if($databefore->isDirty()){
            $count= MstGroups::where('name',$request->name)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Group Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstGroups::where('id', $id)->update([
                        'group_code' => $request->group_code,
                        'name' => $request->name,
                    ]);

                    //Audit Log
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Group ('. $request->name . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Group']);
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['fail' => 'Failed to Update Group!']);
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
            $data = MstGroups::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstGroups::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Group ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Group ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Group ' . $name->name .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstGroups::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstGroups::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Group ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Group ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Group ' . $name->name .'!']);
        }
    }
}
