<?php

namespace App\Http\Controllers;

use App\Models\MstBagians;
use App\Models\MstCountries;
use App\Models\MstCurrencies;
use App\Models\MstProvinces;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstSuppliers;
use App\Models\MstTermPayments;

class MstSuppliersController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Initiate Variable 
        $allprovinces = MstProvinces::get();
        $countries = MstCountries::where('is_active', 1)->get();
        $allcountries = MstCountries::get();
        $currencies = MstCurrencies::where('is_active', 1)->get();
        $allcurrencies = MstCurrencies::get();
        $terms = MstTermPayments::where('is_active', 1)->get();
        $allterms = MstTermPayments::get();
        
        // Search Variable
        $supplier_code = $request->get('supplier_code');
        $name = $request->get('name');
        $name_invoice = $request->get('name_invoice');
        $address = $request->get('address');
        $postal_code = $request->get('postal_code');
        $city = $request->get('city');
        $email = $request->get('email');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $idUpdated = $request->get('idUpdated');

        $datas = MstSuppliers::select(DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'), 'master_suppliers.*', 'master_provinces.province',
                'master_countries.country', 'master_currencies.currency', 'master_term_payments.term_payment')
            ->leftjoin('master_provinces', 'master_suppliers.id_master_provinces', '=', 'master_provinces.id')
            ->leftjoin('master_countries', 'master_suppliers.id_master_countries', '=', 'master_countries.id')
            ->leftjoin('master_currencies', 'master_suppliers.id_master_currencies', '=', 'master_currencies.id')
            ->leftjoin('master_term_payments', 'master_suppliers.id_master_term_payments', '=', 'master_term_payments.id');

        if($supplier_code != null){
            $datas = $datas->where('master_suppliers.supplier_code', 'like', '%'.$supplier_code.'%');
        }
        if($name != null){
            $datas = $datas->where('master_suppliers.name', 'like', '%'.$name.'%');
        }
        if($name_invoice != null){
            $datas = $datas->where('master_suppliers.name_invoice', 'like', '%'.$name_invoice.'%');
        }
        if($address != null){
            $datas = $datas->where('master_suppliers.address', 'like', '%'.$address.'%');
        }
        if($postal_code != null){
            $datas = $datas->where('master_suppliers.postal_code', 'like', '%'.$postal_code.'%');
        }
        if($city != null){
            $datas = $datas->where('master_suppliers.city', 'like', '%'.$city.'%');
        }
        if($email != null){
            $datas = $datas->where('master_suppliers.email', 'like', '%'.$email.'%');
        }
        if($status != null){
            $datas = $datas->where('master_suppliers.status', $status);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('master_suppliers.created_at','>=',$startdate)->whereDate('master_suppliers.created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden([
                'id', 'id_master_provinces', 'id_master_countries', 'id_master_currencies', 'id_master_term_payments'
            ]);
            return $datas;
        }

        $datas = $datas->orderBy('created_at', 'desc');
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($allprovinces, $countries, $allcountries, $currencies, $allcurrencies, $terms, $allterms){
                    return view('supplier.action', compact('data', 'allprovinces', 'countries', 'allcountries', 'currencies', 'allcurrencies', 'terms', 'allterms'));
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
        $this->auditLogsShort('View List Mst Supplier');

        return view('supplier.index',compact('datas', 'allprovinces', 'countries',
            'allcountries', 'currencies', 'allcurrencies', 'terms', 'allterms',
            'supplier_code', 'name', 'name_invoice', 'address', 'postal_code', 'city', 'email',
            'status', 'searchDate', 'startdate', 'enddate', 'flag', 'idUpdated', 'page_number'));
    }

    public function info($id)
    {
        $id = decrypt($id);

        $data = MstSuppliers::select('master_suppliers.*', 'master_provinces.province',
                'master_countries.country', 'master_currencies.currency', 'master_term_payments.term_payment')
            ->leftjoin('master_provinces', 'master_suppliers.id_master_provinces', '=', 'master_provinces.id')
            ->leftjoin('master_countries', 'master_suppliers.id_master_countries', '=', 'master_countries.id')
            ->leftjoin('master_currencies', 'master_suppliers.id_master_currencies', '=', 'master_currencies.id')
            ->leftjoin('master_term_payments', 'master_suppliers.id_master_term_payments', '=', 'master_term_payments.id')
            ->where('master_suppliers.id', $id)
            ->first();
        
        //Audit Log
        $this->auditLogsShort('View Info Supplier, Code ('. $data->supplier_code . ')');

        return view('supplier.info',compact('data'));
    }

    public function generateFormattedId($id) 
    {
        $formattedId = str_pad($id, 6, '0', STR_PAD_LEFT);
        return $formattedId;
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'status' => 'required',
            'name' => 'required',
            'name_invoice' => 'required',
            'address' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
            'id_master_provinces' => 'required',
            'id_master_countries' => 'required',
            'bank_name' => 'required',
            'bank_account_number' => 'required'
        ]);
        if($request->is_domestic == null){
            $is_domestic= 0;
        } else {
            $is_domestic= 1;
        }

        DB::beginTransaction();
        try{
            $data = MstSuppliers::create([
                'supplier_code' => 'xxxxxx',
                'name' => $request->name,
                'name_invoice' => $request->name_invoice,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'city' => $request->city,
                'id_master_provinces' => $request->id_master_provinces,
                'id_master_countries' => $request->id_master_countries,
                'telephone' => $request->telephone,
                'mobile_phone' => $request->mobile_phone,
                'fax' => $request->fax,
                'email' => $request->email,
                'status' => $request->status,
                'remarks' => $request->remarks,
                'tax_number' => $request->tax_number,
                'id_master_currencies' => $request->id_master_currencies,
                'id_master_term_payments' => $request->id_master_term_payments,
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'account_holder' => $request->account_holder,
                'is_domestic' => $is_domestic
            ]);
            
            $filteredString = preg_replace("/[^a-zA-Z]/", "", $request->name);
            $code = strtoupper(substr($filteredString, 0, 3));
            $idcode = $this->generateFormattedId($data->id);
            $supplier_code = $code.$idcode;

            MstSuppliers::where('id', $data->id)->update([
                'supplier_code' => $supplier_code
            ]);

            //Audit Log
            $this->auditLogsShort('Create New Supplier ('. $request->name . ')');

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Supplier']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Supplier!']);
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);

        // Initiate Variable
        $allprovinces = MstProvinces::get();
        $countries = MstCountries::where('is_active', 1)->get();
        $allcountries = MstCountries::get();
        $currencies = MstCurrencies::where('is_active', 1)->get();
        $allcurrencies = MstCurrencies::get();
        $terms = MstTermPayments::where('is_active', 1)->get();
        $allterms = MstTermPayments::get();

        $data = MstSuppliers::select('master_suppliers.*', 'master_provinces.province',
                'master_countries.country', 'master_currencies.currency', 'master_term_payments.term_payment')
            ->leftjoin('master_provinces', 'master_suppliers.id_master_provinces', '=', 'master_provinces.id')
            ->leftjoin('master_countries', 'master_suppliers.id_master_countries', '=', 'master_countries.id')
            ->leftjoin('master_currencies', 'master_suppliers.id_master_currencies', '=', 'master_currencies.id')
            ->leftjoin('master_term_payments', 'master_suppliers.id_master_term_payments', '=', 'master_term_payments.id')
            ->where('master_suppliers.id', $id)
            ->first();

        //Audit Log
        $this->auditLogsShort('View Edit Customer Form, Code ('. $data->customer_code . ')');

        return view('supplier.edit',compact('data', 'allprovinces', 'countries', 'allcountries', 'currencies', 'allcurrencies', 'terms', 'allterms'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'status' => 'required',
            'name' => 'required',
            'name_invoice' => 'required',
            'address' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
            'id_master_provinces' => 'required',
            'id_master_countries' => 'required',
            'bank_name' => 'required',
            'bank_account_number' => 'required'
        ]);
        if($request->is_domestic == null){
            $is_domestic= 0;
        } else {
            $is_domestic= 1;
        }

        $databefore = MstSuppliers::where('id', $id)->first();
        $databefore->name = $request->name;
        $databefore->name_invoice = $request->name_invoice;
        $databefore->address = $request->address;
        $databefore->postal_code = $request->postal_code;
        $databefore->city = $request->city;
        $databefore->id_master_provinces = $request->id_master_provinces;
        $databefore->id_master_countries = $request->id_master_countries;
        $databefore->telephone = $request->telephone;
        $databefore->mobile_phone = $request->mobile_phone;
        $databefore->fax = $request->fax;
        $databefore->email = $request->email;
        $databefore->status = $request->status;
        $databefore->remarks = $request->remarks;
        $databefore->tax_number = $request->tax_number;
        $databefore->id_master_currencies = $request->id_master_currencies;
        $databefore->id_master_term_payments = $request->id_master_term_payments;
        $databefore->bank_name = $request->bank_name;
        $databefore->bank_account_number = $request->bank_account_number;
        $databefore->account_holder = $request->account_holder;
        $databefore->is_domestic = $is_domestic;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                $filteredString = preg_replace("/[^a-zA-Z]/", "", $request->name);
                $code = strtoupper(substr($filteredString, 0, 3));
                $idcode = $this->generateFormattedId($id);
                $supplier_code = $code.$idcode;

                $data = MstSuppliers::where('id', $id)->update([
                    'supplier_code' => $supplier_code,
                    'name' => $request->name,
                    'name_invoice' => $request->name_invoice,
                    'address' => $request->address,
                    'postal_code' => $request->postal_code,
                    'city' => $request->city,
                    'id_master_provinces' => $request->id_master_provinces,
                    'id_master_countries' => $request->id_master_countries,
                    'telephone' => $request->telephone,
                    'mobile_phone' => $request->mobile_phone,
                    'fax' => $request->fax,
                    'email' => $request->email,
                    'status' => $request->status,
                    'remarks' => $request->remarks,
                    'tax_number' => $request->tax_number,
                    'id_master_currencies' => $request->id_master_currencies,
                    'id_master_term_payments' => $request->id_master_term_payments,
                    'bank_name' => $request->bank_name,
                    'bank_account_number' => $request->bank_account_number,
                    'account_holder' => $request->account_holder,
                    'is_domestic' => $is_domestic
                ]);

                //Audit Log
                $this->auditLogsShort('Update Supplier ('. $request->name . ')');

                DB::commit();
                return redirect()->route('supplier.index', ['idUpdated' => $id])->with(['success' => 'Success Update Supplier']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route('supplier.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Update Supplier!']);
            }
        } else {
            return redirect()->route('supplier.index', ['idUpdated' => $id])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstSuppliers::where('id', $id)->update([
                'status' => 'Active'
            ]);

            $name = MstSuppliers::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Supplier ('. $name->name . ')');

            DB::commit();
            return redirect()->route('supplier.index', ['idUpdated' => $id])->with(['success' => 'Success Activate Supplier ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('supplier.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Activate Supplier ' . $name->name .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstSuppliers::where('id', $id)->update([
                'status' => '0'
            ]);

            $name = MstSuppliers::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Supplier ('. $name->name . ')');

            DB::commit();
            return redirect()->route('supplier.index', ['idUpdated' => $id])->with(['success' => 'Success Deactivate Supplier ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('supplier.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Deactivate Supplier ' . $name->name .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $supplier_code = MstSuppliers::where('id', $id)->first()->supplier_code;
            MstSuppliers::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Supplier : '  . $supplier_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $supplier_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $supplier_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $supplier_code = MstSuppliers::whereIn('id', $idselected)->pluck('supplier_code')->toArray();;
            $delete = MstSuppliers::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Supplier Selected : ' . implode(', ', $supplier_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $supplier_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
