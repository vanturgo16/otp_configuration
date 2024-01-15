<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstRegus;
use App\Models\MstWorkCenters;

class MstRegusController extends Controller
{
    use AuditLogsTrait;

    public function index($id){
        $id = decrypt($id);

        $datas = MstRegus::where('id_master_work_centers', $id)->get();
        $wc = MstWorkCenters::where('id', $id)->first();
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Regu From '. $wc->work_center;
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('regu.index',compact('datas', 'wc'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'regu_code' => 'required',
            'regu' => 'required',
            'id_master_work_centers' => 'required',
        ]);
        
        DB::beginTransaction();
        try{
            $data = MstRegus::create([
                'regu_code' => $request->regu_code,
                'regu' => $request->regu,
                'id_master_work_centers' => $request->id_master_work_centers,
                'is_active' => 1
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Regu ('. $request->regu . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Regu']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Regu!']);
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'regu_code' => 'required',
            'regu' => 'required',
        ]);

        $databefore = MstRegus::where('id', $id)->first();
        $databefore->regu_code = $request->regu_code;
        $databefore->regu = $request->regu;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $data = MstRegus::where('id', $id)->update([
                    'regu_code' => $request->regu_code,
                    'regu' => $request->regu,
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Regu ('. $request->regu . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Regu']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Regu!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstRegus::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstRegus::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Regu ('. $name->regu . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Regu ' . $name->regu]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Regu ' . $name->regu .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstRegus::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstRegus::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Regu ('. $name->regu . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Regu ' . $name->regu]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Regu ' . $name->regu .'!']);
        }
    }
}
