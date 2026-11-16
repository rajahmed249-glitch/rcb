@extends('layouts.app')

@section('title', 'Latest News')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>Latest News</h1>
            <p>Stories, updates, and highlights — stay connected with every moment that matters.</p>
        </div>

        <div class="news-grid">
            @forelse ($posts as $post)
                <article class="card news-card">
                    <p class="news-date">📅 {{ $post->published_at?->format('d M Y') }}</p>
                    <h3>{{ $post->title }}</h3>
                    <p>{{ $post->excerpt }}</p>
                    <a href="{{ route('news.show', $post) }}" class="read-more">Read More →</a>
                </article>
            @empty
                <div class="card"><p class="muted">No news published yet.</p></div>
            @endforelse
        </div>

        {{ $posts->links() }}
    </div>
@endsection
