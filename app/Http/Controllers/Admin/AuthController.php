<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'password' => ['required', 'string'],
        ]);

        if ($credentials['password'] !== env('ADMIN_PASSWORD', 'rcb@2026')) {
            return back()->withErrors(['password' => __('site.admin.wrong_password')])->onlyInput('password');
        }

        session(['admin_auth' => true]);

        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        session()->forget('admin_auth');

        return redirect()->route('admin.login');
    }
}
