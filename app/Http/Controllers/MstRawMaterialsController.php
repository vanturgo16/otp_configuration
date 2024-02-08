<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstRawMaterials;
use App\Models\MstUnits;
use App\Models\MstGroups;
use App\Models\MstGroupSubs;
use App\Models\MstDepartments;

class MstRawMaterialsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $rm_code = $request->get('rm_code');
        $description = $request->get('description');
        $status = $request->get('status');
        $category = $request->get('category');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstRawMaterials::select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
                'master_raw_materials.*', 'master_units.unit', 'master_groups.name as groupname',
                'master_group_subs.name as groupsub', 'master_departements.name as department'
            )
            ->leftjoin('master_units', 'master_raw_materials.id_master_units', 'master_units.id')
            ->leftjoin('master_groups', 'master_raw_materials.id_master_groups', 'master_groups.id')
            ->leftjoin('master_group_subs', 'master_raw_materials.id_master_group_subs', 'master_group_subs.id')
            ->leftjoin('master_departements', 'master_raw_materials.id_master_departements', 'master_departements.id');

        if($rm_code != null){
            $datas = $datas->where('rm_code', 'like', '%'.$rm_code.'%');
        }
        if($description != null){
            $datas = $datas->where('description', 'like', '%'.$description.'%');
        }
        if($status != null){
            $datas = $datas->where('status', $status);
        }
        if($category != null){
            $datas = $datas->where('category', $category);
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
        $groups = MstGroups::where('is_active', 1)->get();
        $allgroups = MstGroups::get();
        $group_subs = MstGroupSubs::where('is_active', 1)->get();
        $allgroup_subs = MstGroupSubs::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Raw Material';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('rawmaterial.index',compact('datas', 'units', 'allunits', 'groups', 'allgroups', 'group_subs',
            'allgroup_subs', 'departments', 'alldepartments',
            'rm_code', 'description', 'status', 'category', 'searchDate', 'startdate', 'enddate', 'flag'));
    }
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'rm_code' => 'required',
            'description' => 'required',
            'category' => 'required',
        ]);
        
        DB::beginTransaction();
        try{
            $data = MstRawMaterials::create([
                'rm_code' => $request->rm_code,
                'description' => $request->description,
                'category' => $request->category,
                'qty' => $request->qty,
                'id_master_units' => $request->id_master_units,
                'id_master_groups' => $request->id_master_groups,
                'id_master_group_subs' => $request->id_master_group_subs,
                'id_master_departements' => $request->id_master_departements,
                'status' => $request->status,
                'stock' => $request->stock,
                'weight' => $request->weight
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Raw Material';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Raw Material']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Raw Material!']);
        }
    }

    public function update(Request $request, $id){
        // $id = decrypt($id);

        // $databefore = MstRawMaterials::where('id', $id)->first();
        // dd($request->all(), $databefore);

        $id = decrypt($id);

        $request->validate([
            'rm_code' => 'required',
            'description' => 'required',
            'category' => 'required',
        ]);

        $databefore = MstRawMaterials::where('id', $id)->first();
        $databefore->rm_code = $request->rm_code;
        $databefore->description = $request->description;
        $databefore->category = $request->category;
        $databefore->qty = $request->qty;
        $databefore->id_master_units = $request->id_master_units;
        $databefore->id_master_groups = $request->id_master_groups;
        $databefore->id_master_group_subs = $request->id_master_group_subs;
        $databefore->id_master_departements = $request->id_master_departements;
        $databefore->status = $request->status;
        $databefore->stock = $request->stock;
        $databefore->weight = $request->weight;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $data = MstRawMaterials::where('id', $id)->update([
                    'rm_code' => $request->rm_code,
                    'description' => $request->description,
                    'category' => $request->category,
                    'qty' => $request->qty,
                    'id_master_units' => $request->id_master_units,
                    'id_master_groups' => $request->id_master_groups,
                    'id_master_group_subs' => $request->id_master_group_subs,
                    'id_master_departements' => $request->id_master_departements,
                    'status' => $request->status,
                    'stock' => $request->stock,
                    'weight' => $request->weight
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Raw Material';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Raw Material']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Raw Material!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstRawMaterials::where('id', $id)->update([
                'status' => 'Active'
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Raw Material';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Raw Material']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Raw Material']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstRawMaterials::where('id', $id)->update([
                'status' => 'Not Active'
            ]);
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Raw Material';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Raw Material']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Raw Material']);
        }
    }
}