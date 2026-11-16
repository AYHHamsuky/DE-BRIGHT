<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') | {{ config('school.short_name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
@php
    $unread = \App\Models\Enquiry::where('is_read', false)->count();
    $menu = [
        'admin.dashboard' => ['Dashboard', 'admin.dashboard'],
        'admin.slides.index' => ['Slider', 'admin.slides.*'],
        'admin.gallery.index' => ['Gallery', 'admin.gallery.*'],
        'admin.courses.index' => ['Courses', 'admin.courses.*'],
        'admin.posts.index' => ['News', 'admin.posts.*'],
        'admin.enquiries.index' => ['Enquiries', 'admin.enquiries.*'],
    ];
@endphp
<div class="adm-top">
    <div class="wrap">
        <strong>{{ config('school.short_name') }} Admin</strong>
        @foreach ($menu as $routeName => [$label, $pattern])
            <a href="{{ route($routeName) }}" class="{{ request()->routeIs($pattern) ? 'on' : '' }}">
                {{ $label }}@if ($routeName === 'admin.enquiries.index' && $unread > 0)<span class="badge">{{ $unread }}</span>@endif
            </a>
        @endforeach
        <span class="spacer"></span>
        <a href="{{ route('home') }}" target="_blank" rel="noopener">View site</a>
        <form method="POST" action="{{ route('admin.logout') }}" style="margin:0">
            @csrf
            <button type="submit">Log out</button>
        </form>
    </div>
</div>

<div class="adm-main">
    @if (session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="flash bad">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
