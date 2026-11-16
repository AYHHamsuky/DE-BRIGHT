@extends('layouts.app')

@section('title', $post->title.' | De Bright College')
@section('description', $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 155))

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->published_at->format('j F Y') }}</p>
    </div>
</div>

<section class="section">
    <div class="container prose">
        {!! nl2br(e($post->body)) !!}
        <p style="margin-top:30px"><a class="btn btn-outline btn-sm" href="{{ route('news') }}">&larr; Back to news</a></p>
    </div>
</section>
@endsection
