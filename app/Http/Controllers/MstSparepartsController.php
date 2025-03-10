<?php

namespace App\Http\Controllers;

use App\Models\MstDepartments;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstSpareparts;
use App\Models\MstUnits;

class MstSparepartsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Initiate Variable
        $units = MstUnits::where('is_active', 1)->get();
        $allunits = MstUnits::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();
        
        // Search Variable
        $code = $request->get('code');
        $description = $request->get('description');
        $status_stock = $request->get('status_stock');
        $type = $request->get('type');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $idUpdated = $request->get('idUpdated');

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

        $datas = $datas->orderBy('created_at', 'desc')->get();
        
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
                ->addColumn('action', function ($data) use ($units, $allunits, $departments, $alldepartments){
                    return view('sparepart.action', compact('data', 'units', 'allunits', 'departments', 'alldepartments'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Sparepart');

        return view('sparepart.index',compact('datas', 'units', 'allunits', 'departments', 'alldepartments',
            'code', 'description', 'status_stock', 'type', 'searchDate', 'startdate', 'enddate', 'flag', 'idUpdated', 'page_number'));
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
                // 'stock' => $request->stock,
                'type' => $request->type,
                'id_master_units' => $request->id_master_units,
                'id_master_departements' => $request->id_master_departements,
                'status_stock' => $status_stock
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Sparepart/Aux');

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Sparepart/Aux']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Sparepart/Aux!']);
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
        // $databefore->stock = $request->stock;
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
                    // 'stock' => $request->stock,
                    'type' => $request->type,
                    'id_master_units' => $request->id_master_units,
                    'id_master_departements' => $request->id_master_departements,
                    'status_stock' => $status_stock
                ]);

                //Audit Log
                $this->auditLogsShort('Update Sparepart/Aux');

                DB::commit();
                return redirect()->route('sparepart.index', ['idUpdated' => $id])->with(['success' => 'Success Update Sparepart/Aux']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route('sparepart.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Update Sparepart/Aux!']);
            }
        } else {
            return redirect()->route('sparepart.index', ['idUpdated' => $id])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
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
            $this->auditLogsShort('Activate Sparepart/Aux');

            DB::commit();
            return redirect()->route('sparepart.index', ['idUpdated' => $id])->with(['success' => 'Success In Stock Sparepart/Aux']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('sparepart.index', ['idUpdated' => $id])->with(['fail' => 'Failed to In Stock Sparepart/Aux']);
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
            $this->auditLogsShort('Deactivate Sparepart/Aux');

            DB::commit();
            return redirect()->route('sparepart.index', ['idUpdated' => $id])->with(['success' => 'Success Out Stock Sparepart/Aux']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('sparepart.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Out Stock Sparepart/Aux']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $code = MstSpareparts::where('id', $id)->first()->code;
            MstSpareparts::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Sparepart/Aux : '  . $code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $code = MstSpareparts::whereIn('id', $idselected)->pluck('code')->toArray();
            $delete = MstSpareparts::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Sparepart/Aux Selected : ' . implode(', ', $code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}