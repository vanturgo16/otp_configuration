<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstCustomerAddress;
use App\Models\MstCustomers;
use App\Models\MstProvinces;
use App\Models\MstCountries;

class MstCustomerAddressController extends Controller
{
    use AuditLogsTrait;

    public function index($id)
    {
        $id = decrypt($id);

        $datas = MstCustomerAddress::select('master_customer_addresses.*', 'master_provinces.province', 'master_countries.country')
            ->leftjoin('master_provinces', 'master_customer_addresses.id_master_provinces', 'master_provinces.id')
            ->leftjoin('master_countries', 'master_customer_addresses.id_master_countries', 'master_countries.id')
            ->where('id_master_customers', $id)
            ->get();
        $customer = MstCustomers::where('id', $id)->first();
        
        $provinces = MstProvinces::where('is_active', 1)->get();
        $allprovinces = MstProvinces::get();
        $countries = MstCountries::where('is_active', 1)->get();
        $allcountries = MstCountries::get();

        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Customer Address From '. $customer->name;
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('customeraddress.index',compact('datas', 'customer', 'provinces', 'allprovinces', 'countries', 'allcountries'));
    }

    public function store(Request $request, $id)
    {
        $id = decrypt($id);
        // dd($request->all(), $id);

        $request->validate([
            'address' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
            'id_master_provinces' => 'required',
            'id_master_countries' => 'required',
            'type_address' => 'required',
        ]);

        DB::beginTransaction();
        try{
            MstCustomerAddress::create([
                'id_master_customers' => $id,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'city' => $request->city,
                'id_master_provinces' => $request->id_master_provinces,
                'id_master_countries' => $request->id_master_countries,
                'telephone' => $request->telephone,
                'mobile_phone' => $request->mobile_phone,
                'fax' => $request->fax,
                'email' => $request->email,
                'type_address' => $request->type_address,
                'remarks' => $request->remarks,
                'contact_person' => $request->contact_person,
                'status' => 'Active'
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Address';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Address']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Address!']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'address' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
            'id_master_provinces' => 'required',
            'id_master_countries' => 'required',
            'type_address' => 'required',
        ]);

        $databefore = MstCustomerAddress::where('id', $id)->first();
        $databefore->address = $request->address;
        $databefore->postal_code = $request->postal_code;
        $databefore->city = $request->city;
        $databefore->id_master_provinces = $request->id_master_provinces;
        $databefore->id_master_countries = $request->id_master_countries;
        $databefore->telephone = $request->telephone;
        $databefore->mobile_phone = $request->mobile_phone;
        $databefore->fax = $request->fax;
        $databefore->email = $request->email;
        $databefore->remarks = $request->remarks;
        $databefore->contact_person = $request->contact_person;
        $databefore->type_address = $request->type_address;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstCustomerAddress::where('id', $id)->update([
                    'address' => $request->address,
                    'postal_code' => $request->postal_code,
                    'city' => $request->city,
                    'id_master_provinces' => $request->id_master_provinces,
                    'id_master_countries' => $request->id_master_countries,
                    'telephone' => $request->telephone,
                    'mobile_phone' => $request->mobile_phone,
                    'fax' => $request->fax,
                    'email' => $request->email,
                    'type_address' => $request->type_address,
                    'remarks' => $request->remarks,
                    'contact_person' => $request->contact_person,
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Address';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Address']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Address!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstCustomerAddress::where('id', $id)->update([
                'status' => 'Active'
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Address';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Address']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Address']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstCustomerAddress::where('id', $id)->update([
                'status' => 'Not Active'
            ]);
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Address';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Address']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Address']);
        }
    }
}
