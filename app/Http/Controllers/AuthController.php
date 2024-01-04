<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        $email=$request->email;
        $password=$request->password;
        $credentials = [
            'email' => $email,
            'password' => $password
        ];
        $dologin=Auth::attempt($credentials);
        if($dologin){
            return redirect()->route('dashboard')->with('success','Successfully Entered The Application');
        }
        else{
            return redirect()->route('login')->with('fail','Wrong Email or Password');
        }
    }

    public function logout(Request $request){
        Auth::logout();
        return redirect()->route('login')->with('success','Success Logout');
    }
}
