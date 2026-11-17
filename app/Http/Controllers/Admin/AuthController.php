<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            if (! $user->is_active) {
                return back()->withErrors(['password' => __('site.admin.account_inactive')])->onlyInput('email');
            }

            if (! Hash::check($credentials['password'], $user->password)) {
                return back()->withErrors(['password' => __('site.admin.wrong_credentials')])->onlyInput('email');
            }

            session([
                'admin_auth' => true,
                'admin_user_id' => $user->id,
                'admin_user_name' => $user->name,
                'admin_user_role' => $user->role?->display_name ?? 'Administrator',
                'admin_user_avatar' => $user->avatar,
            ]);

            return redirect()->route('admin.dashboard');
        }

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
        session()->forget(['admin_auth', 'admin_user_id', 'admin_user_name', 'admin_user_role', 'admin_user_avatar']);

        return redirect()->route('admin.login');
    }
}
