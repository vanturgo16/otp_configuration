<?php

namespace App\Http\Controllers;

use App\Models\MstCurrencies;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstFGs;
use App\Models\MstUnits;
use App\Models\MstGroups;
use App\Models\MstGroupSubs;
use App\Models\MstDepartments;

class MstFGsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
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

        $datas = $datas->paginate(10);

        $currencies = MstCurrencies::where('is_active', 1)->get();
        $allcurrencies = MstCurrencies::get();

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

        return view('fg.index',compact('datas', 'currencies', 'allcurrencies', 'units', 'allunits', 'groups',
            'allgroups', 'group_subs', 'allgroup_subs', 'departments', 'alldepartments',
            'product_code', 'description', 'status', 'type_product', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'product_code' => 'required',
            'description' => 'required',
        ]);

        DB::beginTransaction();
        try{
            $data = MstFGs::create([
                'product_code' => $request->wip_code,
                'description' => $request->description,
                'type_product' => $request->type_product,
                'id_master_units' => $request->id_master_units,
                'id_master_groups' => $request->id_master_groups,
                'id_master_group_subs' => $request->id_master_group_subs,
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
                'height' => $request->height,
                'height_unit' => $request->height_unit,
                'thickness' => $request->thickness,
                'perforasi' => $request->perforasi,
                'weight' => $request->weight,
                'stock' => $request->stock
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Product FG';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Product FG']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Product FG!']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'wip_code' => 'required',
            'description' => 'required',
            'id_master_process_productions' => 'required',
            'type' => 'required',
        ]);

        $databefore = MstFGs::where('id', $id)->first();
        $databefore->wip_code = $request->wip_code;
        $databefore->description = $request->description;
        $databefore->type_product = $request->type_product;
        $databefore->id_master_units = $request->id_master_units;
        $databefore->id_master_groups = $request->id_master_groups;
        $databefore->id_master_group_subs = $request->id_master_group_subs;
        $databefore->id_master_departements = $request->id_master_departements;
        $databefore->status = $request->status;
        $databefore->sales_price = $request->sales_price;
        $databefore->sales_price_currency = $request->sales_price_currency;
        $databefore->based_price = $request->based_price;
        $databefore->based_price_currency = $request->based_price_currency;
        $databefore->remarks = $request->remarks;
        $databefore->type = $request->type;
        $databefore->width = $request->width;
        $databefore->width_unit = $request->width_unit;
        $databefore->height = $request->height;
        $databefore->height_unit = $request->height_unit;
        $databefore->thickness = $request->thickness;
        $databefore->perforasi = $request->perforasi;
        $databefore->weight = $request->weight;
        $databefore->stock = $request->stock;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $data = MstFGs::where('id', $id)->update([
                    'product_code' => $request->wip_code,
                    'description' => $request->description,
                    'type_product' => $request->type_product,
                    'id_master_units' => $request->id_master_units,
                    'id_master_groups' => $request->id_master_groups,
                    'id_master_group_subs' => $request->id_master_group_subs,
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
                    'height' => $request->height,
                    'height_unit' => $request->height_unit,
                    'thickness' => $request->thickness,
                    'perforasi' => $request->perforasi,
                    'weight' => $request->weight,
                    'stock' => $request->stock
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Product FG';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Product FG']);
            } catch (\Exception $e) {
                dd($e);
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
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Product FG';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Product FG']);
        } catch (\Exception $e) {
            dd($e);
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
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Product FG';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Product FG']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Product FG']);
        }
    }
}
