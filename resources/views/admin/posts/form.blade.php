@extends('admin.layout')

@section('title', $post->exists ? 'Edit post' : 'New post')

@section('content')
<h1>{{ $post->exists ? 'Edit post' : 'New post' }}</h1>
<p class="sub"><a href="{{ route('admin.posts.index') }}">&larr; Back to news</a></p>

<div class="panel">
    <form method="POST" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}">
        @csrf
        @if ($post->exists) @method('PUT') @endif

        <div class="f">
            <label for="title">Title</label>
            <input id="title" type="text" name="title" value="{{ old('title', $post->title) }}" required maxlength="200">
        </div>
        <div class="f">
            <label for="excerpt">Short summary (optional)</label>
            <input id="excerpt" type="text" name="excerpt" value="{{ old('excerpt', $post->excerpt) }}" maxlength="300">
            <div class="hint">Shown on the news list and the homepage.</div>
        </div>
        <div class="f">
            <label for="body">Full text</label>
            <textarea id="body" name="body" style="min-height:260px" required>{{ old('body', $post->body) }}</textarea>
            <div class="hint">Press Enter twice to start a new paragraph.</div>
        </div>
        <div class="cols">
            <div class="f">
                <label for="published_at">Publish date</label>
                <input id="published_at" type="datetime-local" name="published_at"
                       value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
            </div>
            <div class="f">
                <label>&nbsp;</label>
                <label class="check"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published))> Published (visible on the website)</label>
            </div>
        </div>
        <button class="btn" type="submit">Save post</button>
    </form>
</div>
@endsection
