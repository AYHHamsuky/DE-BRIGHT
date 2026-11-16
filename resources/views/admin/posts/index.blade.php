@extends('admin.layout')

@section('title', 'News')

@section('content')
<div class="row-actions" style="justify-content:space-between">
    <div>
        <h1>News &amp; announcements</h1>
        <p class="sub">Post admission notices, events and updates.</p>
    </div>
    <a class="btn" href="{{ route('admin.posts.create') }}">+ New post</a>
</div>

<div class="panel" style="padding:0; overflow-x:auto">
    <table>
        <thead><tr><th>Title</th><th>Date</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ optional($post->published_at)->format('j M Y') }}</td>
                    <td><span class="pill {{ $post->is_published ? 'ok' : 'off' }}">{{ $post->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td>
                        <div class="row-actions">
                            <a class="btn sm alt" href="{{ route('admin.posts.edit', $post) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn sm danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No posts yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
