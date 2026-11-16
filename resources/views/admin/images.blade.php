@extends('admin.layout')

@section('title', $heading)

@section('content')
<h1>{{ $heading }}</h1>
<p class="sub">{{ $help }}</p>

<div class="panel">
    <h2 style="margin-top:0">Add an image</h2>
    <form method="POST" action="{{ route($routePrefix.'.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="cols">
            <div class="f">
                <label for="image">Image file</label>
                <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
                <div class="hint">JPG, PNG or WebP, up to 5 MB.</div>
            </div>
            <div class="f">
                <label for="title">Title (optional)</label>
                <input id="title" type="text" name="title" maxlength="150" placeholder="Short description">
            </div>
        </div>
        <button class="btn" type="submit">Upload</button>
    </form>
</div>

@if ($items->count())
    <div class="img-grid">
        @foreach ($items as $item)
            <div class="img-card">
                <img src="{{ $item->imageUrl() }}" alt="{{ $item->title }}">
                <div class="body">
                    <form method="POST" action="{{ route($routePrefix.'.update', $item->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="cols">
                            <div class="f">
                                <label>Title</label>
                                <input type="text" name="title" value="{{ $item->title }}" maxlength="150">
                            </div>
                            <div class="f">
                                <label>Order</label>
                                <input type="number" name="sort_order" value="{{ $item->sort_order }}" min="0" max="9999" required>
                            </div>
                        </div>
                        <div class="row-actions">
                            <label class="check" style="margin:0"><input type="checkbox" name="is_active" value="1" @checked($item->is_active)> Show</label>
                            <button class="btn sm" type="submit">Save</button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route($routePrefix.'.destroy', $item->id) }}" style="margin-top:8px"
                          onsubmit="return confirm('Delete this image permanently?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn sm danger" type="submit">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="panel">Nothing here yet. Upload your first image above.</div>
@endif
@endsection
