<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\PlayerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlayerApplicationController extends Controller
{
    public function create()
    {
        return view('be-a-player.create', [
            'districts' => Location::where('type', 'district')->where('status', 1)->orderBy('sort_order')->orderBy('name')->get(),
            'thanas' => Location::where('type', 'thana')->where('status', 1)->orderBy('sort_order')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'dob' => ['required', 'date'],
            'preferred_role' => ['nullable', 'string', 'max:50'],
            'jersey_size' => ['nullable', 'string', 'max:20'],
            'message' => ['nullable', 'string', 'max:2000'],
            'present_village' => ['nullable', 'string', 'max:255'],
            'present_district_id' => ['nullable', 'integer', 'exists:locations,id'],
            'present_thana_id' => ['nullable', 'integer', 'exists:locations,id'],
            'present_post_office' => ['nullable', 'string', 'max:255'],
            'permanent_village' => ['nullable', 'string', 'max:255'],
            'permanent_district_id' => ['nullable', 'integer', 'exists:locations,id'],
            'permanent_thana_id' => ['nullable', 'integer', 'exists:locations,id'],
            'permanent_post_office' => ['nullable', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:50'],
            'religion' => ['nullable', 'string', 'max:50'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'height' => ['nullable', 'string', 'max:20'],
            'facebook_id' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->boolean('same_as_present')) {
            foreach (['village', 'district_id', 'thana_id', 'post_office'] as $field) {
                $validated['permanent_' . $field] = $validated['present_' . $field] ?? null;
            }
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            $filename = 'application_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/applications'), $filename);
            $validated['photo'] = 'uploads/applications/' . $filename;
        }

        PlayerApplication::create($validated);

        return redirect()
            ->route('be-a-player.create')
            ->with('success', __('site.be_a_player_page.success'));
    }
}
