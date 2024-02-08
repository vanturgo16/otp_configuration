<?php

namespace App\Http\Controllers;

use App\Models\MstRawMaterials;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstWipRefs;
use App\Models\MstWips;

class MstWipRefsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request, $id)
    {
        $id = decrypt($id);
        // dd($id);

        $id_master_raw_materials = $request->get('id_master_raw_materials');
        $weight = $request->get('weight');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstWipRefs::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_wips.wip_code',
            'master_raw_materials.description as raw_material',
            'master_wip_refs.*'
        )
        ->leftjoin('master_wips', 'master_wip_refs.id_master_wips', 'master_wips.id')
        ->leftjoin('master_raw_materials', 'master_wip_refs.id_master_raw_materials', 'master_raw_materials.id')
        ->where('master_wip_refs.id_master_wips', $id);

        if($id_master_raw_materials != null){
            $datas = $datas->where('master_wip_refs.id_master_raw_materials', $id_master_raw_materials);
        }
        if($weight != null){
            $datas = $datas->where('master_wip_refs.weight', 'like', '%'.$weight.'%');
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('master_wip_refs.created_at','>=',$startdate)->whereDate('master_wip_refs.created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id', 'id_master_wips', 'id_master_raw_materials']);
            return $datas;
        }

        $datas = $datas->paginate(10);

        $wips = MstWips::where('id', $id)->first();
        $rawmaterials = MstRawMaterials::where('status', 'Active')->get();
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Wip Refs From '. $wips->description;
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('wiprefs.index',compact('datas', 'wips', 'rawmaterials', 'id',
            'id_master_raw_materials', 'weight', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request, $id)
    {
        $id = decrypt($id);
        // dd($request->all());

        $request->validate([
            'id_master_wips' => 'required',
            'id_master_raw_materials' => 'required',
            'weight' => 'required',
        ]);
        
        DB::beginTransaction();
        try{
            $data = MstWipRefs::create([
                'id_master_wips' => $request->id_master_wips,
                'id_master_raw_materials' => $request->id_master_raw_materials,
                'weight' => $request->weight
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Wip Refs';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Wip Refs']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Wip Refs!']);
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'id_master_wips' => 'required',
            'id_master_raw_materials' => 'required',
            'weight' => 'required',
        ]);

        $databefore = MstWipRefs::where('id', $id)->first();
        $databefore->id_master_raw_materials = $request->id_master_raw_materials;
        $databefore->weight = $request->weight;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $data = MstWipRefs::where('id', $id)->update([
                    'id_master_raw_materials' => $request->id_master_raw_materials,
                    'weight' => $request->weight,
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Wip Refs';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Wip Refs']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Wip Refs!']);
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
            MstWipRefs::where('id', $id)->delete();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Delete Wip Refs';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Wip Refs']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Delete Wip Refs!']);
        }
    }
}
