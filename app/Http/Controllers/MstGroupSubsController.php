<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstGroupSubs;

class MstGroupSubsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $group_sub_code = $request->get('group_sub_code');
        $name = $request->get('name');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $idUpdated = $request->get('idUpdated');

        $datas = MstGroupSubs::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_group_subs.*'
        );

        if($group_sub_code != null){
            $datas = $datas->where('group_sub_code', 'like', '%'.$group_sub_code.'%');
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
                    return view('groupsub.action', compact('data'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        // Get Page Number
        $page_number = 1;
        if ($idUpdated) {
            $page_size = 5;
            $datas = $datas->get();
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
        
        //Audit Log
        $this->auditLogsShort('View List Mst Group Sub');

        return view('groupsub.index',compact('datas',
            'group_sub_code', 'name', 'status', 'searchDate', 'startdate', 'enddate', 'flag', 'idUpdated', 'page_number'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'group_sub_code' => 'required',
            'name' => 'required',
        ]);

        $count= MstGroupSubs::where('name',$request->name)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Group Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstGroupSubs::create([
                    'group_sub_code' => $request->group_sub_code,
                    'name' => $request->name,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Group Sub ('. $request->name . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Group Sub']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Group Sub!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'group_sub_code' => 'required',
            'name' => 'required',
        ]);

        $databefore = MstGroupSubs::where('id', $id)->first();
        $databefore->group_sub_code = $request->group_sub_code;
        $databefore->name = $request->name;

        if($databefore->isDirty()){
            $count= MstGroupSubs::where('name',$request->name)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->route('groupsub.index', ['idUpdated' => $id])->with('warning','Group Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstGroupSubs::where('id', $id)->update([
                        'group_sub_code' => $request->group_sub_code,
                        'name' => $request->name,
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Group Sub ('. $request->name . ')');

                    DB::commit();
                    return redirect()->route('groupsub.index', ['idUpdated' => $id])->with(['success' => 'Success Update Group Sub']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->route('groupsub.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Update Group Sub!']);
                }
            }
        } else {
            return redirect()->route('groupsub.index', ['idUpdated' => $id])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstGroupSubs::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstGroupSubs::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Group Sub ('. $name->name . ')');

            DB::commit();
            return redirect()->route('groupsub.index', ['idUpdated' => $id])->with(['success' => 'Success Activate Group Sub ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('groupsub.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Activate Group Sub ' . $name->name .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstGroupSubs::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstGroupSubs::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Group Sub ('. $name->name . ')');

            DB::commit();
            return redirect()->route('groupsub.index', ['idUpdated' => $id])->with(['success' => 'Success Deactivate Group Sub ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('groupsub.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Deactivate Group Sub ' . $name->name .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $group_sub_code = MstGroupSubs::where('id', $id)->first()->group_sub_code;
            MstGroupSubs::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Group Sub : '  . $group_sub_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $group_sub_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $group_sub_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $group_sub_code = MstGroupSubs::whereIn('id', $idselected)->pluck('group_sub_code')->toArray();
            $delete = MstGroupSubs::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Group Sub Selected : ' . implode(', ', $group_sub_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $group_sub_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
