<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.permissions.index', [
            'permissions' => Permission::orderBy('sort_order')->orderBy('id')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.permissions.form', ['permission' => new Permission()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($request->input('slug') ?: $request->input('name'));

        Permission::create($data);

        return redirect()->route('admin.permissions.index')->with('success', __('site.admin.saved'));
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.form', ['permission' => $permission]);
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($request->input('slug') ?: $request->input('name'));

        $permission->update($data);

        return redirect()->route('admin.permissions.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
