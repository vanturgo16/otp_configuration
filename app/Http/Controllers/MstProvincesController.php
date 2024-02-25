<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstProvinces;

class MstProvincesController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $province_code = $request->get('province_code');
        $province = $request->get('province');
        // $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstProvinces::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_provinces.*'
        );

        if($province_code != null){
            $datas = $datas->where('province_code', 'like', '%'.$province_code.'%');
        }
        if($province != null){
            $datas = $datas->where('province', 'like', '%'.$province.'%');
        }
        // if($status != null){
        //     $datas = $datas->where('is_active', $status);
        // }
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
                ->addColumn('action', function ($data){
                    return view('province.action', compact('data'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Province');

        return view('province.index',compact('datas',
            'province_code', 'province', 'searchDate', 'startdate', 'enddate', 'flag'));
    }
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'code' => 'required',
            'province' => 'required',
        ]);

        $count= MstProvinces::where('province',$request->province)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Province Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstProvinces::create([
                    'province_code' => $request->code,
                    'province' => $request->province,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Province ('. $request->province . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Province']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Province!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'code' => 'required',
            'province' => 'required',
        ]);

        $databefore = MstProvinces::where('id', $id)->first();
        $databefore->province_code = $request->code;
        $databefore->province = $request->province;

        if($databefore->isDirty()){
            $count= MstProvinces::where('province',$request->province)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Province Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstProvinces::where('id', $id)->update([
                        'province_code' => $request->code,
                        'province' => $request->province
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Province ('. $request->province . ')');

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Province']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with(['fail' => 'Failed to Update Province!']);
                }
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstProvinces::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstProvinces::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Province ('. $name->province . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Province ' . $name->province]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Province ' . $name->province .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstProvinces::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstProvinces::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Province ('. $name->province . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Province ' . $name->province]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Province ' . $name->province .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $province_code = MstProvinces::where('id', $id)->first()->province_code;
            MstProvinces::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Province : '  . $province_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $province_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $province_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $province_code = MstProvinces::whereIn('id', $idselected)->pluck('province_code')->toArray();;
            $delete = MstProvinces::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Province Selected : ' . implode(', ', $province_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $province_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
