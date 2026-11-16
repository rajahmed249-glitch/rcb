<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoleIcon;
use Illuminate\Http\Request;

class RoleIconController extends Controller
{
    public function index()
    {
        return view('admin.role_icons.index', [
            'icons' => RoleIcon::orderBy('sort_order')->orderBy('id')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.role_icons.form', ['icon' => new RoleIcon()]);
    }

    public function store(Request $request)
    {
        RoleIcon::create($this->validated($request));

        return redirect()->route('admin.role_icons.index')->with('success', __('site.admin.saved'));
    }

    public function edit(RoleIcon $roleIcon)
    {
        return view('admin.role_icons.form', ['icon' => $roleIcon]);
    }

    public function update(Request $request, RoleIcon $roleIcon)
    {
        $roleIcon->update($this->validated($request));

        return redirect()->route('admin.role_icons.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(RoleIcon $roleIcon)
    {
        $roleIcon->delete();

        return redirect()->route('admin.role_icons.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['nullable', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
