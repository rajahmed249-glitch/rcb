<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index()
    {
        return view('admin.gallery.index', [
            'photos' => GalleryPhoto::orderBy('sort_order')->orderBy('id')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.gallery.form', ['photo' => new GalleryPhoto()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->upload($request);

        if (! $data['image']) {
            return back()->withErrors(['image' => __('site.admin.image_required')])->withInput();
        }

        GalleryPhoto::create($data);

        return redirect()->route('admin.gallery.index')->with('success', __('site.admin.saved'));
    }

    public function edit(GalleryPhoto $photo)
    {
        return view('admin.gallery.form', ['photo' => $photo]);
    }

    public function update(Request $request, GalleryPhoto $photo)
    {
        $data = $this->validated($request);
        $data['image'] = $this->upload($request) ?? $photo->image;

        $photo->update($data);

        return redirect()->route('admin.gallery.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(GalleryPhoto $photo)
    {
        $photo->delete();

        return redirect()->route('admin.gallery.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'title_bn' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }

    private function upload(Request $request): ?string
    {
        if (! $request->hasFile('image') || ! $request->file('image')->isValid()) {
            return null;
        }

        $file = $request->file('image');
        $filename = 'gallery_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/gallery'), $filename);

        return 'uploads/gallery/' . $filename;
    }
}
