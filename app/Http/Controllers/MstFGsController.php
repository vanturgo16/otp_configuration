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
        $currencies = MstCurrencies::where('is_active', 1)->get();
        $allcurrencies = MstCurrencies::get();

        $unitcode = ['CM', 'INCH', 'MM', 'M'];
        $widthunits = MstUnits::whereIn('unit_code', $unitcode)->get();
        $lengthunits = $widthunits;
        // $widthunits = MstDropdowns::where('category', 'Width Unit')->get();
        // $lengthunits = MstDropdowns::where('category', 'Length Unit')->get();
        $perforasis = MstDropdowns::where('category', 'Perforasi')->get();

        $prodCodes = MstDropdowns::where('category', 'Product Code')->get();
        
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
                'master_group_subs.name as groupsub', 'master_departements.name as department'
            )
            ->leftjoin('master_units', 'master_product_fgs.id_master_units', 'master_units.id')
            ->leftjoin('master_groups', 'master_product_fgs.id_master_groups', 'master_groups.id')
            ->leftjoin('master_group_subs', 'master_product_fgs.id_master_group_subs', 'master_group_subs.id')
            ->leftjoin('master_departements', 'master_product_fgs.id_master_departements', 'master_departements.id');

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
            'product_code', 'description', 'status', 'type_product', 'searchDate', 'startdate', 'enddate', 'flag','prodCodes'));
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
        ]);

        $sales_price = str_replace(',', '', $request->sales_price);
        $based_price = str_replace(',', '', $request->based_price);

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
                'sales_price' => $sales_price,
                'sales_price_currency' => $request->sales_price_currency,
                'based_price' => $based_price,
                'based_price_currency' => $request->based_price_currency,
                'remarks' => $request->remarks,
                'type' => $request->type,
                'width' => $request->width,
                'width_unit' => $request->width_unit,
                'height' => $request->length,
                'height_unit' => $request->length_unit,
                'thickness' => $request->thickness,
                'perforasi' => $request->perforasi,
                'weight' => $request->weight,
                // 'stock' => $request->stock
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
    
    public function edit($id)
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
        $prodCodes = MstDropdowns::where('category', 'Product Code')->get();
        
        //Audit Log
        $this->auditLogsShort('View Edit Product FG ('. $data->id . ')');

        return view('fg.edit',compact('data', 'currencies', 'allcurrencies', 'units', 'allunits', 'groups',
            'allgroups', 'group_subs', 'allgroup_subs', 'departments', 'alldepartments', 'widthunits', 'lengthunits', 'perforasis','prodCodes'));
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());

        $id = decrypt($id);

        $sales_price = str_replace(',', '', $request->sales_price);
        $based_price = str_replace(',', '', $request->based_price);

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
        $databefore->sales_price = $sales_price;
        $databefore->sales_price_currency = $request->sales_price_currency;
        $databefore->based_price = $based_price;
        $databefore->based_price_currency = $request->based_price_currency;
        $databefore->remarks = $request->remarks;
        $databefore->type = $request->type;
        $databefore->width = $request->width;
        $databefore->width_unit = $request->width_unit;
        $databefore->height = $request->length;
        $databefore->height_unit = $request->length_unit;
        $databefore->thickness = $request->thickness;
        $databefore->perforasi = $request->perforasi;
        $databefore->weight = $request->weight;

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
                    'sales_price' => $request->sales_price,
                    'sales_price_currency' => $request->sales_price_currency,
                    'based_price' => $request->based_price,
                    'based_price_currency' => $request->based_price_currency,
                    'remarks' => $request->remarks,
                    'type' => $request->type,
                    'width' => $request->width,
                    'width_unit' => $request->width_unit,
                    'height' => $request->length,
                    'height_unit' => $request->length_unit,
                    'thickness' => $request->thickness,
                    'perforasi' => $request->perforasi,
                    'weight' => $request->weight,
                ]);

                //Audit Log
                $this->auditLogsShort('Update Product FG');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Product FG']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update Product FG!']);
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
            $data = MstFGs::where('id', $id)->update([
                'status' => 'Active'
            ]);

            //Audit Log
            $this->auditLogsShort('Activate Product FG');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Product FG']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Product FG']);
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
            return redirect()->back()->with(['success' => 'Success Deactivate Product FG']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Product FG']);
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
