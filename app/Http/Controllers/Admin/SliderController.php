<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function index()
    {
        return view('admin.sliders.index', [
            'sliders' => Slider::orderBy('sort_order')->orderBy('id')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.sliders.form', ['slider' => new Slider()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->upload($request);

        if (! $data['image']) {
            return back()->withErrors(['image' => __('site.admin.image_required')])->withInput();
        }

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', __('site.admin.saved'));
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.form', ['slider' => $slider]);
    }

    public function update(Request $request, Slider $slider)
    {
        $data = $this->validated($request);
        $data['image'] = $this->upload($request) ?? $slider->image;

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'title_bn' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function upload(Request $request): ?string
    {
        if (! $request->hasFile('image') || ! $request->file('image')->isValid()) {
            return null;
        }

        $file = $request->file('image');
        $filename = 'slider_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/sliders'), $filename);

        return 'uploads/sliders/' . $filename;
    }
}
