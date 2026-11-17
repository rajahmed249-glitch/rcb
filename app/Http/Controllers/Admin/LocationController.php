<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'district') === 'thana' ? 'thana' : 'district';
        $search = trim((string) $request->get('search', ''));
        $status = (string) $request->get('status', '');
        $parent = (string) $request->get('parent', '');
        $limit = in_array((int) $request->get('limit', 10), [10, 25, 50, 100]) ? (int) $request->get('limit', 10) : 10;

        $query = Location::where('type', $tab);

        if ($parent !== '') {
            $query->where('parent_id', (int) $parent);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('name_bn', 'like', "%{$search}%");
            });
        }

        if ($status !== '') {
            $query->where('status', $status === '1' ? 1 : 0);
        }

        $locations = $query->with('parent')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate($limit)
            ->appends($request->query());

        return view('admin.locations.index', [
            'locations' => $locations,
            'tab' => $tab,
            'filterSearch' => $search,
            'filterStatus' => $status,
            'filterParent' => $parent,
            'filterLimit' => $limit,
            'districts' => Location::where('type', 'district')->orderBy('name')->get(),
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.locations.form', [
            'location' => new Location(['type' => $request->get('type', 'district')]),
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
        if ($location->type === 'district') {
            Location::where('type', 'thana')->where('parent_id', $location->id)->delete();
        }

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
            'status' => ['nullable', 'boolean'],
        ]) + [
            'status' => $request->boolean('status'),
        ];
    }
}
