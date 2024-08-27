<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstCountries;

class MstCountriesController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $country_code = $request->get('country_code');
        $country = $request->get('country');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstCountries::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_countries.*'
        );

        if($country_code != null){
            $datas = $datas->where('country_code', 'like', '%'.$country_code.'%');
        }
        if($country != null){
            $datas = $datas->where('country', 'like', '%'.$country.'%');
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
                    return view('country.action', compact('data'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Country');

        return view('country.index',compact('datas',
            'country_code', 'country', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'code' => 'required',
            'country' => 'required',
        ]);

        $count= MstCountries::where('country',$request->country)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Country Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstCountries::create([
                    'country_code' => $request->code,
                    'country' => $request->country,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Country ('. $request->country . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Country']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Country!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'code' => 'required',
            'country' => 'required',
        ]);

        $databefore = MstCountries::where('id', $id)->first();
        $databefore->country_code = $request->code;
        $databefore->country = $request->country;

        if($databefore->isDirty()){
            $count= MstCountries::where('country',$request->country)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Country Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstCountries::where('id', $id)->update([
                        'country_code' => $request->code,
                        'country' => $request->country
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Country ('. $request->country . ')');

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Country']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with(['fail' => 'Failed to Update Country!']);
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
            $data = MstCountries::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstCountries::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Country ('. $name->country . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Country ' . $name->country]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Country ' . $name->country .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstCountries::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstCountries::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Country ('. $name->country . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Country ' . $name->country]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Country ' . $name->country .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $country_code = MstCountries::where('id', $id)->first()->country_code;
            MstCountries::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Country : '  . $country_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $country_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $country_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $country_code = MstCountries::whereIn('id', $idselected)->pluck('country_code')->toArray();;
            $delete = MstCountries::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Country Selected : ' . implode(', ', $country_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $country_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}