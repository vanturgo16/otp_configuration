<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
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

    public function index(Request $request)
    {
        // Initiate Variable
        $allprovinces = MstProvinces::get();
        $countries = MstCountries::where('is_active', 1)->get();
        $allcountries = MstCountries::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();
        $workcenters = MstWorkCenters::where('status', 'Active')->get();
        $allworkcenters = MstWorkCenters::get();

        // Search Variable
        $employee_code = $request->get('employee_code');
        $nik = $request->get('nik');
        $name = $request->get('name');
        $address = $request->get('address');
        $mobile_phone = $request->get('mobile_phone');
        $id_master_departements = $request->get('id_master_departements');
        $basic_salary = $request->get('basic_salary');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstEmployees::select(DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'), 'master_employees.*', 'master_provinces.province', 'master_countries.country',
                'master_departements.name as departmentname', 'master_work_centers.work_center', 'master_bagians.name as bagianname')
            ->leftjoin('master_provinces', 'master_employees.id_master_provinces', '=', 'master_provinces.id')
            ->leftjoin('master_countries', 'master_employees.id_master_countries', '=', 'master_countries.id')
            ->leftjoin('master_departements', 'master_employees.id_master_departements', '=', 'master_departements.id')
            ->leftjoin('master_work_centers', 'master_employees.id_master_work_centers', '=', 'master_work_centers.id')
            ->leftjoin('master_bagians', 'master_employees.id_master_bagians', '=', 'master_bagians.id');

        if($employee_code != null){
            $datas = $datas->where('master_employees.employee_code', 'like', '%'.$employee_code.'%');
        }
        if($nik != null){
            $datas = $datas->where('master_employees.nik', 'like', '%'.$nik.'%');
        }
        if($name != null){
            $datas = $datas->where('master_employees.name', 'like', '%'.$name.'%');
        }
        if($address != null){
            $datas = $datas->where('master_employees.address', 'like', '%'.$address.'%');
        }
        if($mobile_phone != null){
            $datas = $datas->where('master_employees.mobile_phone', 'like', '%'.$mobile_phone.'%');
        }
        if($id_master_departements != null){
            $datas = $datas->where('master_employees.id_master_departements', 'like', '%'.$id_master_departements.'%');
        }
        if($basic_salary != null){
            $datas = $datas->where('master_employees.basic_salary', 'like', '%'.$basic_salary.'%');
        }
        if($status != null){
            $datas = $datas->where('master_employees.status', $status);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('created_at','>=',$startdate)->whereDate('created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden([
                'id', 'id_master_provinces', 'id_master_countries',
                'id_master_departements', 'id_master_bagians'
            ]);
            return $datas;
        }

        $datas = $datas->orderBy('created_at', 'desc')->get();
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($allprovinces, $countries, $allcountries, $departments, $alldepartments, $workcenters, $allworkcenters){
                    return view('employee.action', compact('data', 'allprovinces', 'countries', 'allcountries', 'departments', 'alldepartments', 'workcenters', 'allworkcenters'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }

        //Audit Log
        $this->auditLogsShort('View List Mst Employee');
        
        return view('employee.index', compact('datas', 'allprovinces', 'countries', 'allcountries',
            'departments', 'alldepartments', 'workcenters', 'allworkcenters',
            'employee_code', 'nik', 'name', 'address', 'mobile_phone', 'id_master_departements', 'basic_salary', 'status', 'searchDate', 'startdate', 'enddate', 'flag'));
    }

    public function info($id)
    {
        $id = decrypt($id);

        $data = MstEmployees::select('master_employees.*', 'master_provinces.province', 'master_countries.country',
                'master_departements.name as departmentname', 'master_work_centers.work_center', 'master_bagians.name as bagianname')
            ->leftjoin('master_provinces', 'master_employees.id_master_provinces', '=', 'master_provinces.id')
            ->leftjoin('master_countries', 'master_employees.id_master_countries', '=', 'master_countries.id')
            ->leftjoin('master_departements', 'master_employees.id_master_departements', '=', 'master_departements.id')
            ->leftjoin('master_work_centers', 'master_employees.id_master_work_centers', '=', 'master_work_centers.id')
            ->leftjoin('master_bagians', 'master_employees.id_master_bagians', '=', 'master_bagians.id')
            ->where('master_employees.id', $id)
            ->first();
        
        //Audit Log
        $this->auditLogsShort('View Info Employee, Code ('. $data->employee_code . ')');

        return view('employee.info',compact('data'));
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
        $basic_salary = str_replace(',', '.', $basic_salary);

        $regional_minimum_wage = str_replace('.', '', $request->regional_minimum_wage);
        $regional_minimum_wage = str_replace(',', '.', $regional_minimum_wage);
        
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
            $this->auditLogsShort('Create New Employee ('. $request->name . ')');

            DB::commit();

            return redirect()->back()->with(['success' => 'Success Create New Employee']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Create New Employee!']);
        }
    }

    public function edit($id)
    {
        $id = decrypt($id);

        // Initiate Variable
        $allprovinces = MstProvinces::get();
        $countries = MstCountries::where('is_active', 1)->get();
        $allcountries = MstCountries::get();
        $departments = MstDepartments::where('is_active', 1)->get();
        $alldepartments = MstDepartments::get();
        $workcenters = MstWorkCenters::where('status', 'Active')->get();
        $allworkcenters = MstWorkCenters::get();

        $data = MstEmployees::select('master_employees.*', 'master_provinces.province', 'master_countries.country',
                'master_departements.name as departmentname', 'master_work_centers.work_center', 'master_bagians.name as bagianname')
            ->leftjoin('master_provinces', 'master_employees.id_master_provinces', '=', 'master_provinces.id')
            ->leftjoin('master_countries', 'master_employees.id_master_countries', '=', 'master_countries.id')
            ->leftjoin('master_departements', 'master_employees.id_master_departements', '=', 'master_departements.id')
            ->leftjoin('master_work_centers', 'master_employees.id_master_work_centers', '=', 'master_work_centers.id')
            ->leftjoin('master_bagians', 'master_employees.id_master_bagians', '=', 'master_bagians.id')
            ->where('master_employees.id', $id)
            ->first();
        
        //Audit Log
        $this->auditLogsShort('View Edit Employee Form, Code ('. $data->employee_code . ')');

        return view('employee.edit',compact('data', 'allprovinces', 'allcountries', 'alldepartments', 'allworkcenters'));
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
        $basic_salary = str_replace(',', '.', $basic_salary);

        $regional_minimum_wage = str_replace('.', '', $request->regional_minimum_wage);
        $regional_minimum_wage = str_replace(',', '.', $regional_minimum_wage);

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
                $this->auditLogsShort('Update Employee ('. $request->name . ')');

                DB::commit();
                return redirect()->route('employee.index')->with(['success' => 'Success Update Employee']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Update Employee!']);
            }
        } else {
            return redirect()->route('employee.index')->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
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
            $this->auditLogsShort('Activate Employee ('. $name->name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Activate Company ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
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
            $this->auditLogsShort('Deactivate Employee ('. $name->name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Deactivate Company ' . $name->name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Deactivate Company ' . $name->name .'!']);
        }
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        // dd($id);

        DB::beginTransaction();
        try{
            $employee_code = MstEmployees::where('id', $id)->first()->employee_code;
            MstEmployees::where('id', $id)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Data Employee : '  . $employee_code);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Data : ' . $employee_code]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Data : ' . $employee_code .'!']);
        }
    }

    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $employee_code = MstEmployees::whereIn('id', $idselected)->pluck('employee_code')->toArray();;
            $delete = MstEmployees::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Employee Selected : ' . implode(', ', $employee_code));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $employee_code), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
