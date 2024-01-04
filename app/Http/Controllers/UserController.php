<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Model
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $users=User::get();
        
        return view('users.index',compact('users'));
    }
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);

        $count= User::where('email',$request->email)->count();
        
        if($count > 0){
            return redirect()->back()->with('warning','Email Was Already Registered');
        } else {
            DB::beginTransaction();
            try{
                $users = User::create([
                    'department' => "Production",
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make('password'),
                    'is_active' => '1',
                    'role' => $request->role
                ]);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Create New User']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Create New User!']);
            }
        }
    }

    public function update(Request $request, $id){
        // dd($request->all());

        $iduser = decrypt($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);

        $userbefore = User::where('id', $iduser)->first();
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
                        'name' => $request->name,
                        'email' => $request->email,
                        'role' => $request->role
                    ]);

                    DB::commit();
                    return redirect()->back()->with(['success' => 'Success Update User']);
                } catch (\Exception $e) {
                    dd($e);
                    return redirect()->back()->with(['fail' => 'Failed to Update User!']);
                }
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }

    public function delete($id){
        $iduser = decrypt($id);

        dd($iduser);

        DB::beginTransaction();
        try{
            $users = User::where('id', $iduser)->delete();

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete User']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Delete User!']);
        }
    }
}
