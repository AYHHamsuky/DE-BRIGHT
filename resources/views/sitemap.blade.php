{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach (['home', 'about', 'approval', 'courses', 'admissions', 'gallery', 'news', 'contact'] as $name)
    <url><loc>{{ route($name) }}</loc></url>
@endforeach
@foreach ($posts as $post)
    <url><loc>{{ route('news.show', $post->slug) }}</loc><lastmod>{{ $post->updated_at->toAtomString() }}</lastmod></url>
@endforeach
</urlset>
