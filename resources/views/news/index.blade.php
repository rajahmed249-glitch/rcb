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
                <a href="{{ route('news.show', $post) }}" class="news-card">
                    <div class="news-thumb">
                        @if ($post->image)
                            <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" loading="lazy">
                        @else
                            <div class="news-thumb-placeholder">📰</div>
                        @endif
                        <span class="news-date-pill">📅 {{ $post->published_at?->format('d M Y') }}</span>
                    </div>
                    <div class="news-body">
                        <h3>{{ $post->title }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($post->excerpt, 120) }}</p>
                        <div class="news-foot">
                            <span>Read More</span>
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="card"><p class="muted">No news published yet.</p></div>
            @endforelse
        </div>

        {{ $posts->links() }}
    </div>
@endsection
