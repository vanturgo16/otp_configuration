<?php

namespace App\Http\Controllers;

use App\Models\MstDepartments;
use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\User;

class UserController extends Controller
{
    use AuditLogsTrait;
    
    // public function __construct()
    // {
    //     $this->middleware(['permission:permissions.index']);
    //     if(!$this->middleware('auth:sanctum')){
    //         return redirect('/login');
    //     }
  
    // } 

    public function index(Request $request)
    {
        // Initiate Variable
        $departments = MstDepartments::where('is_active', 1)->get();

        // Search Variable
        $department = $request->get('department');
        $name = $request->get('name');
        $email = $request->get('email');
        $status = $request->get('status');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $idUpdated = $request->get('idUpdated');

        $datas=User::select(
                DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
                'users.*', 'master_departements.name as department_name'
            )
            ->leftjoin('master_departements', 'users.department', 'master_departements.id');

        if($department != null){
            $datas = $datas->where('users.department', $department);
        }
        if($name != null){
            $datas = $datas->where('users.name', 'like', '%'.$name.'%');
        }
        if($email != null){
            $datas = $datas->where('users.email', 'like', '%'.$email.'%');
        }
        if($status != null){
            $datas = $datas->where('users.is_active', $status);
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('users.created_at','>=',$startdate)->whereDate('users.created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id', 'department']);
            return $datas;
        }

        $datas = $datas->orderBy('created_at', 'desc');
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($departments){
                    return view('users.action', compact('data', 'departments'));
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
        $this->auditLogsShort('View List Mst User');
        
        return view('users.index',compact('datas', 'departments',
            'department', 'name', 'email', 'status', 'searchDate', 'startdate', 'enddate', 'flag', 'idUpdated', 'page_number'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'department' => 'required',
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);

        $count= User::where('email',$request->email)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Email Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $users = User::create([
                    // 'department' => "Production",
                    'department' => $request->department,
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'is_active' => '1',
                    'role' => $request->role
                ]);

                //Audit Log
                $this->auditLogsShort('Create New User ('. $request->email . ')');

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Create New User, Password is "'.$request->password.'"']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->back()->with(['fail' => 'Failed to Create New User!']);
            }
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $iduser = decrypt($id);

        $request->validate([
            'department' => 'required',
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);

        $userbefore = User::where('id', $iduser)->first();
        $userbefore->department = $request->department;
        $userbefore->name = $request->name;
        $userbefore->email = $request->email;
        $userbefore->role = $request->role;

        if($userbefore->isDirty()){
            $count= User::where('email',$request->email)->whereNotIn('id', [$iduser])->count();
            if($count > 0){
                return redirect()->back()->with('warning','Email Was Already Registered');
            } else {
                DB::beginTransaction();
                try{
                    $users = User::where('id', $iduser)->update([
                        'department' => $request->department,
                        'name' => $request->name,
                        'email' => $request->email,
                        'role' => $request->role
                    ]);

                    //Audit Log
                    $this->auditLogsShort('Create New User ('. $request->email . ')');

                    DB::commit();
                    return redirect()->route('user.index', ['idUpdated' => $iduser])->with(['success' => 'Success Update User']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->route('user.index', ['idUpdated' => $iduser])->with(['fail' => 'Failed to Update User!']);
                }
            }
        } else {
            return redirect()->route('user.index', ['idUpdated' => $iduser])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function delete($id){
        $iduser = decrypt($id);

        // dd($iduser);

        DB::beginTransaction();
        try{
            $users = User::where('id', $iduser)->delete();

            $name = User::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Delete User ('. $name->email . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete User ' . $name->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete User ' . $name->email .'!']);
        }
    }

    public function activate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = User::where('id', $id)->update([
                'is_active' => 1
            ]);

            $name = User::where('id', $id)->first();

            //Audit Log
            $this->auditLogsShort('Activate User ('. $name->email . ')');

            DB::commit();
            return redirect()->route('user.index', ['idUpdated' => $id])->with(['success' => 'Success Activate User ' . $name->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('user.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Activate User ' . $name->email .'!']);
        }
    }

    public function deactivate($id){
        $id = decrypt($id);

        DB::beginTransaction();
        try{
            $data = User::where('id', $id)->update([
                'is_active' => 0
            ]);

            $name = User::where('id', $id)->first();
            
            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Deactivate User ('. $name->email . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->route('user.index', ['idUpdated' => $id])->with(['success' => 'Success Deactivate User ' . $name->email]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('user.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Deactivate User ' . $name->email .'!']);
        }
    }

    
    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $name = User::whereIn('id', $idselected)->pluck('name')->toArray();;
            $delete = User::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete User Selected : ' . implode(', ', $name));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $name), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }

    public function resetPassword(Request $request, $id){
        //dd('hai');

        $iduser = decrypt($id);

        $request->validate([
            'password' => 'required',
        ]);

        $passwordbefore = User::where('id', $iduser)->first();
        $passwordbefore->password = Hash::make($request->password);

        if($passwordbefore->isDirty()){
            DB::beginTransaction();
            try{
                $users = User::where('id', $iduser)->update([
                    'password' => Hash::make($request->password),
                ]);

                //Audit Log
                $this->auditLogsShort('Reset Password for User ('. $passwordbefore->email . ')');

                DB::commit();
                return redirect()->route('user.index', ['idUpdated' => $iduser])->with(['success' => 'Success Reset Password User']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route('user.index', ['idUpdated' => $iduser])->with(['fail' => 'Failed Reset Password User!']);
            }
        } else {
            return redirect()->route('user.index', ['idUpdated' => $iduser])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }
}
