<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstCurrencies;

class MstCurrenciesController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Search Variable
        $currency_code = $request->get('currency_code');
        $currency = $request->get('currency');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $idUpdated = $request->get('idUpdated');

        $datas = MstCurrencies::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_currencies.*'
        );

        if($currency_code != null){
            $datas = $datas->where('currency_code', 'like', '%'.$currency_code.'%');
        }
        if($currency != null){
            $datas = $datas->where('term_payment', 'like', '%'.$currency.'%');
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
                    return view('currency.action', compact('data'));
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
        $this->auditLogsShort('View List Mst Currency');

        return view('currency.index',compact('datas',
            'currency_code', 'currency', 'status', 'searchDate', 'startdate', 'enddate', 'flag', 'idUpdated', 'page_number'));
    }
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'code' => 'required',
            'currency' => 'required',
            'idr_rate' => 'required',
        ]);

        $count= MstCurrencies::where('currency',$request->currency)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Currency Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstCurrencies::create([
                    'currency_code' => $request->code,
                    'currency' => $request->currency,
                    'idr_rate' => $request->idr_rate,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Currency ('. $request->currency . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Currency']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Currency!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'code' => 'required',
            'currency' => 'required',
            'idr_rate' => 'required',
        ]);

        $databefore = MstCurrencies::where('id', $id)->first();
        $databefore->currency_code = $request->code;
        $databefore->currency = $request->currency;
        $databefore->idr_rate = $request->idr_rate;

        if($databefore->isDirty()){
            $count= MstCurrencies::where('currency',$request->currency)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->route('currency.index', ['idUpdated' => $id])->with('warning','Currency Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstCurrencies::where('id', $id)->update([
                        'currency_code' => $request->code,
                        'currency' => $request->currency,
                        'idr_rate' => $request->idr_rate,
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Currency ('. $request->currency . ')');

                    DB::commit();
                    return redirect()->route('currency.index', ['idUpdated' => $id])->with(['success' => 'Success Update Currency']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->route('currency.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Update Currency!']);
                }
            }
        } else {
            return redirect()->route('currency.index', ['idUpdated' => $id])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstCurrencies::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstCurrencies::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Currency ('. $name->currency . ')');

            DB::commit();
            return redirect()->route('currency.index', ['idUpdated' => $id])->with(['success' => 'Success Activate Currency ' . $name->currency]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('currency.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Activate Currency ' . $name->currency .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstCurrencies::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstCurrencies::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Currency ('. $name->currency . ')');

            DB::commit();
            return redirect()->route('currency.index', ['idUpdated' => $id])->with(['success' => 'Success Deactivate Currency ' . $name->currency]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('currency.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Deactivate Currency ' . $name->currency .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $currency_code = MstCurrencies::where('id', $id)->first()->currency_code;
            MstCurrencies::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Currency : '  . $currency_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $currency_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $currency_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $currency_code = MstCurrencies::whereIn('id', $idselected)->pluck('currency_code')->toArray();
            $delete = MstCurrencies::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Currency Selected : ' . implode(', ', $currency_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $currency_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
