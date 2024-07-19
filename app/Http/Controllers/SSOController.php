<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SSOController extends Controller
{
    public function callback(Request $request)
    {
        $token = $request->input('token');

        // Validasi token dan dapatkan email pengguna
        $userEmail = $this->validateToken($token);

        if ($userEmail) {
            // Temukan pengguna berdasarkan email
            $user = User::where('email', $userEmail)->first();

            if ($user) {
                // Login pengguna
                Auth::login($user);

                // Redirect ke dashboard
                return redirect()->route('dashboard');
            }

            return redirect()->route('login')->with('fail', 'Pengguna tidak ditemukan.');
        }

        return redirect()->route('login')->with('fail', 'Token tidak valid.');
    }

    private function validateToken($token)
    {
        // Dekode token
        $decoded = base64_decode($token);
        list($email, $timestamp, $hash) = explode('|', $decoded);

        // Validasi hash
        $data = $email . '|' . $timestamp;
        $validHash = hash_hmac('sha256', $data, env('APP_KEY'));

        if (hash_equals($validHash, $hash) && now()->timestamp - $timestamp < 3600) { // Token berlaku selama 1 jam
            return $email;
        }

        return null;
    }
}
