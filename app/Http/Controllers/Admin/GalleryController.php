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
            'photos' => GalleryPhoto::orderBy('type')->orderBy('sort_order')->orderBy('id')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.gallery.form', ['photo' => new GalleryPhoto()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->upload($request, 'image');
        $data['thumbnail'] = $this->upload($request, 'thumbnail');

        if ($data['type'] === 'photo' && ! $data['image']) {
            return back()->withErrors(['image' => __('site.admin.image_required')])->withInput();
        }

        if ($data['type'] === 'video' && empty($data['video_code'])) {
            return back()->withErrors(['video_code' => __('site.admin.video_code_required')])->withInput();
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
        $data['image'] = $this->upload($request, 'image') ?? $photo->image;
        $data['thumbnail'] = $this->upload($request, 'thumbnail') ?? $photo->thumbnail;

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
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'title_bn' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'type' => ['required', 'in:photo,video'],
            'video_code' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
        ]);

        $data['video_code'] = $data['video_code'] ? $this->extractVideoCode($data['video_code']) : null;

        return $data;
    }

    private function extractVideoCode(string $input): string
    {
        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,20})~', $input, $m)) {
            return $m[1];
        }

        return $input;
    }

    private function upload(Request $request, string $field): ?string
    {
        if (! $request->hasFile($field) || ! $request->file($field)->isValid()) {
            return null;
        }

        $file = $request->file($field);
        $filename = 'gallery_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/gallery'), $filename);

        return 'uploads/gallery/' . $filename;
    }
}
