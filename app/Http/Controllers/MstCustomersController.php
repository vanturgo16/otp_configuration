<?php

namespace App\Http\Controllers;

use App\Models\MstBagians;
use App\Models\MstCountries;
use App\Models\MstCurrencies;
use App\Models\MstCustomerAddress;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

// Model
use App\Models\MstCustomers;
use App\Models\MstProvinces;
use App\Models\MstSalesmans;
use App\Models\MstTermPayments;

class MstCustomersController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Initiate Variable
        $salesmans = MstSalesmans::where('is_active', 1)->get();
        $allsalesmans = MstSalesmans::get();
        $currencies = MstCurrencies::where('is_active', 1)->get();
        $allcurrencies = MstCurrencies::get();
        $terms = MstTermPayments::where('is_active', 1)->get();
        $allterms = MstTermPayments::get();

        $idUpdated = $request->get('idUpdated');

        // Search Variable
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

        // $datas = MstCustomers::select(DB::raw('ROW_NUMBER() OVER (ORDER BY master_customers.id) as no'), 'master_customers.*', 'master_salesmen.name as salesmanname',
        //         'master_currencies.currency', 'master_term_payments.term_payment',
        //         DB::raw('(SELECT address FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as address'),
        //         DB::raw('(SELECT postal_code FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as postal_code'),
        //         DB::raw('(SELECT city FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as city'),
        //         DB::raw('(SELECT telephone FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as telephone'),
        //         DB::raw('(SELECT mobile_phone FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as mobile_phone'),
        //         DB::raw('(SELECT fax FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as fax'),
        //         DB::raw('(SELECT email FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as email'),
        //         DB::raw('(SELECT type_address FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as type_address'),
        //         DB::raw('(SELECT contact_person FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as contact_person'),
        //         )
        //     ->leftjoin('master_salesmen', 'master_customers.id_master_salesmen', '=', 'master_salesmen.id')
        //     ->leftjoin('master_currencies', 'master_customers.id_master_currencies', '=', 'master_currencies.id')
        //     ->leftjoin('master_term_payments', 'master_customers.id_master_term_payments', '=', 'master_term_payments.id');

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
                ->addColumn('action', function ($data) use ($salesmans, $allsalesmans, $currencies, $allcurrencies, $terms, $allterms){
                    return view('customer.action', compact('data', 'salesmans', 'allsalesmans', 'currencies', 'allcurrencies', 'terms', 'allterms'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Customer');

        return view('customer.index',compact('datas', 'salesmans', 'allsalesmans', 'currencies', 'allcurrencies', 'terms', 'allterms',
            'customer_code', 'name', 'remark', 'tax_number', 'tax_code', 'id_master_salesmen', 'id_master_currencies', 'id_master_term_payments',
            'ppn', 'status', 'searchDate', 'startdate', 'enddate', 'flag', 'idUpdated', 'page_number'));
    }

    public function info($id)
    {
        $id = decrypt($id);

        $data = MstCustomers::select('master_customers.*', 'master_salesmen.name as salesmanname',
                'master_currencies.currency', 'master_term_payments.term_payment',
                DB::raw('(SELECT address FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as address'),
                DB::raw('(SELECT postal_code FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as postal_code'),
                DB::raw('(SELECT city FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as city'),
                DB::raw('(SELECT telephone FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as telephone'),
                DB::raw('(SELECT mobile_phone FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as mobile_phone'),
                DB::raw('(SELECT fax FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as fax'),
                DB::raw('(SELECT email FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as email'),
                DB::raw('(SELECT type_address FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as type_address'),
                DB::raw('(SELECT contact_person FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as contact_person'),
                )
            ->leftjoin('master_salesmen', 'master_customers.id_master_salesmen', '=', 'master_salesmen.id')
            ->leftjoin('master_currencies', 'master_customers.id_master_currencies', '=', 'master_currencies.id')
            ->leftjoin('master_term_payments', 'master_customers.id_master_term_payments', '=', 'master_term_payments.id')
            ->where('master_customers.id', $id)
            ->first();
        
        //Audit Log
        $this->auditLogsShort('View Info Customer, Code ('. $data->customer_code . ')');

        return view('customer.info',compact('data'));
    }

    public function create(Request $request)
    {
        // Initiate Variable
        $salesmans = MstSalesmans::where('is_active', 1)->get();
        $allsalesmans = MstSalesmans::get();
        $currencies = MstCurrencies::where('is_active', 1)->get();
        $allcurrencies = MstCurrencies::get();
        $terms = MstTermPayments::where('is_active', 1)->get();
        $allterms = MstTermPayments::get();

        $provinces = MstProvinces::get();
        $allprovinces = MstProvinces::get();
        $countries = MstCountries::where('is_active', 1)->get();
        $allcountries = MstCountries::get();
        
        //Audit Log
        $this->auditLogsShort('Open Create Form Master Customer');

        return view('customer.create',compact('salesmans', 'allsalesmans', 'currencies', 'allcurrencies', 'terms', 'allterms', 'provinces', 'allprovinces', 'countries', 'allcountries'));
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

            $addressData = json_decode($request->input('address_data'), true);
            if (is_array($addressData) && !empty($addressData)) {
                array_shift($addressData);

                foreach($addressData as $item){
                    MstCustomerAddress::create([
                        'id_master_customers' => $data->id,
                        'address' => $item['address'],
                        'postal_code' => $item['postal_code'],
                        'city' => $item['city'],
                        'id_master_provinces' => $item['province']['value'],
                        'id_master_countries' => $item['country']['value'],
                        'telephone' => $item['telephone'],
                        'mobile_phone' => $item['mobile_phone'],
                        'fax' => $item['fax'],
                        'email' => $item['email'],
                        'type_address' => $item['type_address']['value'],
                        'remarks' => $item['remarks'],
                        'contact_person' => $item['contact_person'],
                        'status' => 'Active'
                    ]);
                }
            }

            //Audit Log
            $this->auditLogsShort('Create New Customer ('. $request->name . ')');

            DB::commit();
            return redirect()->route('customer.index')->with(['success' => 'Success Create New Customer']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Customer!']);
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);

        // Initiate Variable
        $salesmans = MstSalesmans::where('is_active', 1)->get();
        $allsalesmans = MstSalesmans::get();
        $currencies = MstCurrencies::where('is_active', 1)->get();
        $allcurrencies = MstCurrencies::get();
        $terms = MstTermPayments::where('is_active', 1)->get();
        $allterms = MstTermPayments::get();

        $data = MstCustomers::select('master_customers.*', 'master_salesmen.name as salesmanname',
                'master_currencies.currency', 'master_term_payments.term_payment',
                DB::raw('(SELECT address FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as address'),
                DB::raw('(SELECT postal_code FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as postal_code'),
                DB::raw('(SELECT city FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as city'),
                DB::raw('(SELECT telephone FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as telephone'),
                DB::raw('(SELECT mobile_phone FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as mobile_phone'),
                DB::raw('(SELECT fax FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as fax'),
                DB::raw('(SELECT email FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as email'),
                DB::raw('(SELECT type_address FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as type_address'),
                DB::raw('(SELECT contact_person FROM master_customer_addresses WHERE id_master_customers = master_customers.id ORDER BY id DESC LIMIT 1) as contact_person'),
                )
            ->leftjoin('master_salesmen', 'master_customers.id_master_salesmen', '=', 'master_salesmen.id')
            ->leftjoin('master_currencies', 'master_customers.id_master_currencies', '=', 'master_currencies.id')
            ->leftjoin('master_term_payments', 'master_customers.id_master_term_payments', '=', 'master_term_payments.id')
            ->where('master_customers.id', $id)
            ->first();

        //Audit Log
        $this->auditLogsShort('View Edit Customer Form, Code ('. $data->customer_code . ')');

        return view('customer.edit',compact('data', 'salesmans', 'allsalesmans', 'currencies', 'allcurrencies', 'terms', 'allterms'));
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
                $this->auditLogsShort('Update Customer ('. $request->name . ')');

                DB::commit();
                return redirect()->route('customer.index', ['idUpdated' => $id])->with(['success' => 'Success Update Customer']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update Customer!']);
            }
        } else {
            return redirect()->route('customer.index', ['idUpdated' => $id])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
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
            $this->auditLogsShort('Activate Customer ('. $name->name . ')');

            DB::commit();
            return redirect()->route('customer.index', ['idUpdated' => $id])->with(['success' => 'Success Activate Customer ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()-route('customer.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Activate Customer ' . $name->name .'!']);
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
            $this->auditLogsShort('Deactivate Customer ('. $name->name . ')');

            DB::commit();
            return redirect()->route('customer.index', ['idUpdated' => $id])->with(['success' => 'Success Deactivate Customer ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('customer.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Deactivate Customer ' . $name->name .'!']);
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
            $customer_code = MstCustomers::where('id', $id)->first()->customer_code;
            MstCustomers::where('id', $id)->delete();
            MstCustomerAddress::where('id_master_customers', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Customer : '  . $customer_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $customer_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $customer_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $customer_code = MstCustomers::whereIn('id', $idselected)->pluck('customer_code')->toArray();
            $delete = MstCustomers::whereIn('id', $idselected)->delete();
            MstCustomerAddress::whereIn('id_master_customers', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Customer Selected : ' . implode(', ', $customer_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $customer_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
