<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstVehicles;

class MstVehiclesController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $vehicle_number = $request->get('vehicle_number');
        $driver = $request->get('driver');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $idUpdated = $request->get('idUpdated');

        $datas = MstVehicles::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_vehicles.*'
        );

        if($vehicle_number != null){
            $datas = $datas->where('vehicle_number', 'like', '%'.$vehicle_number.'%');
        }
        if($driver != null){
            $datas = $datas->where('driver', 'like', '%'.$driver.'%');
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
                    return view('vehicle.action', compact('data'));
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
        $this->auditLogsShort('View List Mst Vehicle');

        return view('vehicle.index',compact('datas',
            'vehicle_number', 'driver', 'status', 'searchDate', 'startdate', 'enddate', 'flag', 'idUpdated', 'page_number'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'vehicle_number' => 'required',
            'driver' => 'required',
        ]);

        $count= MstVehicles::where('vehicle_number',$request->vehicle_number)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Vehicle Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstVehicles::create([
                    'vehicle_number' => $request->vehicle_number,
                    'driver' => $request->driver,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Vehicle ('. $request->vehicle_number . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Vehicle']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Vehicle!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'vehicle_number' => 'required',
            'driver' => 'required',
        ]);

        $databefore = MstVehicles::where('id', $id)->first();
        $databefore->vehicle_number = $request->vehicle_number;
        $databefore->driver = $request->driver;

        if($databefore->isDirty()){
            $count= MstVehicles::where('vehicle_number',$request->vehicle_number)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->route('vehicle.index', ['idUpdated' => $id])->with('warning','Vehicle Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstVehicles::where('id', $id)->update([
                        'vehicle_number' => $request->vehicle_number,
                        'driver' => $request->driver,
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Vehicle ('. $request->vehicle_number . ')');

                    DB::commit();
                    return redirect()->route('vehicle.index', ['idUpdated' => $id])->with(['success' => 'Success Update Vehicle']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->route('vehicle.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Update Vehicle!']);
                }
            }
        } else {
            return redirect()->route('vehicle.index', ['idUpdated' => $id])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstVehicles::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstVehicles::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Vehicle ('. $name->vehicle_number . ')');

            DB::commit();
            return redirect()->route('vehicle.index', ['idUpdated' => $id])->with(['success' => 'Success Activate Vehicle ' . $name->vehicle_number]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('vehicle.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Activate Vehicle ' . $name->vehicle_number .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstVehicles::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstVehicles::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Vehicle ('. $name->vehicle_number . ')');

            DB::commit();
            return redirect()->route('vehicle.index', ['idUpdated' => $id])->with(['success' => 'Success Deactivate Vehicle ' . $name->vehicle_number]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('vehicle.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Deactivate Vehicle ' . $name->vehicle_number .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $vehicle_number = MstVehicles::where('id', $id)->first()->vehicle_number;
            MstVehicles::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Vehicle : '  . $vehicle_number);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $vehicle_number]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $vehicle_number .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $vehicle_number = MstVehicles::whereIn('id', $idselected)->pluck('vehicle_number')->toArray();
            $delete = MstVehicles::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Vehicle Selected : ' . implode(', ', $vehicle_number));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $vehicle_number), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
