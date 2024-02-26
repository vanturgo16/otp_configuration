<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstFGs;
use App\Models\MstFGRefs;
use App\Models\MstUnits;
use App\Models\MstWips;

class MstFGRefsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request, $id)
    {
        $id = decrypt($id);

        // Initiate Variable
        $fg = MstFGs::where('id', $id)->first();
        $listfg = MstFGs::select('id', 'description')->get();
        $listwip = MstWips::select('id', 'description')->get();
        $listunit = MstUnits::select('id', 'unit')->get();
        
        // Search Variable
        $type_ref = $request->get('type_ref');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstFGRefs::select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
                'master_product_fgs.description as master_product_fgs',
                'master_product_fg_refs.type_ref',
                'fg.product_code as fg_product_code', 'fg.description as fg_description',
                'wp.wip_code as wp_wip_code', 'wp.description as wp_description',
                'master_product_fg_refs.*',
                'master_units.unit'
            )
            ->leftjoin('master_product_fgs', 'master_product_fg_refs.id_master_product_fgs', 'master_product_fgs.id')
            ->leftjoin('master_product_fgs as fg', 'master_product_fg_refs.id_master_fgs', 'fg.id')
            ->leftjoin('master_wips as wp', 'master_product_fg_refs.id_master_wips', 'wp.id')
            ->leftjoin('master_units', 'master_product_fg_refs.master_units_id', 'master_units.id')
            ->where('master_product_fgs.id', $id);

        if($type_ref != null){
            $datas = $datas->where('master_product_fg_refs.type_ref', $type_ref);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('master_product_fg_refs.created_at','>=',$startdate)->whereDate('master_product_fg_refs.created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id', 'id_master_product_fgs', 'id_master_wips', 'id_master_fgs', 'master_units_id']);
            return $datas;
        }

        $datas = $datas->get();
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($fg, $id, $listfg, $listwip, $listunit){
                    return view('fgref.action', compact('data', 'fg', 'id', 'listfg', 'listwip', 'listunit'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst FG Refs From '. $fg->description);

        return view('fgref.index',compact('datas', 'fg', 'id', 'listfg', 'listwip', 'listunit',
            'type_ref', 'searchDate', 'startdate', 'enddate', 'flag'));
    }
    public function store(Request $request, $id)
    {
        // dd($request->all());
        $id = decrypt($id);

        $request->validate([
            'type_ref' => 'required',
            'qty' => 'required',
            'master_units_id' => 'required',
            'qty_results' => 'required',
        ]);

        DB::beginTransaction();
        try{
            $data = MstFGRefs::create([
                'id_master_product_fgs' => $id,
                'type_ref' => $request->type_ref,
                'id_master_wips' => $request->id_master_wips,
                'id_master_fgs' => $request->id_master_fgs,
                'qty' => $request->qty,
                'master_units_id' => $request->master_units_id,
                'qty_results' => $request->qty_results
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Master FG Ref');

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New FG Ref']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New FG Ref!']);
        }
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'type_ref' => 'required',
            'qty' => 'required',
            'master_units_id' => 'required',
            'qty_results' => 'required',
        ]);

        $databefore = MstFGRefs::where('id', $id)->first();
        $databefore->type_ref = $request->type_ref;
        $databefore->id_master_wips = $request->id_master_wips;
        $databefore->id_master_fgs = $request->id_master_fgs;
        $databefore->qty = $request->qty;
        $databefore->master_units_id = $request->master_units_id;
        $databefore->qty_results = $request->qty_results;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $data = MstFGRefs::where('id', $id)->update([
                    'type_ref' => $request->type_ref,
                    'id_master_wips' => $request->id_master_wips,
                    'id_master_fgs' => $request->id_master_fgs,
                    'qty' => $request->qty,
                    'master_units_id' => $request->master_units_id,
                    'qty_results' => $request->qty_results
                ]);

                //Audit Log
                $this->auditLogsShort('Update New Master FG Ref');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Master FG Ref']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update Master FG Ref!']);
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
            $fg_description = MstFGRefs::where('id', $id)->first()->fg_description;
            MstFGRefs::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data FG Ref : '  . $fg_description);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $fg_description]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $fg_description .'!']);
        }
    }

    public function deleteselected(Request $request, $id)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $fg_description = MstFGRefs::whereIn('id', $idselected)->pluck('fg_description')->toArray();
            $delete = MstFGRefs::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete FG Ref Selected : ' . implode(', ', $fg_description));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $fg_description), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
