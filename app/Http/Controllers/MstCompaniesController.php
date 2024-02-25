<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\User;
use App\Models\MstCompanies;
use App\Models\MstProvinces;
use App\Models\MstCountries;
use App\Models\MstCurrencies;

class MstCompaniesController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Initiate Variable
        $provinces = MstProvinces::get();
        $countries = MstCountries::get();
        $currencies = MstCurrencies::get();

        // Search Variable
        $company_name = $request->get('company_name');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstCompanies::select(DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'), 'master_companies.*', 'master_provinces.province', 'master_countries.country', 'master_currencies.currency')
            ->leftjoin('master_provinces', 'master_companies.id_master_provinces', '=', 'master_provinces.id')
            ->leftjoin('master_countries', 'master_companies.id_master_countries', '=', 'master_countries.id')
            ->leftjoin('master_currencies', 'master_companies.id_master_currencies', '=', 'master_currencies.id');

        if($company_name != null){
            $datas = $datas->where('master_companies.company_name', 'like', '%'.$company_name.'%');
        }
        if($status != null){
            $datas = $datas->where('master_companies.is_active', $status);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('master_companies.created_at','>=',$startdate)->whereDate('master_companies.created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id']);
            return $datas;
        }

        $datas = $datas->get();
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($provinces, $countries, $currencies){
                    return view('company.action', compact('data', 'provinces', 'countries', 'currencies'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Company');
        
        return view('company.index', compact('datas', 'provinces', 'countries', 'currencies',
            'company_name', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'company_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'id_master_provinces' => 'required',
            'id_master_countries' => 'required',
            'postal_code' => 'required',
            'telephone' => 'required',
            'mobile_phone' => 'required',
            'fax' => 'required',
            'email' => 'required',
            'website' => 'required',
            'penandatanganan' => 'required',
            'id_master_currencies' => 'required',
            'tax_no' => 'required',
        ]);

        $count= MstCompanies::where('company_name',$request->company_name)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Company Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $data = MstCompanies::create([
                    'company_name' => $request->company_name,
                    'address' => $request->address,
                    'city' => $request->city,
                    'id_master_provinces' => $request->id_master_provinces,
                    'id_master_countries' => $request->id_master_countries,
                    'postal_code' => $request->postal_code,
                    'telephone' => $request->telephone,
                    'mobile_phone' => $request->mobile_phone,
                    'fax' => $request->fax,
                    'email' => $request->email,
                    'website' => $request->website,
                    'penandatanganan' => $request->penandatanganan,
                    'id_master_currencies' => $request->id_master_currencies,
                    'tax_no' => $request->tax_no,
                    'is_active' => '1'
                ]);

                //Audit Log
                $this->auditLogsShort('Create New Company ('. $request->company_name . ')');

                DB::commit();

                return redirect()->back()->with(['success' => 'Success Create New Company']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New Company!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'company_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'id_master_provinces' => 'required',
            'id_master_countries' => 'required',
            'postal_code' => 'required',
            'telephone' => 'required',
            'mobile_phone' => 'required',
            'fax' => 'required',
            'email' => 'required',
            'website' => 'required',
            'penandatanganan' => 'required',
            'id_master_currencies' => 'required',
            'tax_no' => 'required',
        ]);

        $databefore = MstCompanies::where('id', $id)->first();
        $databefore->company_name = $request->company_name;
        $databefore->address = $request->address;
        $databefore->city = $request->city;
        $databefore->id_master_provinces = $request->id_master_provinces;
        $databefore->id_master_countries = $request->id_master_countries;
        $databefore->postal_code = $request->postal_code;
        $databefore->telephone = $request->telephone;
        $databefore->mobile_phone = $request->mobile_phone;
        $databefore->fax = $request->fax;
        $databefore->email = $request->email;
        $databefore->website = $request->website;
        $databefore->penandatanganan = $request->penandatanganan;
        $databefore->id_master_currencies = $request->id_master_currencies;
        $databefore->tax_no = $request->tax_no;

        if($databefore->isDirty()){
            $count= MstCompanies::where('company_name',$request->company_name)->whereNotIn('id', [$id])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Company Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $data = MstCompanies::where('id', $id)->update([
                        'company_name' => $request->company_name,
                        'address' => $request->address,
                        'city' => $request->city,
                        'id_master_provinces' => $request->id_master_provinces,
                        'id_master_countries' => $request->id_master_countries,
                        'postal_code' => $request->postal_code,
                        'telephone' => $request->telephone,
                        'mobile_phone' => $request->mobile_phone,
                        'fax' => $request->fax,
                        'email' => $request->email,
                        'website' => $request->website,
                        'penandatanganan' => $request->penandatanganan,
                        'id_master_currencies' => $request->id_master_currencies,
                        'tax_no' => $request->tax_no
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Update Company ('. $request->company_name . ')');

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update Company']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with(['fail' => 'Failed to Update Company!']);
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
            $data = MstCompanies::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = MstCompanies::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate Company ('. $name->company_name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Company ' . $name->company_name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Activate Company ' . $name->company_name .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstCompanies::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = MstCompanies::where('id', $id)->first();
            
            //Audit Log
            $this->auditLogsShort('Deactivate Company ('. $name->company_name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Company ' . $name->company_name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Company ' . $name->company_name .'!']);
        }
    }

    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $company_name = MstCompanies::where('id', $id)->first()->company_name;
            MstCompanies::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Company : '  . $company_name);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $company_name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $company_name .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $company_name = MstCompanies::whereIn('id', $idselected)->pluck('company_name')->toArray();;
            $delete = MstCompanies::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Company Selected : ' . implode(', ', $company_name));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $company_name), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
