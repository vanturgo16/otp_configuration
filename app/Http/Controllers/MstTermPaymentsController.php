<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstTermPayments;

class MstTermPaymentsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $term_payment_code = $request->get('term_payment_code');
        $term_payment = $request->get('term_payment');
        $status = $request->get('status');
        $payment_period = $request->get('payment_period');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstTermPayments::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_term_payments.*'
        );

        if($term_payment_code != null){
            $datas = $datas->where('term_payment_code', 'like', '%'.$term_payment_code.'%');
        }
        if($term_payment != null){
            $datas = $datas->where('term_payment', 'like', '%'.$term_payment.'%');
        }
        if($status != null){
            $datas = $datas->where('is_active', $status);
        }
        if($payment_period != null){
            $datas = $datas->where('payment_period', $payment_period);
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
        $activity='View List Mst Term Payment';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('termpayment.index',compact('datas',
            'term_payment_code', 'term_payment', 'status', 'payment_period', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'term_payment_code' => 'required',
            'term_payment' => 'required',
            'payment_period' => 'required',
        ]);

        $count= MstTermPayments::where('term_payment',$request->term_payment)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Term Payment Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstTermPayments::create([
                    'term_payment_code' => $request->term_payment_code,
                    'term_payment' => $request->term_payment,
                    'payment_period' => $request->payment_period,
                    'is_active' => '1'
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Create New Term Payment ('. $request->term_payment . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Term Payment']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Create New Term Payment!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'term_payment_code' => 'required',
            'term_payment' => 'required',
            'payment_period' => 'required',
        ]);

        $databefore = MstTermPayments::where('id', $id)->first();
        $databefore->term_payment_code = $request->term_payment_code;
        $databefore->term_payment = $request->term_payment;
        $databefore->payment_period = $request->payment_period;

        if($databefore->isDirty()){
            $count= MstTermPayments::where('term_payment',$request->term_payment)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Term Payment Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstTermPayments::where('id', $id)->update([
                        'term_payment_code' => $request->term_payment_code,
                        'term_payment' => $request->term_payment,
                        'payment_period' => $request->payment_period,
                    ]);

                    //Audit Log
                    $username= auth()->user()->email; 
                    $ipAddress=$_SERVER['REMOTE_ADDR'];
                    $location='0';
                    $access_from=Browser::browserName();
                    $activity='Update Term Payment ('. $request->term_payment . ')';
                    $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Term Payment']);
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['fail' => 'Failed to Update Term Payment!']);
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
            $data = MstTermPayments::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstTermPayments::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Term Payment ('. $name->term_payment . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Term Payment ' . $name->term_payment]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Term Payment ' . $name->term_payment .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstTermPayments::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstTermPayments::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Term Payment ('. $name->term_payment . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Term Payment ' . $name->term_payment]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Term Payment ' . $name->term_payment .'!']);
        }
    }
}
