<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index', [
            'roles' => Role::orderBy('sort_order')->orderBy('id')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.roles.form', ['role' => new Role()]);
    }

    public function store(Request $request)
    {
        Role::create($this->validated($request));

        return redirect()->route('admin.roles.index')->with('success', __('site.admin.saved'));
    }

    public function edit(Role $role)
    {
        return view('admin.roles.form', ['role' => $role]);
    }

    public function update(Request $request, Role $role)
    {
        $role->update($this->validated($request));

        return redirect()->route('admin.roles.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
