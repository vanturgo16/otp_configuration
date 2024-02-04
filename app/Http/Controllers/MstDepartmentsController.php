<?php

namespace App\Http\Controllers;

use App\Models\MstBagians;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstDepartments;

class MstDepartmentsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $departement_code = $request->get('departement_code');
        $name = $request->get('name');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstDepartments::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_departements.*'
        );

        if($departement_code != null){
            $datas = $datas->where('vehicle_number', 'like', '%'.$departement_code.'%');
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
        $activity='View List Mst Department';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('department.index',compact('datas',
            'departement_code', 'name', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'departement_code' => 'required',
            'name' => 'required',
        ]);

        $count= MstDepartments::where('name',$request->name)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Department Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstDepartments::create([
                    'departement_code' => $request->departement_code,
                    'name' => $request->name,
                    'is_active' => '1'
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Department ('. $request->name . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Department']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Create New Department!']);
            }
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'departement_code' => 'required',
            'name' => 'required',
        ]);

        $databefore = MstDepartments::where('id', $id)->first();
        $databefore->departement_code = $request->departement_code;
        $databefore->name = $request->name;

        if($databefore->isDirty()){
            $count= MstDepartments::where('name',$request->name)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Department Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstDepartments::where('id', $id)->update([
                        'departement_code' => $request->departement_code,
                        'name' => $request->name,
                    ]);

                    //Audit Log
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Department ('. $request->name . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Department']);
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['fail' => 'Failed to Update Department!']);
                }
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstDepartments::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstDepartments::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Department ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Department ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Department ' . $name->name .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstDepartments::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstDepartments::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Department ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Department ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Department ' . $name->name .'!']);
        }
    }

    public function mappingBagian($id)
    {
        $datas = MstBagians::select('id', 'name')
            ->where('id_master_departements', $id)
            ->where('status', 'Active')
            ->get();

        return json_encode($datas);
    }
}
