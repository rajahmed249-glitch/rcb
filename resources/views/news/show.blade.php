@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="container">
        <article class="card" style="max-width: 820px;">
            <p class="news-date">📅 {{ $post->published_at?->format('d M Y') }}</p>
            <h1 style="margin-bottom: 1rem;">{{ $post->title }}</h1>
            <div class="article-content muted">{{ $post->content }}</div>
        </article>

        @if ($related->isNotEmpty())
            <section class="section" style="margin-top: 3rem;">
                <div class="section-head">
                    <h2>Related News</h2>
                    <a href="{{ route('news.index') }}" class="btn btn-outline">View All</a>
                </div>
                <div class="news-grid">
                    @foreach ($related as $item)
                        <article class="card news-card">
                            <p class="news-date">📅 {{ $item->published_at?->format('d M Y') }}</p>
                            <h3>{{ $item->title }}</h3>
                            <p>{{ $item->excerpt }}</p>
                            <a href="{{ route('news.show', $item) }}" class="read-more">Read More →</a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <p style="margin-top: 2rem;"><a href="{{ route('news.index') }}" class="btn btn-outline">← Back to News</a></p>
    </div>
@endsection
