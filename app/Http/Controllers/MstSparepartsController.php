<?php

namespace App\Http\Controllers;

use App\Models\MstDepartments;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstSpareparts;
use App\Models\MstUnits;

class MstSparepartsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $code = $request->get('code');
        $description = $request->get('description');
        $status_stock = $request->get('status_stock');
        $type = $request->get('type');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstSpareparts::select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
                'master_tool_auxiliaries.*', 'master_units.unit', 'master_departements.name'
            )
            ->leftjoin('master_units', 'master_tool_auxiliaries.id_master_units', 'master_units.id')
            ->leftjoin('master_departements', 'master_tool_auxiliaries.id_master_departements', 'master_departements.id');

        if($code != null){
            $datas = $datas->where('code', 'like', '%'.$code.'%');
        }
        if($description != null){
            $datas = $datas->where('description', 'like', '%'.$description.'%');
        }
        if($status_stock == null || $status_stock == 'on' || $status_stock == 'Y'){
            $datas = $datas->where('status_stock', 'Y');
            $status_stock = 'Y';
        } 
        else {
            $datas = $datas->where('status_stock', 'N');
        }

        if($type != null){
            $datas = $datas->where('type', $type);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('created_at','>=',$startdate)->whereDate('created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id']);
            return $datas;
        }

        $datas = $datas->paginate(10);

        $units = MstUnits::where('is_active', 1)->get();
        $allunits = MstUnits::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Sparepart';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('sparepart.index',compact('datas', 'units', 'allunits', 'departments', 'alldepartments',
            'code', 'description', 'status_stock', 'type', 'searchDate', 'startdate', 'enddate', 'flag'));
    }
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'code' => 'required',
            'description' => 'required',
            'type' => 'required',
        ]);
        if($request->status_stock == null){
            $status_stock= 'N';
        } else{
            $status_stock= 'Y';
        }
        
        DB::beginTransaction();
        try{
            $data = MstSpareparts::create([
                'code' => $request->code,
                'description' => $request->description,
                'stock' => $request->stock,
                'type' => $request->type,
                'id_master_units' => $request->id_master_units,
                'id_master_departements' => $request->id_master_departements,
                'status_stock' => $status_stock
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Sparepart';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Sparepart']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Sparepart!']);
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'code' => 'required',
            'description' => 'required',
            'type' => 'required',
        ]);
        if($request->status_stock == null){
            $status_stock= 'N';
        } else{
            $status_stock= 'Y';
        }

        $databefore = MstSpareparts::where('id', $id)->first();
        $databefore->code = $request->code;
        $databefore->description = $request->description;
        $databefore->stock = $request->stock;
        $databefore->type = $request->type;
        $databefore->id_master_units = $request->id_master_units;
        $databefore->id_master_departements = $request->id_master_departements;
        $databefore->status_stock = $request->status_stock;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $data = MstSpareparts::where('id', $id)->update([
                    'code' => $request->code,
                    'description' => $request->description,
                    'stock' => $request->stock,
                    'type' => $request->type,
                    'id_master_units' => $request->id_master_units,
                    'id_master_departements' => $request->id_master_departements,
                    'status_stock' => $status_stock
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Sparepart';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Sparepart']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Sparepart!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstSpareparts::where('id', $id)->update([
                'status_stock' => 'Y'
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Sparepart';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success In Stock Sparepart']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to In Stock Sparepart']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstSpareparts::where('id', $id)->update([
                'status_stock' => 'N'
            ]);
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Sparepart';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Out Stock Sparepart']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Out Stock Sparepart']);
        }
    }
}