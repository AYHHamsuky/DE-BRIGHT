@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<h1>Dashboard</h1>
<p class="sub">Manage everything that appears on the website.</p>

<div class="stats">
    <a class="stat" href="{{ route('admin.enquiries.index') }}"><b>{{ $counts['unread'] }}</b><span>Unread enquiries</span></a>
    <a class="stat" href="{{ route('admin.slides.index') }}"><b>{{ $counts['slides'] }}</b><span>Slider images</span></a>
    <a class="stat" href="{{ route('admin.gallery.index') }}"><b>{{ $counts['gallery'] }}</b><span>Gallery photos</span></a>
    <a class="stat" href="{{ route('admin.courses.index') }}"><b>{{ $counts['courses'] }}</b><span>Courses</span></a>
    <a class="stat" href="{{ route('admin.posts.index') }}"><b>{{ $counts['posts'] }}</b><span>News posts</span></a>
</div>

<div class="panel">
    <h2 style="margin-top:0">Latest enquiries</h2>
    @if ($latest->count())
        <table>
            <thead><tr><th>Name</th><th>Phone</th><th>Programme</th><th>Received</th></tr></thead>
            <tbody>
                @foreach ($latest as $enquiry)
                    <tr class="{{ $enquiry->is_read ? '' : 'unread' }}">
                        <td><a href="{{ route('admin.enquiries.show', $enquiry) }}">{{ $enquiry->name }}</a></td>
                        <td>{{ $enquiry->phone }}</td>
                        <td>{{ $enquiry->course?->name ?? '-' }}</td>
                        <td>{{ $enquiry->created_at->format('j M Y, g:i a') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No enquiries yet.</p>
    @endif
</div>
@endsection
