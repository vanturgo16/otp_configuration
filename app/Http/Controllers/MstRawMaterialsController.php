<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstRawMaterials;
use App\Models\MstUnits;
use App\Models\MstGroups;
use App\Models\MstGroupSubs;
use App\Models\MstDepartments;
use App\Models\MstDropdowns;

class MstRawMaterialsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Initiate Variable
        $units = MstUnits::where('is_active', 1)->get();
        $allunits = MstUnits::get();
        $groups = MstGroups::where('is_active', 1)->get();
        $allgroups = MstGroups::get();
        $group_subs = MstGroupSubs::where('is_active', 1)->get();
        $allgroup_subs = MstGroupSubs::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();
        $categories = MstDropdowns::where('category', 'Category RAW')->get();
        
        // Search Variable
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

        $datas = $datas->get();
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($units, $allunits, $groups, $allgroups, $group_subs, $allgroup_subs, $departments, $alldepartments, $categories){
                    return view('rawmaterial.action', compact('data', 'units', 'allunits', 'groups', 'allgroups', 'group_subs', 'allgroup_subs', 'departments', 'alldepartments', 'categories'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Raw Material');

        return view('rawmaterial.index',compact('datas', 'units', 'allunits', 'groups', 'allgroups', 'group_subs',
            'allgroup_subs', 'departments', 'alldepartments', 'categories',
            'rm_code', 'description', 'status', 'category', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function generateFormattedId($type, $description, $id)
    {
        $formattedId = str_pad($id, 3, '0', STR_PAD_LEFT);
        $typeCap = strtoupper($type);
        $filteredString = preg_replace("/[^a-zA-Z]/", "", $description);
        $desc = strtoupper(substr($filteredString, 0, 3));
        $desc = strtoupper($desc);
        $code = 'RMBL'.$typeCap.'-'.$desc.$formattedId;

        return $code;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        
        DB::beginTransaction();
        try{
            $data = MstRawMaterials::create([
                'category' => $request->category,
                'description' => $request->description,
                'id_master_units' => $request->id_master_units,
                'rm_code' => 'temp',
                'status' => $request->status,
                'qty' => '0',
                'id_master_groups' => '1',
                'id_master_group_subs' => '1',
                // 'id_master_departements' => $request->id_master_departements,
                // 'stock' => $request->stock,
                // 'weight' => $request->weight
            ]);

            $code = $this->generateFormattedId($request->category, $request->description, $data->id);
            MstRawMaterials::where('id', $data->id)->update([
                'rm_code' => $code
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Raw Material');

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Raw Material']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Raw Material!']);
        }
    }

    public function update(Request $request, $id){
        // $id = decrypt($id);

        // $databefore = MstRawMaterials::where('id', $id)->first();
        // dd($request->all(), $databefore);

        $id = decrypt($id);

        $databefore = MstRawMaterials::where('id', $id)->first();
        $databefore->rm_code = $request->rm_code;
        $databefore->category = $request->category;
        $databefore->description = $request->description;
        $databefore->id_master_units = $request->id_master_units;
        $databefore->status = $request->status;
        // $databefore->qty = $request->qty;
        // $databefore->id_master_groups = $request->id_master_groups;
        // $databefore->id_master_group_subs = $request->id_master_group_subs;
        // $databefore->id_master_departements = $request->id_master_departements;
        // $databefore->stock = $request->stock;
        // $databefore->weight = $request->weight;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $data = MstRawMaterials::where('id', $id)->update([
                    'category' => $request->category,
                    'description' => $request->description,
                    'id_master_units' => $request->id_master_units,
                    'status' => $request->status,
                    // 'qty' => $request->qty,
                    // 'id_master_groups' => $request->id_master_groups,
                    // 'id_master_group_subs' => $request->id_master_group_subs,
                    // 'id_master_departements' => $request->id_master_departements,
                    // 'stock' => $request->stock,
                    // 'weight' => $request->weight
                ]);
                $code = $this->generateFormattedId($request->category, $request->description, $id);
                MstRawMaterials::where('id', $id)->update([
                    'rm_code' => $code
                ]);

                //Audit Log
                $this->auditLogsShort('Update Raw Material');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Raw Material']);
            } catch (Exception $e) {
                DB::rollback();
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
            $this->auditLogsShort('Activate Raw Material');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Raw Material']);
        } catch (Exception $e) {
            DB::rollback();
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
            $this->auditLogsShort('Deactivate Raw Material');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Raw Material']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Raw Material']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $rm_code = MstRawMaterials::where('id', $id)->first()->rm_code;
            MstRawMaterials::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Raw Material : '  . $rm_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $rm_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $rm_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $rm_code = MstRawMaterials::whereIn('id', $idselected)->pluck('rm_code')->toArray();
            $delete = MstRawMaterials::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Raw Material Selected : ' . implode(', ', $rm_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $rm_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}