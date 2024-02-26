<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstCustomerAddress;
use App\Models\MstCustomers;
use App\Models\MstProvinces;
use App\Models\MstCountries;

class MstCustomerAddressController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request, $id)
    {
        $id = decrypt($id);

        // Initiate Variable
        $customer = MstCustomers::where('id', $id)->first();
        $allprovinces = MstProvinces::get();
        $countries = MstCountries::where('is_active', 1)->get();
        $allcountries = MstCountries::get();

        // Search Variable
        $address = $request->get('address');
        $postal_code = $request->get('postal_code');
        $city = $request->get('city');
        $id_master_provinces = $request->get('id_master_provinces');
        $id_master_countries = $request->get('id_master_countries');
        $telephone = $request->get('telephone');
        $mobile_phone = $request->get('mobile_phone');
        $fax = $request->get('fax');
        $email = $request->get('email');
        $type_address = $request->get('type_address');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstCustomerAddress::select(DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'), 'master_customers.name as customer_name', 'master_customer_addresses.address',
                'master_provinces.province', 'master_countries.country', 'master_customer_addresses.*')
            ->leftjoin('master_customers', 'master_customer_addresses.id_master_customers', 'master_customers.id')
            ->leftjoin('master_provinces', 'master_customer_addresses.id_master_provinces', 'master_provinces.id')
            ->leftjoin('master_countries', 'master_customer_addresses.id_master_countries', 'master_countries.id')
            ->where('id_master_customers', $id);

        if($address != null){
            $datas = $datas->where('master_customer_addresses.address', 'like', '%'.$address.'%');
        }
        if($postal_code != null){
            $datas = $datas->where('master_customer_addresses.postal_code', 'like', '%'.$postal_code.'%');
        }
        if($city != null){
            $datas = $datas->where('master_customer_addresses.city', 'like', '%'.$city.'%');
        }
        if($id_master_provinces != null){
            $datas = $datas->where('master_customer_addresses.id_master_provinces', 'like', '%'.$id_master_provinces.'%');
        }
        if($id_master_countries != null){
            $datas = $datas->where('master_customer_addresses.id_master_countries', 'like', '%'.$id_master_countries.'%');
        }
        if($telephone != null){
            $datas = $datas->where('master_customer_addresses.telephone', 'like', '%'.$telephone.'%');
        }
        if($mobile_phone != null){
            $datas = $datas->where('master_customer_addresses.mobile_phone', 'like', '%'.$mobile_phone.'%');
        }
        if($fax != null){
            $datas = $datas->where('master_customer_addresses.fax', 'like', '%'.$fax.'%');
        }
        if($email != null){
            $datas = $datas->where('master_customer_addresses.email', 'like', '%'.$email.'%');
        }
        if($type_address != null){
            $datas = $datas->where('master_customer_addresses.type_address', 'like', '%'.$type_address.'%');
        }
        if($status != null){
            $datas = $datas->where('master_customer_addresses.status', $status);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('master_customer_addresses.created_at','>=',$startdate)->whereDate('master_customer_addresses.created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id', 'id_master_customers', 'id_master_provinces', 'id_master_countries']);
            return $datas;
        }

        $datas = $datas->get();
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($customer, $allprovinces, $countries, $allcountries){
                    return view('customeraddress.action', compact('data', 'customer', 'allprovinces', 'countries', 'allcountries'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Customer Address From ('. $customer->name. ')');

        return view('customeraddress.index',compact('id', 'datas', 'customer', 'allprovinces', 'countries', 'allcountries',
            'address', 'postal_code', 'city', 'id_master_provinces', 'id_master_countries', 'telephone', 'mobile_phone', 'fax', 'email', 'type_address',
            'status', 'searchDate', 'startdate', 'enddate', 'flag'));
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

    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $address = MstCustomerAddress::where('id', $id)->first()->address;
            MstCustomerAddress::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Customer Address : '  . $address);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $address]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $address .'!']);
        }
    }

    public function deleteselected(Request $request, $id)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $idca = MstCustomerAddress::whereIn('id', $idselected)->pluck('id')->toArray();;
            $delete = MstCustomerAddress::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Customer Address Selected : ' . implode(', ', $idca));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $idca), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
