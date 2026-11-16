@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="container">
        <article class="card" style="max-width: 820px;">
            @if ($post->image)
                <img src="{{ asset($post->image) }}" alt="{{ $post->display_title }}" style="border-radius: 12px; margin-bottom: 1.25rem; width: 100%;">
            @endif
            <p class="news-date">📅 {{ $post->published_at?->format('d M Y') }}</p>
            <h1 style="margin-bottom: 1rem;">{{ $post->display_title }}</h1>
            <div class="article-content muted">{{ $post->display_content }}</div>
        </article>

        @if ($related->isNotEmpty())
            <section class="section" style="margin-top: 3rem;">
                <div class="section-head">
                    <h2>{{ __('site.news.related') }}</h2>
                    <a href="{{ route('news.index') }}" class="btn btn-outline">{{ __('site.view_all') }}</a>
                </div>
                <div class="news-grid">
                    @foreach ($related as $item)
                        <a href="{{ route('news.show', $item) }}" class="news-card">
                            <div class="news-thumb">
                                @if ($item->image)
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->display_title }}" loading="lazy">
                                @else
                                    <div class="news-thumb-placeholder">📰</div>
                                @endif
                                <span class="news-date-pill">📅 {{ $item->published_at?->format('d M Y') }}</span>
                            </div>
                            <div class="news-body">
                                <h3>{{ $item->display_title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($item->display_excerpt, 120) }}</p>
                                <div class="news-foot">
                                    <span>{{ __('site.read_more') }}</span>
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <p style="margin-top: 2rem;"><a href="{{ route('news.index') }}" class="btn btn-outline">{{ __('site.back_to_news') }}</a></p>
    </div>
@endsection
