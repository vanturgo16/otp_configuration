<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstGroupSubs;

class MstGroupSubsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $group_sub_code = $request->get('group_sub_code');
        $name = $request->get('name');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstGroupSubs::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_group_subs.*'
        );

        if($group_sub_code != null){
            $datas = $datas->where('group_sub_code', 'like', '%'.$group_sub_code.'%');
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
        $activity='View List Mst Group Sub';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('groupsub.index',compact('datas',
            'group_sub_code', 'name', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'group_sub_code' => 'required',
            'name' => 'required',
        ]);

        $count= MstGroupSubs::where('name',$request->name)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Group Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstGroupSubs::create([
                    'group_sub_code' => $request->group_sub_code,
                    'name' => $request->name,
                    'is_active' => '1'
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Group Sub ('. $request->name . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Group Sub']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Create New Group Sub!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'group_sub_code' => 'required',
            'name' => 'required',
        ]);

        $databefore = MstGroupSubs::where('id', $id)->first();
        $databefore->group_sub_code = $request->group_sub_code;
        $databefore->name = $request->name;

        if($databefore->isDirty()){
            $count= MstGroupSubs::where('name',$request->name)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Group Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstGroupSubs::where('id', $id)->update([
                        'group_sub_code' => $request->group_sub_code,
                        'name' => $request->name,
                    ]);

                    //Audit Log
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Group Sub ('. $request->name . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Group Sub']);
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['fail' => 'Failed to Update Group Sub!']);
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
            $data = MstGroupSubs::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstGroupSubs::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Group Sub ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Group Sub ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Group Sub ' . $name->name .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstGroupSubs::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstGroupSubs::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Group Sub ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Group Sub ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Group Sub ' . $name->name .'!']);
        }
    }
}
