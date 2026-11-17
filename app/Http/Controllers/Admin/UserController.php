<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $role = (string) $request->get('role', '');
        $status = (string) $request->get('status', '');
        $limit = in_array((int) $request->get('limit', 10), [10, 25, 50, 100]) ? (int) $request->get('limit', 10) : 10;

        $query = User::with('role');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role !== '') {
            $query->where('role_id', (int) $role);
        }

        if ($status !== '') {
            $query->where('is_active', $status === '1' ? 1 : 0);
        }

        $users = $query->orderBy('id')
            ->paginate($limit)
            ->appends($request->query());

        return view('admin.users.index', [
            'users' => $users,
            'filterSearch' => $search,
            'filterRole' => $role,
            'filterStatus' => $status,
            'filterLimit' => $limit,
            'roles' => Role::orderBy('sort_order')->orderBy('id')->get(),
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
        $data['avatar'] = $this->uploadAvatar($request);

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
            if (! Hash::check($request->input('old_password', ''), $user->password)) {
                return back()
                    ->withErrors(['old_password' => __('site.admin.wrong_old_password')])
                    ->withInput($request->except('password', 'old_password'));
            }

            $data['password'] = Hash::make($request->input('password'));
        } else {
            unset($data['password']);
        }

        $data['avatar'] = $this->uploadAvatar($request) ?? $user->avatar;

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

    private function uploadAvatar(Request $request): ?string
    {
        if (! $request->hasFile('avatar') || ! $request->file('avatar')->isValid()) {
            return null;
        }

        $file = $request->file('avatar');
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/avatars'), $filename);

        return 'uploads/avatars/' . $filename;
    }
}
