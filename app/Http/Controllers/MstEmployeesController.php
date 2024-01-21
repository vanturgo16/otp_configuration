<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Browser;

// Model
use App\Models\User;
use App\Models\MstEmployees;
use App\Models\MstProvinces;
use App\Models\MstCountries;
use App\Models\MstDepartments;
use App\Models\MstWorkCenters;
use App\Models\MstBagians;
use App\Models\CmsUsers;

class MstEmployeesController extends Controller
{
    use AuditLogsTrait;

    public function index(){
        $datas = MstEmployees::select('master_employees.*', 'master_provinces.province', 'master_countries.country',
                'master_departements.name as departmentname', 'master_work_centers.work_center', 'master_bagians.name as bagianname')
            ->leftjoin('master_provinces', 'master_employees.id_master_provinces', '=', 'master_provinces.id')
            ->leftjoin('master_countries', 'master_employees.id_master_countries', '=', 'master_countries.id')
            ->leftjoin('master_departements', 'master_employees.id_master_departements', '=', 'master_departements.id')
            ->leftjoin('master_work_centers', 'master_employees.id_master_work_centers', '=', 'master_work_centers.id')
            ->leftjoin('master_bagians', 'master_employees.id_master_bagians', '=', 'master_bagians.id')
            ->get();
        // dd($datas);

        $provinces = MstProvinces::where('is_active', 1)->get();
        $allprovinces = MstProvinces::get();
        $countries = MstCountries::where('is_active', 1)->get();
        $allcountries = MstCountries::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();
        $workcenters = MstWorkCenters::where('status', 'Active')->get();
        $allworkcenters = MstWorkCenters::get();


        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Employee';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);
        
        return view('employee.index', compact('datas', 'allprovinces', 'provinces', 'countries', 'allcountries',
            'departments', 'alldepartments', 'workcenters', 'allworkcenters'));
    }

    public function generateFormattedId($id) {
        $formattedId = 'E' . str_pad($id, 6, '0', STR_PAD_LEFT);
        return $formattedId;
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'nik' => 'required',
            'name' => 'required',
            'address' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
            'id_master_provinces' => 'required',
            'id_master_countries' => 'required',
            'telephone' => 'required',
            'mobile_phone' => 'required',
            'fax' => 'required',
            'email' => 'required',
            'user_id_finger' => 'required',
            'id_master_departements' => 'required',
            'basic_salary' => 'required',
            'regional_minimum_wage' => 'required',
            'account_number' => 'required',
            'remarks' => 'required',
            'password' => 'required',
            'status' => 'required',
            'status_employee' => 'required',
        ]);
        if($request->staff == null){
            $staff= 'N';
        } else{
            $staff= 'Y';
        }
        $basic_salary = str_replace('.', '', $request->basic_salary);
        $basic_salary = str_replace(',', '', $basic_salary);
        $basic_salary = (int)$basic_salary;

        $regional_minimum_wage = str_replace('.', '', $request->regional_minimum_wage);
        $regional_minimum_wage = str_replace(',', '', $regional_minimum_wage); 
        $regional_minimum_wage = (int)$regional_minimum_wage;
        
        DB::beginTransaction();
        try{

            $cms = CmsUsers::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $data = MstEmployees::create([
                'employee_code' => 1,
                'nik' => $request->nik,
                'name' => $request->name,
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
                'id_cms_users' => $cms->id,
                'user_id_finger' => $request->user_id_finger,
                'id_master_departements' => $request->id_master_departements,
                'id_master_bagians' => $request->id_master_bagians,
                'id_master_work_centers' => $request->id_master_work_centers,
                'basic_salary' => $basic_salary,
                'regional_minimum_wage' => $regional_minimum_wage,
                'account_number' => $request->account_number,
                'status_employee' => $request->status_employee,
                'staff' => $staff
            ]);

            $employee_code = $this->generateFormattedId($data->id);
            MstEmployees::where('id', $data->id)->update([
                'employee_code' => $employee_code
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Employee ('. $request->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Employee']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Employee!']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'nik' => 'required',
            'name' => 'required',
            'address' => 'required',
            'postal_code' => 'required',
            'city' => 'required',
            'id_master_provinces' => 'required',
            'id_master_countries' => 'required',
            'telephone' => 'required',
            'mobile_phone' => 'required',
            'fax' => 'required',
            'email' => 'required',
            'user_id_finger' => 'required',
            'id_master_departements' => 'required',
            'basic_salary' => 'required',
            'regional_minimum_wage' => 'required',
            'account_number' => 'required',
            'remarks' => 'required',
            'status' => 'required',
            'status_employee' => 'required',
        ]);

        if($request->staff == null){
            $staff= 'N';
        } else{
            $staff= 'Y';
        }
        $basic_salary = str_replace('.', '', $request->basic_salary);
        $basic_salary = str_replace(',', '', $basic_salary);
        $basic_salary = (int)$basic_salary;

        $regional_minimum_wage = str_replace('.', '', $request->regional_minimum_wage);
        $regional_minimum_wage = str_replace(',', '', $regional_minimum_wage); 
        $regional_minimum_wage = (int)$regional_minimum_wage;

        $databefore = MstEmployees::where('id', $id)->first();
        $databefore->nik = $request->nik;
        $databefore->name = $request->name;
        $databefore->address = $request->address;
        $databefore->postal_code = $request->postal_code;
        $databefore->city = $request->city;
        $databefore->id_master_provinces = $request->id_master_provinces;
        $databefore->id_master_countries = $request->id_master_countries;
        $databefore->telephone = $request->telephone;
        $databefore->mobile_phone = $request->mobile_phone;
        $databefore->fax = $request->fax;
        $databefore->email = $request->email;
        $databefore->user_id_finger = $request->user_id_finger;
        $databefore->id_master_departements = $request->id_master_departements;
        $databefore->id_master_bagians = $request->id_master_bagians;
        $databefore->id_master_work_centers = $request->id_master_work_centers;
        $databefore->basic_salary = $basic_salary;
        $databefore->regional_minimum_wage = $regional_minimum_wage;
        $databefore->account_number = $request->account_number;
        // $databefore->remarks = $request->remarks;
        $databefore->status = $request->status;
        $databefore->status_employee = $request->status_employee;
        $databefore->staff = $staff;

        if($databefore->isDirty() || $request->password != null){
            DB::beginTransaction();
            try{
                if($request->password != null){
                    $cms = CmsUsers::where('id', $databefore->id_cms_users)->update([
                        'password' => Hash::make($request->password)
                    ]);
                }
                $cms = CmsUsers::where('id', $databefore->id_cms_users)->update([
                    'name' => $request->name,
                    'email' => $request->email
                ]);

                $data = MstEmployees::where('id', $id)->update([
                    'nik' => $request->nik,
                    'name' => $request->name,
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
                    'user_id_finger' => $request->user_id_finger,
                    'id_master_departements' => $request->id_master_departements,
                    'id_master_bagians' => $request->id_master_bagians,
                    'id_master_work_centers' => $request->id_master_work_centers,
                    'basic_salary' => $basic_salary,
                    'regional_minimum_wage' => $regional_minimum_wage,
                    'account_number' => $request->account_number,
                    'status_employee' => $request->status_employee,
                    'staff' => $staff
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Employee ('. $request->name . ')';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Employee']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Employee!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstEmployees::where('id', $id)->update([
                'status' => 'Active'
            ]);

            $name = MstEmployees::where('id', $id)->first();

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Activate Employee ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Company ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Activate Company ' . $name->name .'!']);
        }
    }

    public function deactivate($id)
    {
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = MstEmployees::where('id', $id)->update([
                'status' => 'Not Active'
            ]);

            $name = MstEmployees::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate Employee ('. $name->name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Company ' . $name->name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Company ' . $name->name .'!']);
        }
    }
}
