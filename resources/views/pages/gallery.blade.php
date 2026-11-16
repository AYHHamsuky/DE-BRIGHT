@extends('layouts.app')

@section('title', 'Gallery | De Bright College of Health Science and Technology')
@section('description', 'Photos of classes, laboratory work and practical training at De Bright College of Health Science and Technology, Lapai.')

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>Photo Gallery</h1>
        <p>Life and learning at De Bright College</p>
    </div>
</div>

<section class="section">
    <div class="container">
        @if ($items->count())
            <div class="gallery">
                @foreach ($items as $item)
                    <figure style="margin:0">
                        <a href="{{ $item->imageUrl() }}" data-lightbox data-title="{{ $item->title }}">
                            <img src="{{ $item->imageUrl() }}" alt="{{ $item->title ?: 'De Bright College photo' }}" loading="lazy">
                            @if ($item->title)
                                <figcaption>{{ $item->title }}</figcaption>
                            @endif
                        </a>
                    </figure>
                @endforeach
            </div>
        @else
            <p class="empty">Photos will be added soon.</p>
        @endif
    </div>
</section>
@endsection
