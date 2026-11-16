<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function index()
    {
        return view('admin.sponsors.index', [
            'sponsors' => Sponsor::orderBy('sort_order')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.sponsors.form', ['sponsor' => new Sponsor()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['logo'] = $this->upload($request);

        Sponsor::create($data);

        return redirect()->route('admin.sponsors.index')->with('success', __('site.admin.saved'));
    }

    public function edit(Sponsor $sponsor)
    {
        return view('admin.sponsors.form', ['sponsor' => $sponsor]);
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $data = $this->validated($request);
        $data['logo'] = $this->upload($request) ?? $sponsor->logo;

        $sponsor->update($data);

        return redirect()->route('admin.sponsors.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(Sponsor $sponsor)
    {
        $sponsor->delete();

        return redirect()->route('admin.sponsors.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }

    private function upload(Request $request): ?string
    {
        if (! $request->hasFile('logo') || ! $request->file('logo')->isValid()) {
            return null;
        }

        $file = $request->file('logo');
        $filename = 'sponsor_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/sponsors'), $filename);

        return 'uploads/sponsors/' . $filename;
    }
}
