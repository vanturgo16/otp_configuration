<?php

namespace App\Http\Controllers;

use App\Models\MstRawMaterials;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstWipRefWips;
use App\Models\MstWips;
use App\Models\MstUnits;

class MstWipRefWipsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request, $id)
    {
        $id = decrypt($id);
        // dd($id);

        $id_master_wips_material = $request->get('id_master_wips_material');
        $qty = $request->get('qty');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstWipRefWips::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_wips.description as wip_description',
            'mwp.description as wip_material_description',
            'master_wip_ref_wips.qty', 'master_wip_ref_wips.qty_results',
            'master_units.unit',
            'master_wip_ref_wips.*'
        )
        ->leftjoin('master_wips', 'master_wip_ref_wips.id_master_wips', 'master_wips.id')
        ->leftjoin('master_wips as mwp', 'master_wip_ref_wips.id_master_wips_material', 'mwp.id')
        ->leftjoin('master_units', 'master_wip_ref_wips.master_units_id', 'master_units.id')
        ->where('master_wip_ref_wips.id_master_wips', $id);

        if($id_master_wips_material != null){
            $datas = $datas->where('master_wip_ref_wips.id_master_wips_material', $id_master_wips_material);
        }
        if($qty != null){
            $datas = $datas->where('master_wip_ref_wips.qty', 'like', '%'.$qty.'%');
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('master_wip_ref_wips.created_at','>=',$startdate)->whereDate('master_wip_ref_wips.created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id', 'id_master_wips', 'id_master_wips_material', 'master_units_id']);
            return $datas;
        }

        $datas = $datas->paginate(10);

        $wips = MstWips::where('id', $id)->first();
        $wipmaterials = MstWips::where('status', 'Active')->get();
        $units = MstUnits::get();
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Wip Refs Wip From '. $wips->description;
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('wiprefwips.index',compact('datas', 'wips', 'wipmaterials', 'units', 'id',
            'id_master_wips_material', 'qty', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request, $id)
    {
        $id = decrypt($id);
        // dd($request->all());

        $request->validate([
            'id_master_wips' => 'required',
            'id_master_wips_material' => 'required',
            'qty' => 'required',
            'qty_results' => 'required',
            'master_units_id' => 'required',
        ]);
        
        DB::beginTransaction();
        try{
            $data = MstWipRefWips::create([
                'id_master_wips' => $request->id_master_wips,
                'id_master_wips_material' => $request->id_master_wips_material,
                'qty' => $request->qty,
                'qty_results' => $request->qty_results,
                'master_units_id' => $request->master_units_id,
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Wip Ref Wips';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Wip Ref Wips']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Wip Ref Wips!']);
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'id_master_wips_material' => 'required',
            'qty' => 'required',
            'qty_results' => 'required',
            'master_units_id' => 'required',
        ]);

        $databefore = MstWipRefWips::where('id', $id)->first();
        $databefore->id_master_wips_material = $request->id_master_wips_material;
        $databefore->qty = $request->qty;
        $databefore->qty_results = $request->qty_results;
        $databefore->master_units_id = $request->master_units_id;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $data = MstWipRefWips::where('id', $id)->update([
                    'id_master_wips_material' => $request->id_master_wips_material,
                    'qty' => $request->qty,
                    'qty_results' => $request->qty_results,
                    'master_units_id' => $request->master_units_id,
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Wip Ref Wips';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Wip Ref Wips']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Wip Ref Wips!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }
    public function delete($id)
    {
        $id = decrypt($id);

        // dd($id);

        DB::beginTransaction();
        try{
            MstWipRefWips::where('id', $id)->delete();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Delete Wip Ref Wips';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Wip Ref Wips']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Delete Wip Ref Wips!']);
        }
    }
}
