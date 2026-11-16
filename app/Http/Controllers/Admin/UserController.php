<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::with('role')->orderBy('id')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.users.form', [
            'user' => new User(),
            'roles' => Role::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['password'] = Hash::make($request->input('password'));

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', __('site.admin.saved'));
    }

    public function edit(User $user)
    {
        return view('admin.users.form', [
            'user' => $user,
            'roles' => Role::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validated($request, $user->id);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request, ?int $userId = null): array
    {
        $emailRule = ['required', 'email', 'max:255'];
        $emailRule[] = $userId
            ? 'unique:users,email,' . $userId
            : 'unique:users,email';

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => $emailRule,
            'role_id' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ];

        if (! $userId) {
            $rules['password'] = ['required', 'string', 'min:6'];
        }

        return $request->validate($rules) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
