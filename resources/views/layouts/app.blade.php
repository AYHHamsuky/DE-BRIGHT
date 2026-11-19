@php
    $defaultDescription = 'DE BRIGHT College of Health Science and Technology, Lapai, Niger State. Diploma, National Diploma and Certificate programmes in community health, medical laboratory technology, health information management, environmental health, public health and pharmacy technology.';
    $navLinks = [
        'home' => 'Home',
        'about' => 'About',
        'courses' => 'Courses',
        'admissions' => 'Admissions',
        'gallery' => 'Gallery',
        'news' => 'News',
        'contact' => 'Contact',
    ];
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'CollegeOrUniversity',
        'name' => config('school.name'),
        'slogan' => config('school.motto'),
        'url' => url('/'),
        'logo' => asset('images/brand/logo.png'),
        'telephone' => '+234'.substr(config('school.phones')[0], 1),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => config('school.address'),
            'addressLocality' => 'Lapai',
            'addressRegion' => 'Niger State',
            'addressCountry' => 'NG',
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('school.name'))</title>
    <meta name="description" content="@yield('description', $defaultDescription)">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="theme-color" content="#872e30">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('school.name') }}">
    <meta property="og:title" content="@yield('title', config('school.name'))">
    <meta property="og:description" content="@yield('description', $defaultDescription)">
    <meta property="og:image" content="{{ asset('images/slides/slide-2-flyer.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">

    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
</head>
<body>

<div class="topbar">
    <div class="container">
        <span>📍 Minna Road, Lapai, Niger State</span>
        <span>
            @foreach (config('school.phones') as $phone)
                <a href="tel:+234{{ substr($phone, 1) }}">{{ $phone }}</a>
            @endforeach
            <a href="{{ url('/mail/') }}">✉ Staff Email</a>
        </span>
    </div>
</div>

<header class="site-header">
    <div class="container">
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ asset('images/brand/logo.png') }}" alt="DE BRIGHT College logo">
            <span>
                <strong>{{ config('school.short_name') }}</strong>
                <small>{{ config('school.motto') }}</small>
            </span>
        </a>

        <button class="nav-toggle" type="button" aria-label="Open menu" aria-controls="site-nav" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="nav" id="site-nav" aria-label="Main">
            @foreach ($navLinks as $routeName => $label)
                <a href="{{ route($routeName) }}" class="{{ request()->routeIs($routeName, $routeName.'.*') ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
            <a class="btn btn-primary btn-sm" href="{{ route('admissions') }}">Apply Now</a>
        </nav>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <h4>{{ config('school.name') }}</h4>
            <p>{{ config('school.motto') }}. Training the next generation of health professionals in Lapai, Niger State.</p>
        </div>
        <div>
            <h4>Quick links</h4>
            <ul>
                @foreach ($navLinks as $routeName => $label)
                    <li><a href="{{ route($routeName) }}">{{ $label }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h4>Contact us</h4>
            <p>{{ config('school.address') }}</p>
            <p>
                @foreach (config('school.phones') as $phone)
                    <a href="tel:+234{{ substr($phone, 1) }}">{{ $phone }}</a><br>
                @endforeach
            </p>
            @if (config('school.email'))
                <p><a href="mailto:{{ config('school.email') }}">{{ config('school.email') }}</a></p>
            @endif
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">&copy; {{ date('Y') }} {{ config('school.name') }}. All rights reserved.</div>
    </div>
</footer>

<a class="wa-float" href="https://wa.me/{{ config('school.whatsapp') }}?text={{ rawurlencode('Hello, I would like to know more about DE BRIGHT College.') }}" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
    <span aria-hidden="true">💬</span>
</a>

<script src="{{ asset('js/site.js') }}" defer></script>
</body>
</html>
