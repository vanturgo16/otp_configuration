<?php

namespace App\Http\Controllers;

use App\Models\MstBagians;
use App\Models\MstCurrencies;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstCustomers;
use App\Models\MstSalesmans;
use App\Models\MstTermPayments;

class MstCustomersController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $customer_code = $request->get('customer_code');
        $name = $request->get('name');
        $remark = $request->get('remark');
        $tax_number = $request->get('tax_number');
        $tax_code = $request->get('tax_code');
        $id_master_salesmen = $request->get('id_master_salesmen');
        $id_master_currencies = $request->get('id_master_currencies');
        $id_master_term_payments = $request->get('id_master_term_payments');
        $ppn = $request->get('ppn');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstCustomers::select(DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'), 'master_customers.*', 'master_salesmen.name as salesmanname',
                'master_currencies.currency', 'master_term_payments.term_payment',)
            ->leftjoin('master_salesmen', 'master_customers.id_master_salesmen', '=', 'master_salesmen.id')
            ->leftjoin('master_currencies', 'master_customers.id_master_currencies', '=', 'master_currencies.id')
            ->leftjoin('master_term_payments', 'master_customers.id_master_term_payments', '=', 'master_term_payments.id');

        if($customer_code != null){
            $datas = $datas->where('master_customers.customer_code', 'like', '%'.$customer_code.'%');
        }
        if($name != null){
            $datas = $datas->where('master_customers.name', 'like', '%'.$name.'%');
        }
        if($remark != null){
            $datas = $datas->where('master_customers.remark', 'like', '%'.$remark.'%');
        }
        if($tax_number != null){
            $datas = $datas->where('master_customers.tax_number', 'like', '%'.$tax_number.'%');
        }
        if($tax_code != null){
            $datas = $datas->where('master_customers.tax_code', 'like', '%'.$tax_code.'%');
        }
        if($id_master_salesmen != null){
            $datas = $datas->where('master_customers.id_master_salesmen', 'like', '%'.$id_master_salesmen.'%');
        }
        if($id_master_currencies != null){
            $datas = $datas->where('master_customers.id_master_currencies', 'like', '%'.$id_master_currencies.'%');
        }
        if($id_master_term_payments != null){
            $datas = $datas->where('master_customers.id_master_term_payments', 'like', '%'.$id_master_term_payments.'%');
        }
        if($ppn != null){
            $datas = $datas->where('master_customers.name', 'like', '%'.$ppn.'%');
        }
        if($status != null){
            $datas = $datas->where('master_customers.status', $status);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('master_customers.created_at','>=',$startdate)->whereDate('master_customers.created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden([
                'id', 'id_master_salesmen', 'id_master_currencies', 'id_master_term_payments'
            ]);
            return $datas;
        }

        $datas = $datas->paginate(10);
            
        $salesmans = MstSalesmans::where('is_active', 1)->get();
        $allsalesmans = MstSalesmans::get();
        $currencies = MstCurrencies::where('is_active', 1)->get();
        $allcurrencies = MstCurrencies::get();
        $terms = MstTermPayments::where('is_active', 1)->get();
        $allterms = MstTermPayments::get();
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Customer';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('customer.index',compact('datas', 'salesmans', 'allsalesmans', 'currencies', 'allcurrencies', 'terms', 'allterms',
            'customer_code', 'name', 'remark', 'tax_number', 'tax_code', 'id_master_salesmen', 'id_master_currencies', 'id_master_term_payments',
            'ppn', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function generateFormattedId($id) {
        $formattedId = str_pad($id, 6, '0', STR_PAD_LEFT);
        return $formattedId;
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'status' => 'required',
            'name' => 'required',
            'id_master_salesmen' => 'required',
            'id_master_term_payments' => 'required',
            'ppn' => 'required',
        ]);
        if($request->cbc == null){
            $cbc= 'N';
        } else{
            $cbc= 'Y';
        }

        DB::beginTransaction();
        try{
            $data = MstCustomers::create([
                'customer_code' => 'xxxxxx',
                'name' => $request->name,
                'status' => $request->status,
                'remark' => $request->remark,
                'tax_number' => $request->tax_number,
                'tax_code' => $request->tax_code,
                'id_master_salesmen' => $request->id_master_salesmen,
                'id_master_currencies' => $request->id_master_currencies,
                'id_master_term_payments' => $request->id_master_term_payments,
                'ppn' => $request->ppn,
                'cbc' => $cbc
            ]);
            
            $filteredString = preg_replace("/[^a-zA-Z]/", "", $request->name);
            $code = strtoupper(substr($filteredString, 0, 3));
            $idcode = $this->generateFormattedId($data->id);
            $customer_code = $code.$idcode;

            MstCustomers::where('id', $data->id)->update([
                'customer_code' => $customer_code
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Customer ('. $request->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Customer']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Customer!']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'status' => 'required',
            'name' => 'required',
            'id_master_salesmen' => 'required',
            'id_master_term_payments' => 'required',
            'ppn' => 'required',
        ]);
        if($request->cbc == null){
            $cbc= 'N';
        } else{
            $cbc= 'Y';
        }

        $databefore = MstCustomers::where('id', $id)->first();
        $databefore->status = $request->status;
        $databefore->name = $request->name;
        $databefore->remark = $request->remark;
        $databefore->tax_number = $request->tax_number;
        $databefore->tax_code = $request->tax_code;
        $databefore->id_master_salesmen = $request->id_master_salesmen;
        $databefore->id_master_currencies = $request->id_master_currencies;
        $databefore->id_master_term_payments = $request->id_master_term_payments;
        $databefore->ppn = $request->ppn;
        $databefore->cbc = $cbc;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $filteredString = preg_replace("/[^a-zA-Z]/", "", $request->name);
                $code = strtoupper(substr($filteredString, 0, 3));
                $idcode = $this->generateFormattedId($id);
                $customer_code = $code.$idcode;

                $data = MstCustomers::where('id', $id)->update([
                    'customer_code' => $customer_code,
                    'name' => $request->name,
                    'status' => $request->status,
                    'remark' => $request->remark,
                    'tax_number' => $request->tax_number,
                    'tax_code' => $request->tax_code,
                    'id_master_salesmen' => $request->id_master_salesmen,
                    'id_master_currencies' => $request->id_master_currencies,
                    'id_master_term_payments' => $request->id_master_term_payments,
                    'ppn' => $request->ppn,
                    'cbc' => $cbc
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Customer ('. $request->name . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Customer']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Customer!']);
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
            $data = MstCustomers::where('id', $id)->update([
                'status' => 'Active'
            ]);

            $name = MstCustomers::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Customer ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Customer ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Customer ' . $name->name .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstCustomers::where('id', $id)->update([
                'status' => 'Not Active'
            ]);

            $name = MstCustomers::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Customer ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Customer ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Customer ' . $name->name .'!']);
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
}
