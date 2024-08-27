<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstDepartments;
use App\Models\MstBagians;

class MstDepartmentsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $departement_code = $request->get('departement_code');
        $name = $request->get('name');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstDepartments::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_departements.*'
        );

        if($departement_code != null){
            $datas = $datas->where('departement_code', 'like', '%'.$departement_code.'%');
        }
        if($name != null){
            $datas = $datas->where('name', 'like', '%'.$name.'%');
        }
        if($status != null){
            $datas = $datas->where('is_active', $status);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('created_at','>=',$startdate)->whereDate('created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id']);
            return $datas;
        }

        $datas = $datas->orderBy('created_at', 'desc');
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data){
                    return view('department.action', compact('data'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Department');

        return view('department.index',compact('departement_code', 'name', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'departement_code' => 'required',
            'name' => 'required',
        ]);

        $count= MstDepartments::where('name',$request->name)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Department Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstDepartments::create([
                    'departement_code' => $request->departement_code,
                    'name' => $request->name,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Department ('. $request->name . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Department']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Department!']);
            }
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'departement_code' => 'required',
            'name' => 'required',
        ]);

        $databefore = MstDepartments::where('id', $id)->first();
        $databefore->departement_code = $request->departement_code;
        $databefore->name = $request->name;

        if($databefore->isDirty()){
            $count= MstDepartments::where('name',$request->name)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Department Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstDepartments::where('id', $id)->update([
                        'departement_code' => $request->departement_code,
                        'name' => $request->name,
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Department ('. $request->name . ')');

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Department']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with(['fail' => 'Failed to Update Department!']);
                }
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
            $data = MstDepartments::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstDepartments::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Department ('. $name->name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Department ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Department ' . $name->name .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstDepartments::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstDepartments::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Department ('. $name->name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Department ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Department ' . $name->name .'!']);
        }
    }

    public function mappingBagian($id)
    {
        $datas = MstBagians::select('id', 'name')
            ->where('id_master_departements', $id)
            ->where('status', 'Active')
            ->get();

        return json_encode($datas);
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $departement_code = MstDepartments::where('id', $id)->first()->departement_code;
            MstDepartments::where('id', $id)->delete();
            MstBagians::where('id_master_departements', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Department : '  . $departement_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $departement_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $departement_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $departement_code = MstDepartments::whereIn('id', $idselected)->pluck('departement_code')->toArray();;
            $delete = MstDepartments::whereIn('id', $idselected)->delete();
            MstBagians::whereIn('id_master_departements', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Department Selected : ' . implode(', ', $departement_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $departement_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
