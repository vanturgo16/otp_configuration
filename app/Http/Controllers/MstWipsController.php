<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstWips;
use App\Models\MstWipRefs;
use App\Models\MstWipRefWips;
use App\Models\MstBagians;
use App\Models\MstProcessProductions;
use App\Models\MstUnits;
use App\Models\MstGroups;
use App\Models\MstGroupSubs;
use App\Models\MstDepartments;
use App\Models\MstDropdowns;
use App\Models\MstRawMaterials;
use Symfony\Component\HttpFoundation\Response;
use Maatwebsite\Excel\Facades\Excel;

class MstWipsController extends Controller
{
    use AuditLogsTrait;

    // function getUnitToMeter($unitName) {
    //     $unitToMeter = [
    //         "M" => 1,
    //         "CM" => 0.01,
    //         "INCH" => 0.0254,
    //         "MM" => 0.001
    //     ]; return $unitToMeter[$unitName] ?? 0;
    // }
    public function index(Request $request)
    {
        // $datas = MstWips::select('master_wips.id', 'master_wips.thickness', 'master_wips.width', 'master_wips.length', 'master_wips.weight',
        //             'master_group_subs.name as group_sub_name', 'w_unit.unit_code as width_unit',  'h_unit.unit_code as length_unit'
        //         )
        //     ->leftjoin('master_group_subs', 'master_wips.id_master_group_subs', 'master_group_subs.id')
        //     ->leftjoin('master_units as w_unit', 'master_wips.width_unit', 'w_unit.id')
        //     ->leftjoin('master_units as h_unit', 'master_wips.length_unit', 'h_unit.id')
        //     ->whereBetween('master_wips.id', [350, 400])
        //     ->get();
        // foreach($datas as $item){
        //     $thickness = (float) ($item->thickness) ?? 0;
        //     if($thickness){
        //         $thickness = $thickness/1000;
        //     }
        //     $width = ((float) ($item->width) ?? 0) * $this->getUnitToMeter($item->width_unit);
        //     $length = ((float) ($item->length) ?? 0) * $this->getUnitToMeter($item->length_unit);
        //     $factor = 2;
        //     $weight = $thickness*$width*$length*$factor*0.92;
        //     $weight = round($weight, 9);
        //     MstWips::where('id', $item->id)->update(['weight' => $weight]);
        // }
        // dd($datas);

        // Initiate Variable
        $process = MstProcessProductions::where('status', 'Active')->get();
        $allprocess = MstProcessProductions::get();
        $units = MstUnits::where('is_active', 1)->get();
        $allunits = MstUnits::get();
        $groups = MstGroups::where('is_active', 1)->get();
        $allgroups = MstGroups::get();
        $group_subs = MstGroupSubs::where('is_active', 1)->get();
        $allgroup_subs = MstGroupSubs::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();

        // Search Variable
        $wip_code = $request->get('wip_code');
        $description = $request->get('description');
        $status = $request->get('status');
        $type = $request->get('type');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstWips::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_wips.*',
            'master_units.unit',
            'master_groups.name as groupname',
            'master_process_productions.process',
            'master_group_subs.name as groupsub',
            'master_departements.name as department',
            'widthUnit.unit_code as width_unt',
            'lengthUnit.unit_code as length_unt'
        )
            ->leftjoin('master_process_productions', 'master_wips.id_master_process_productions', 'master_process_productions.id')
            ->leftjoin('master_units', 'master_wips.id_master_units', 'master_units.id')
            ->leftjoin('master_units as widthUnit', 'master_wips.width_unit', 'widthUnit.id')
            ->leftjoin('master_units as lengthUnit', 'master_wips.length_unit', 'lengthUnit.id')
            ->leftjoin('master_groups', 'master_wips.id_master_groups', 'master_groups.id')
            ->leftjoin('master_group_subs', 'master_wips.id_master_group_subs', 'master_group_subs.id')
            ->leftjoin('master_departements', 'master_wips.id_master_departements', 'master_departements.id');

        if ($wip_code != null) {
            $datas = $datas->where('wip_code', 'like', '%' . $wip_code . '%');
        }
        if ($description != null) {
            $datas = $datas->where('description', 'like', '%' . $description . '%');
        }
        if ($status != null) {
            $datas = $datas->where('status', $status);
        }
        if ($type != null) {
            $datas = $datas->where('type', $type);
        }
        if ($startdate != null && $enddate != null) {
            $datas = $datas->whereDate('created_at', '>=', $startdate)->whereDate('created_at', '<=', $enddate);
        }

        if ($request->flag != null) {
            $datas = $datas->get()->makeHidden(['id']);
            return $datas;
        }

        $datas = $datas->orderBy('created_at', 'desc')->get();

        // Datatables
        if ($request->ajax()) {

            $start = $request->get('start');
            $length = $request->get('length');
            $page = ($length > 0) ? intval($start / $length) + 1 : 1;

            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($process, $allprocess, $units, $allunits, $groups, $allgroups, $group_subs, $allgroup_subs, $departments, $alldepartments, $page) {
                    return view('wip.action', compact('data', 'process', 'allprocess', 'units', 'allunits', 'groups', 'allgroups', 'group_subs', 'allgroup_subs', 'departments', 'alldepartments', 'page'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Wips');

        return view('wip.index', compact(
            'datas',
            'process',
            'allprocess',
            'units',
            'allunits',
            'groups',
            'allgroups',
            'group_subs',
            'allgroup_subs',
            'departments',
            'alldepartments',
            'wip_code',
            'description',
            'status',
            'type',
            'searchDate',
            'startdate',
            'enddate',
            'flag'
        ));
    }

    public function createwip($flag)
    {
        // Initiate Variable
        $process = MstProcessProductions::where('status', 'Active')->get();
        $allprocess = MstProcessProductions::get();
        $units = MstUnits::where('is_active', 1)->get();
        $allunits = MstUnits::get();
        $groups = MstGroups::where('is_active', 1)->get();
        $allgroups = MstGroups::get();
        $group_subs = MstGroupSubs::where('is_active', 1)->get();
        $allgroup_subs = MstGroupSubs::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();
        $wipmaterials = MstWips::where('status', 'Active')->get();
        $units = MstUnits::get();
        $rawmaterials = MstRawMaterials::where('status', 'Active')->get();

        $unitcode = ['CM', 'INCH', 'MM', 'M'];
        $widthunits = MstUnits::whereIn('unit_code', $unitcode)->get();
        $lengthunits = $widthunits;
        $perforasis = MstDropdowns::where('category', 'Perforasi')->get();

        $prodCodes = MstDropdowns::where('category', 'Type Product Code')->get();
        $subCodes = MstDropdowns::where('category', 'Group Sub Code')->get();

        // dd($flag);

        //Audit Log
        $this->auditLogsShort('View Create Form New Mst Wips');

        return view('wip.create', compact(
            'process',
            'allprocess',
            'units',
            'allunits',
            'groups',
            'allgroups',
            'group_subs',
            'allgroup_subs',
            'departments',
            'alldepartments',
            'wipmaterials',
            'units',
            'flag',
            'widthunits',
            'lengthunits',
            'perforasis',
            'rawmaterials',
            'prodCodes',
            'subCodes'
        ));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'wip_type' => 'required',
            'description' => 'required',
            'type' => 'required',
            'type_product_code' => 'required',
            'id_master_process_productions' => 'required',
            'id_master_units' => 'required',
            'id_master_groups' => 'required',
            'id_master_group_subs' => 'required',
            'group_sub_code' => 'required',
            'status' => 'required',
            'thickness' => 'required',
            'width' => 'required',
            'width_unit' => 'required',
            'length' => 'required',
            'length_unit' => 'required',
            'weight' => 'required'
        ]);

        if ($request->wip_type == 'wip') {
            $wip_type = 'WIP';
        } else {
            $wip_type = 'WIP Blow';
        }

        $thickness = str_replace(['.', ','], ['', '.'], $request->thickness);
        $width = str_replace(['.', ','], ['', '.'], $request->width);
        $length = str_replace(['.', ','], ['', '.'], $request->length);
        $weight = str_replace(['.', ','], ['', '.'], $request->weight);

        DB::beginTransaction();
        try {
            $data = MstWips::create([
                'wip_type' => $wip_type,
                'wip_code' => $request->wip_code,
                'description' => $request->description,
                'id_master_process_productions' => $request->id_master_process_productions,
                'qty' => '0',
                'id_master_units' => $request->id_master_units,
                'id_master_groups' => $request->id_master_groups,
                'id_master_group_subs' => $request->id_master_group_subs,
                'status' => $request->status,
                'type' => $request->type,
                'type_product_code' => $request->type_product_code,
                'group_sub_code' => $request->group_sub_code,
                'thickness' => $thickness,
                'width' => $width,
                'width_unit' => $request->width_unit,
                'length' => $length,
                'length_unit' => $request->length_unit,
                'perforasi' => $request->perforasi,
                'weight' => $weight,
            ]);

            $dataInput = json_decode($request->input('dataInput'), true);
            if (is_array($dataInput) && !empty($dataInput)) {
                array_shift($dataInput);

                if ($request->wip_type == 'wip') {
                    foreach ($dataInput as $item) {
                        MstWipRefWips::create([
                            'id_master_wips' => $data->id,
                            'id_master_wips_material' => $item['wips']['value'],
                            'qty' => $item['qty'],
                            'master_units_id' => $item['unit']['value'],
                            'qty_results' => $item['qty_result'],
                        ]);
                    }
                } else {
                    foreach ($dataInput as $item) {
                        MstWipRefs::create([
                            'id_master_wips' => $data->id,
                            'id_master_raw_materials' => $item['raws']['value'],
                            'weight' => $item['weights'],
                        ]);
                    }
                }
            }

            //Audit Log
            $this->auditLogsShort('Create New Mst Wips');

            DB::commit();

            return redirect()->route('wip.index')->with(['success' => 'Success Create New Wip']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Wip!']);
        }
    }

    public function storeold(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'wip_code' => 'required',
            'description' => 'required',
            'id_master_process_productions' => 'required',
            'type' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = MstWips::create([
                'wip_type' => $request->wip_type,
                'wip_code' => $request->wip_code,
                'description' => $request->description,
                'id_master_process_productions' => $request->id_master_process_productions,
                'qty' => $request->qty,
                'id_master_units' => $request->id_master_units,
                'id_master_groups' => $request->id_master_groups,
                'id_master_group_subs' => $request->id_master_group_subs,
                'id_master_departements' => $request->id_master_departements,
                'status' => $request->status,
                'type' => $request->type,
                'width' => $request->width,
                'width_unit' => $request->width_unit,
                'length' => $request->length,
                'length_unit' => $request->length_unit,
                'thickness' => $request->thickness,
                'perforasi' => $request->perforasi,
                'weight' => $request->weight,
                'stock' => $request->stock
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Mst Wips');

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Wip']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Wip!']);
        }
    }

    public function edit(Request $request, $id, $page)
    {
        $id = decrypt($id);

        // Initiate Variable
        $process = MstProcessProductions::where('status', 'Active')->get();
        $allprocess = MstProcessProductions::get();
        $units = MstUnits::where('is_active', 1)->get();
        $allunits = MstUnits::get();
        $groups = MstGroups::where('is_active', 1)->get();
        $allgroups = MstGroups::get();
        $group_subs = MstGroupSubs::where('is_active', 1)->get();
        $allgroup_subs = MstGroupSubs::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();
        $wipmaterials = MstWips::where('status', 'Active')->get();
        $units = MstUnits::get();
        $rawmaterials = MstRawMaterials::where('status', 'Active')->get();

        $data = MstWips::where('id', $id)->first();

        $unitcode = ['CM', 'INCH', 'MM', 'M'];
        $widthunits = MstUnits::whereIn('unit_code', $unitcode)->get();
        $lengthunits = $widthunits;
        $perforasis = MstDropdowns::where('category', 'Perforasi')->get();

        $prodCodes = MstDropdowns::where('category', 'Type Product Code')->get();
        $subCodes = MstDropdowns::where('category', 'Group Sub Code')->get();

        // dd($data);

        //Audit Log
        $this->auditLogsShort('View Create Form New Mst Wips');

        return view('wip.edit', compact(
            'data',
            'page',
            'process',
            'allprocess',
            'units',
            'allunits',
            'groups',
            'allgroups',
            'group_subs',
            'allgroup_subs',
            'departments',
            'alldepartments',
            'wipmaterials',
            'units',
            'widthunits',
            'lengthunits',
            'perforasis',
            'rawmaterials',
            'page',
            'prodCodes',
            'subCodes'
        ));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'description' => 'required',
            'type' => 'required',
            'type_product_code' => 'required',
            'id_master_process_productions' => 'required',
            'id_master_units' => 'required',
            'id_master_groups' => 'required',
            'id_master_group_subs' => 'required',
            'group_sub_code' => 'required',
            'status' => 'required',
            'thickness' => 'required',
            'width' => 'required',
            'width_unit' => 'required',
            'length' => 'required',
            'length_unit' => 'required',
            'weight' => 'required'
        ]);

        $thickness = str_replace(['.', ','], ['', '.'], $request->thickness);
        $width = str_replace(['.', ','], ['', '.'], $request->width);
        $length = str_replace(['.', ','], ['', '.'], $request->length);
        $weight = str_replace(['.', ','], ['', '.'], $request->weight);

        $id = decrypt($id);
        $page = $request->input('page');

        $databefore = MstWips::where('id', $id)->first();
        $databefore->wip_code = $request->wip_code;
        $databefore->description = $request->description;
        $databefore->id_master_process_productions = $request->id_master_process_productions;
        $databefore->id_master_units = $request->id_master_units;
        $databefore->id_master_groups = $request->id_master_groups;
        $databefore->id_master_group_subs = $request->id_master_group_subs;
        $databefore->status = $request->status;
        $databefore->type = $request->type;
        $databefore->type_product_code = $request->type_product_code;
        $databefore->group_sub_code = $request->group_sub_code;
        $databefore->thickness = $thickness;
        $databefore->width = $width;
        $databefore->width_unit = $request->width_unit;
        $databefore->length = $length;
        $databefore->length_unit = $request->length_unit;
        $databefore->perforasi = $request->perforasi;
        $databefore->weight = $weight;

        if ($databefore->isDirty()) {
            DB::beginTransaction();
            try {
                $data = MstWips::where('id', $id)->update([
                    'wip_code' => $request->wip_code,
                    'description' => $request->description,
                    'id_master_process_productions' => $request->id_master_process_productions,
                    'id_master_units' => $request->id_master_units,
                    'id_master_groups' => $request->id_master_groups,
                    'id_master_group_subs' => $request->id_master_group_subs,
                    'status' => $request->status,
                    'type' => $request->type,
                    'type_product_code' => $request->type_product_code,
                    'group_sub_code' => $request->group_sub_code,
                    'thickness' => $thickness,
                    'width' => $width,
                    'width_unit' => $request->width_unit,
                    'length' => $length,
                    'length_unit' => $request->length_unit,
                    'perforasi' => $request->perforasi,
                    'weight' => $weight,
                ]);

                //Audit Log
                $this->auditLogsShort('Update Mst Wips');

                DB::commit();
                return redirect()->route('wip.index')->with('page', $page)->with(['success' => 'Success Update Wip']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with('page', $page)->with(['fail' => 'Failed to Update Wip!']);
            }
        } else {
            return redirect()->back()->with('page', $page)->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try {
            $data = MstWips::where('id', $id)->update([
                'status' => 'Active'
            ]);

            //Audit Log
            $this->auditLogsShort('Activate Wips');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Wip']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Wip']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try {
            $data = MstWips::where('id', $id)->update([
                'status' => 'Non Active'
            ]);

            $name = MstWips::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Deactivate Wips');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Wip']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Wip']);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try {
            $description = MstWips::where('id', $id)->first()->description;
            MstWips::where('id', $id)->delete();
            MstWipRefs::where('id_master_wips', $id)->delete();
            MstWipRefWips::where('id_master_wips', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Customer : '  . $description);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $description]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $description . '!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try {
            $description = MstWips::whereIn('id', $idselected)->pluck('description')->toArray();
            $delete = MstWips::whereIn('id', $idselected)->delete();
            MstWipRefs::whereIn('id_master_wips', $idselected)->delete();
            MstWipRefWips::whereIn('id_master_wips', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Customer Selected : ' . implode(', ', $description));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $description), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
