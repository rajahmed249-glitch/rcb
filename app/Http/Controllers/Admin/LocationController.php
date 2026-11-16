<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return view('admin.locations.index', [
            'locations' => Location::orderBy('type')->orderBy('sort_order')->orderBy('id')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.locations.form', [
            'location' => new Location(),
            'parents' => Location::where('type', 'district')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Location::create($this->validated($request));

        return redirect()->route('admin.locations.index')->with('success', __('site.admin.saved'));
    }

    public function edit(Location $location)
    {
        return view('admin.locations.form', [
            'location' => $location,
            'parents' => Location::where('type', 'district')->where('id', '!=', $location->id)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Location $location)
    {
        $location->update($this->validated($request));

        return redirect()->route('admin.locations.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:district,thana'],
            'parent_id' => ['nullable', 'integer'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
