<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        return view('admin.news.index', [
            'posts' => NewsPost::orderByDesc('published_at')->paginate(10),
        ]);
    }

    public function create()
    {
        return view('admin.news.form', ['post' => new NewsPost()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->upload($request);
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $request->input('title'));

        NewsPost::create($data);

        return redirect()->route('admin.news.index')->with('success', __('site.admin.saved'));
    }

    public function edit(NewsPost $post)
    {
        return view('admin.news.form', ['post' => $post]);
    }

    public function update(Request $request, NewsPost $post)
    {
        $data = $this->validated($request);
        $data['image'] = $this->upload($request) ?? $post->image;

        if ($request->filled('slug')) {
            $data['slug'] = $this->uniqueSlug($request->input('slug'), $post->id);
        }

        $post->update($data);

        return redirect()->route('admin.news.index')->with('success', __('site.admin.saved'));
    }

    public function destroy(NewsPost $post)
    {
        $post->delete();

        return redirect()->route('admin.news.index')->with('success', __('site.admin.deleted'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'title_bn' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'excerpt_bn' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'content_bn' => ['nullable', 'string'],
            'published_at' => ['required', 'date'],
        ]);
    }

    private function upload(Request $request): ?string
    {
        if (! $request->hasFile('image') || ! $request->file('image')->isValid()) {
            return null;
        }

        $file = $request->file('image');
        $filename = 'news_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/news'), $filename);

        return 'uploads/news/' . $filename;
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: 'post';
        $original = $slug;
        $i = 2;

        while (NewsPost::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}
