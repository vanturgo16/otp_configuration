<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstBagians;
use App\Models\MstDepartments;

class MstBagiansController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request, $id)
    {
        $id = decrypt($id);

        $code = $request->get('code');
        $name = $request->get('name');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstBagians::select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
                'master_departements.name as department',
                'master_bagians.*'
            )
            ->leftjoin('master_departements', 'master_bagians.id_master_departements', 'master_departements.id')
            ->where('id_master_departements', $id);

        if($code != null){
            $datas = $datas->where('master_bagians.code', 'like', '%'.$code.'%');
        }
        if($name != null){
            $datas = $datas->where('master_bagians.name', 'like', '%'.$name.'%');
        }
        if($status != null){
            $datas = $datas->where('master_bagians.status', $status);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('master_bagians.created_at','>=',$startdate)->whereDate('master_bagians.created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id', 'id_master_departements']);
            return $datas;
        }

        $datas = $datas->paginate(10);

        $department = MstDepartments::where('id', $id)->first();
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Bagian From '. $department->name;
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('bagian.index',compact('datas', 'department', 'id',
            'code', 'name', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request, $id)
    {
        // dd($request->all());
        $id = decrypt($id);

        $request->validate([
            'code' => 'required',
            'name' => 'required',
            'id_master_departements' => 'required',
        ]);

        $count= MstBagians::where('name',$request->name)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Bagian Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstBagians::create([
                    'code' => $request->code,
                    'name' => $request->name,
                    'id_master_departements' => $request->id_master_departements,
                    'status' => 'Active'
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Bagian ('. $request->name . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Bagian']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Create New Bagian!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'code' => 'required',
            'name' => 'required',
        ]);

        $databefore = MstBagians::where('id', $id)->first();
        $databefore->code = $request->code;
        $databefore->name = $request->name;

        if($databefore->isDirty()){
            $count= MstBagians::where('name',$request->name)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Bagian Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstBagians::where('id', $id)->update([
                        'code' => $request->code,
                        'name' => $request->name,
                    ]);

                    //Audit Log
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Bagian ('. $request->name . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Bagian']);
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['fail' => 'Failed to Update Bagian!']);
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
            $data = MstBagians::where('id', $id)->update([
                'status' => 'Active'
            ]);

            $name = MstBagians::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Bagian ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Bagian ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Bagian ' . $name->name .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstBagians::where('id', $id)->update([
                'status' => 'Innactive'
            ]);

            $name = MstBagians::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Bagian ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Bagian ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Bagian ' . $name->name .'!']);
        }
    }
}
