<?php

namespace App\Http\Controllers;

use App\Models\MstCurrencies;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstFGs;
use App\Models\MstUnits;
use App\Models\MstGroups;
use App\Models\MstGroupSubs;
use App\Models\MstDepartments;
use App\Models\MstDropdowns;
use App\Models\MstFGRefs;

class MstFGsController extends Controller
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
        // $datas = MstFGs::select('master_product_fgs.id', 'master_product_fgs.thickness', 'master_product_fgs.width', 'master_product_fgs.height', 'master_product_fgs.weight',
        //             'master_group_subs.name as group_sub_name', 'w_unit.unit_code as width_unit',  'h_unit.unit_code as height_unit'
        //         )
        //     ->leftjoin('master_group_subs', 'master_product_fgs.id_master_group_subs', 'master_group_subs.id')
        //     ->leftjoin('master_units as w_unit', 'master_product_fgs.width_unit', 'w_unit.id')
        //     ->leftjoin('master_units as h_unit', 'master_product_fgs.height_unit', 'h_unit.id')
        //     ->whereBetween('master_product_fgs.id', [0, 0])
        //     ->get();
        // foreach($datas as $item){
        //     $thickness = (float) ($item->thickness) ?? 0;
        //     if($thickness){
        //         $thickness = $thickness/1000;
        //     }
        //     $width = ((float) ($item->width) ?? 0) * $this->getUnitToMeter($item->width_unit);
        //     $height = ((float) ($item->height) ?? 0) * $this->getUnitToMeter($item->height_unit);
        //     $factor = ($item->group_sub_name == 'Slitting') ? 1 : 2;
        //     $weight = $thickness*$width*$height*$factor*0.92;
        //     $weight = round($weight, 9);
        //     MstFGs::where('id', $item->id)->update(['weight' => $weight]);
        // }
        // dd($datas);

        // Initiate Variable
        $units = MstUnits::where('is_active', 1)->get();
        $allunits = MstUnits::get();
        $groups = MstGroups::where('is_active', 1)->get();
        $allgroups = MstGroups::get();
        $group_subs = MstGroupSubs::where('is_active', 1)->get();
        $allgroup_subs = MstGroupSubs::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();
        $currencies = MstCurrencies::where('is_active', 1)->get();
        $allcurrencies = MstCurrencies::get();

        $idUpdated = $request->get('idUpdated');

        $unitcode = ['CM', 'INCH', 'MM', 'M'];
        $widthunits = MstUnits::whereIn('unit_code', $unitcode)->get();
        $lengthunits = $widthunits;
        // $widthunits = MstDropdowns::where('category', 'Width Unit')->get();
        // $lengthunits = MstDropdowns::where('category', 'Length Unit')->get();
        $perforasis = MstDropdowns::where('category', 'Perforasi')->get();

        $prodCodes = MstDropdowns::where('category', 'Type Product Code')->get();
        $subCodes = MstDropdowns::where('category', 'Group Sub Code')->get();
        
        // Search Variable
        $product_code = $request->get('product_code');
        $description = $request->get('description');
        $status = $request->get('status');
        $type_product = $request->get('type_product');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstFGs::select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
                'master_product_fgs.*', 'master_units.unit', 'master_groups.name as groupname',
                'master_group_subs.name as groupsub', 'master_departements.name as department',
                'widthUnit.unit_code as width_unt', 'heightUnit.unit_code as height_unt',
                'sales_currency.currency_code as salesCurrency', 'based_currency.currency_code as basedCurrency'
            )
            ->leftjoin('master_units', 'master_product_fgs.id_master_units', 'master_units.id')
            ->leftjoin('master_units as widthUnit', 'master_product_fgs.width_unit', 'widthUnit.id')
            ->leftjoin('master_units as heightUnit', 'master_product_fgs.height_unit', 'heightUnit.id')
            ->leftjoin('master_groups', 'master_product_fgs.id_master_groups', 'master_groups.id')
            ->leftjoin('master_group_subs', 'master_product_fgs.id_master_group_subs', 'master_group_subs.id')
            ->leftjoin('master_departements', 'master_product_fgs.id_master_departements', 'master_departements.id')
            ->leftjoin('master_currencies as sales_currency', 'master_product_fgs.sales_price_currency', 'sales_currency.id')
            ->leftjoin('master_currencies as based_currency', 'master_product_fgs.based_price_currency', 'based_currency.id');

        if($product_code != null){
            $datas = $datas->where('product_code', 'like', '%'.$product_code.'%');
        }
        if($description != null){
            $datas = $datas->where('description', 'like', '%'.$description.'%');
        }
        if($status != null){
            $datas = $datas->where('status', $status);
        }
        if($type_product != null){
            $datas = $datas->where('type_product', $type_product);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('master_product_fgs.created_at','>=',$startdate)->whereDate('master_product_fgs.created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id']);
            return $datas;
        }

        $datas = $datas->orderBy('master_product_fgs.created_at', 'desc')->get();
        
        // Get Page Number
        $page_number = 1;
        if ($idUpdated) {
            $page_size = 5;
            $item = $datas->firstWhere('id', $idUpdated);
            if ($item) {
                $index = $datas->search(function ($value) use ($idUpdated) {
                    return $value->id == $idUpdated;
                });
                $page_number = (int) ceil(($index + 1) / $page_size);
            } else {
                $page_number = 1;
            }
        }
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($currencies, $allcurrencies, $units, $allunits, $groups, $allgroups, $group_subs, $allgroup_subs, $departments, $alldepartments){
                    return view('fg.action', compact('data', 'currencies', 'allcurrencies', 'units', 'allunits', 'groups', 'allgroups', 'group_subs', 'allgroup_subs', 'departments', 'alldepartments'));
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

        return view('fg.index',compact('datas', 'currencies', 'allcurrencies', 'units', 'allunits', 'groups',
            'allgroups', 'group_subs', 'allgroup_subs', 'departments', 'alldepartments', 'widthunits', 'lengthunits', 'perforasis',
            'product_code', 'description', 'status', 'type_product', 'searchDate', 'startdate', 'enddate', 'flag','prodCodes', 'subCodes', 'idUpdated', 'page_number'));
    }   

    public function generateFormattedId($type, $id) {
        if($type == 'PP'){
            $code = "FGSHRINK01-";
        } elseif ($type == 'POF') {
            $code = "FGSHRINK02-";
        } elseif ($type == 'CROSSLINK') {
            $code = "FGSHRINK03-";
        } elseif ($type == 'SOFTSHRINK') {
            $code = "FGSHRINK04-";
        } elseif ($type == 'HOT PERFORATION') {
            $code = "FGSHRINK05-";
        }
        $formattedId = str_pad($id, 5, '0', STR_PAD_LEFT);

        $product_code = $code.$formattedId;

        return $product_code;
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'description' => 'required',
            'type_product' => 'required',
            'type_product_code' => 'required',
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
            'weight' => 'required',
            'sales_price' => 'required',
            'based_price' => 'required',
            'sales_price_currency' => 'required',
            'based_price_currency' => 'required',
        ]);

        $thickness = str_replace(['.', ','], ['', '.'], $request->thickness);
        $width = str_replace(['.', ','], ['', '.'], $request->width);
        $length = str_replace(['.', ','], ['', '.'], $request->length);
        $weight = str_replace(['.', ','], ['', '.'], $request->weight);

        $sales_price = str_replace(['.', ','], ['', '.'], $request->sales_price);
        $based_price = str_replace(['.', ','], ['', '.'], $request->based_price);

        DB::beginTransaction();
        try{
            $data = MstFGs::create([
                'product_code' => "TEMPCODE",
                'description' => $request->description,
                'type_product' => $request->type_product,
                'type_product_code' => $request->type_product_code,
                'id_master_units' => $request->id_master_units,
                'id_master_groups' => $request->id_master_groups,
                'id_master_group_subs' => $request->id_master_group_subs,
                'group_sub_code' => $request->group_sub_code,
                'id_master_departements' => $request->id_master_departements,
                'status' => $request->status,
                'remarks' => $request->remarks,
                'type' => $request->type,
                'thickness' => $thickness,
                'width' => $width,
                'width_unit' => $request->width_unit,
                'height' => $length,
                'height_unit' => $request->length_unit,
                'perforasi' => $request->perforasi,
                'weight' => $weight,
                'sales_price' => $sales_price,
                'sales_price_currency' => $request->sales_price_currency,
                'based_price' => $based_price,
                'based_price_currency' => $request->based_price_currency,
            ]);

            $product_code = $this->generateFormattedId($request->type_product, $data->id);

            MstFGs::where('id', $data->id)->update([
                'product_code' => $product_code
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Product FG');

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Product FG']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Product FG!']);
        }
    }
    
    public function edit(Request $request, $id)
    {
        $id = decrypt($id);
        
        // Initiate Variable
        $units = MstUnits::where('is_active', 1)->get();
        $allunits = MstUnits::get();
        $groups = MstGroups::where('is_active', 1)->get();
        $allgroups = MstGroups::get();
        $group_subs = MstGroupSubs::where('is_active', 1)->get();
        $allgroup_subs = MstGroupSubs::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();
        $currencies = MstCurrencies::where('is_active', 1)->get();
        $allcurrencies = MstCurrencies::get();

        $data = MstFGs::where('id', $id)->first();

        $unitcode = ['CM', 'INCH', 'MM', 'M'];
        $widthunits = MstUnits::whereIn('unit_code', $unitcode)->get();
        $lengthunits = $widthunits;
        // $widthunits = MstDropdowns::where('category', 'Width Unit')->get();
        // $lengthunits = MstDropdowns::where('category', 'Length Unit')->get();
        $perforasis = MstDropdowns::where('category', 'Perforasi')->get();
        $prodCodes = MstDropdowns::where('category', 'Type Product Code')->get();
        $subCodes = MstDropdowns::where('category', 'Group Sub Code')->get();
        
        //Audit Log
        $this->auditLogsShort('View Edit Product FG ('. $data->id . ')');

        return view('fg.edit',compact('data', 'currencies', 'allcurrencies', 'units', 'allunits', 'groups',
            'allgroups', 'group_subs', 'allgroup_subs', 'departments', 'alldepartments', 'widthunits', 'lengthunits', 'perforasis','prodCodes', 'subCodes'));
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());

        $id = decrypt($id);

        $thickness = str_replace(['.', ','], ['', '.'], $request->thickness);
        $width = str_replace(['.', ','], ['', '.'], $request->width);
        $length = str_replace(['.', ','], ['', '.'], $request->length);
        $weight = str_replace(['.', ','], ['', '.'], $request->weight);

        $sales_price = str_replace(['.', ','], ['', '.'], $request->sales_price);
        $based_price = str_replace(['.', ','], ['', '.'], $request->based_price);

        $databefore = MstFGs::where('id', $id)->first();

        $databefore->product_code = $request->product_code;
        $databefore->description = $request->description;
        $databefore->type_product = $request->type_product;
        $databefore->type_product_code = $request->type_product_code;
        $databefore->id_master_units = $request->id_master_units;
        $databefore->id_master_groups = $request->id_master_groups;
        $databefore->id_master_group_subs = $request->id_master_group_subs;
        $databefore->group_sub_code = $request->group_sub_code;
        $databefore->id_master_departements = $request->id_master_departements;
        $databefore->status = $request->status;
        $databefore->remarks = $request->remarks;
        $databefore->type = $request->type;
        $databefore->thickness = $thickness;
        $databefore->width = $width;
        $databefore->width_unit = $request->width_unit;
        $databefore->height = $length;
        $databefore->height_unit = $request->length_unit;
        $databefore->perforasi = $request->perforasi;
        $databefore->weight = $weight;
        $databefore->sales_price = $sales_price;
        $databefore->sales_price_currency = $request->sales_price_currency;
        $databefore->based_price = $based_price;
        $databefore->based_price_currency = $request->based_price_currency;
        
        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $data = MstFGs::where('id', $id)->update([
                    'product_code' => $request->product_code,
                    'description' => $request->description,
                    'type_product' => $request->type_product,
                    'type_product_code' => $request->type_product_code,
                    'id_master_units' => $request->id_master_units,
                    'id_master_groups' => $request->id_master_groups,
                    'id_master_group_subs' => $request->id_master_group_subs,
                    'group_sub_code' => $request->group_sub_code,
                    'id_master_departements' => $request->id_master_departements,
                    'status' => $request->status,
                    'remarks' => $request->remarks,
                    'type' => $request->type,
                    'thickness' => $thickness,
                    'width' => $width,
                    'width_unit' => $request->width_unit,
                    'height' => $length,
                    'height_unit' => $request->length_unit,
                    'perforasi' => $request->perforasi,
                    'weight' => $weight,
                    'sales_price' => $sales_price,
                    'sales_price_currency' => $request->sales_price_currency,
                    'based_price' => $based_price,
                    'based_price_currency' => $request->based_price_currency,
                ]);

                //Audit Log
                $this->auditLogsShort('Update Product FG ID ('.$id.')');

                DB::commit();
                return redirect()->route('fg.index', ['idUpdated' => $id])->with('success', 'Success Update Product FG');
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route('fg.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Update Product FG!']);
            }
        } else {
            return redirect()->route('fg.index', ['idUpdated' => $id])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstFGs::where('id', $id)->update([
                'status' => 'Active'
            ]);

            //Audit Log
            $this->auditLogsShort('Activate Product FG');

            DB::commit();
            return redirect()->route('fg.index', ['idUpdated' => $id])->with(['success' => 'Success Activate Product FG']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('fg.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Activate Product FG']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstFGs::where('id', $id)->update([
                'status' => 'Non Active'
            ]);

            $name = MstFGs::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Product FG');

            DB::commit();
            return redirect()->route('fg.index', ['idUpdated' => $id])->with(['success' => 'Success Deactivate Product FG']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('fg.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Deactivate Product FG']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $product_code = MstFGs::where('id', $id)->first()->product_code;
            MstFGs::where('id', $id)->delete();
            MstFGRefs::where('id_master_product_fgs', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Product FG : '  . $product_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $product_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $product_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $product_code = MstFGs::whereIn('id', $idselected)->pluck('product_code')->toArray();
            $delete = MstFGs::whereIn('id', $idselected)->delete();
            MstFGRefs::whereIn('id_master_product_fgs', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Product FG Selected : ' . implode(', ', $product_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $product_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
