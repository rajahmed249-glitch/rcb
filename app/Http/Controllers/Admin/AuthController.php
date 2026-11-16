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
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $validEmail = strtolower($credentials['email']) === strtolower(env('ADMIN_EMAIL', 'rajahmed249@gmail.com'));
        $validPassword = $credentials['password'] === env('ADMIN_PASSWORD', 'rcb@2026');

        if (! $validEmail || ! $validPassword) {
            return back()->withErrors(['password' => __('site.admin.wrong_credentials')])->onlyInput('email');
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
