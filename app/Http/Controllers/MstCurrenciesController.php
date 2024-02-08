<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstCurrencies;

class MstCurrenciesController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $currency_code = $request->get('currency_code');
        $currency = $request->get('currency');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

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

        $datas = $datas->paginate(10);
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Currency';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('currency.index',compact('datas',
            'currency_code', 'currency', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
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
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Currency ('. $request->currency . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Currency']);
            } catch (\Exception $e) {
                dd($e);
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
                return redirect()->back()->with('warning','Currency Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstCurrencies::where('id', $id)->update([
                        'currency_code' => $request->code,
                        'currency' => $request->currency,
                        'idr_rate' => $request->idr_rate,
                    ]);

                    //Audit Log
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Currency ('. $request->currency . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Currency']);
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['fail' => 'Failed to Update Currency!']);
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
            $data = MstCurrencies::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstCurrencies::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Currency ('. $name->currency . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Currency ' . $name->currency]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Currency ' . $name->currency .'!']);
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
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Currency ('. $name->currency . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Currency ' . $name->currency]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Currency ' . $name->currency .'!']);
        }
    }
}
