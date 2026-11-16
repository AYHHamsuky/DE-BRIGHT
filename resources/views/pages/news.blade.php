@extends('layouts.app')

@section('title', 'News | De Bright College of Health Science and Technology')
@section('description', 'News and announcements from De Bright College of Health Science and Technology, Lapai.')

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>News &amp; Announcements</h1>
        <p>What is happening at the college</p>
    </div>
</div>

<section class="section">
    <div class="container">
        @if ($posts->count())
            <div class="grid grid-3">
                @foreach ($posts as $post)
                    <article class="card post-card">
                        <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('j F Y') }}</time>
                        <h3><a href="{{ route('news.show', $post->slug) }}">{{ $post->title }}</a></h3>
                        <p>{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 130) }}</p>
                        <a href="{{ route('news.show', $post->slug) }}">Read more &rarr;</a>
                    </article>
                @endforeach
            </div>

            <div class="pager">
                <span>@if ($posts->previousPageUrl())<a class="btn btn-outline btn-sm" href="{{ $posts->previousPageUrl() }}">&larr; Newer</a>@endif</span>
                <span>@if ($posts->nextPageUrl())<a class="btn btn-outline btn-sm" href="{{ $posts->nextPageUrl() }}">Older &rarr;</a>@endif</span>
            </div>
        @else
            <p class="empty">No news yet. Please check back soon.</p>
        @endif
    </div>
</section>
@endsection
