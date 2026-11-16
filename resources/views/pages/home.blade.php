@extends('layouts.app')

@section('title', 'De Bright College of Health Science and Technology | Lapai, Niger State')

@section('content')

{{-- Sliding banner --}}
@if ($slides->count())
<section class="slider" data-slider aria-roledescription="carousel" aria-label="School highlights" tabindex="0">
    <div class="slides">
        @foreach ($slides as $slide)
            <div class="slide" role="group" aria-roledescription="slide" aria-label="{{ $loop->iteration }} of {{ $loop->count }}">
                <div class="slide-bg" style="background-image:url('{{ $slide->imageUrl() }}')"></div>
                <img src="{{ $slide->imageUrl() }}" alt="{{ $slide->title ?: config('school.name') }}">
            </div>
        @endforeach
    </div>
    <button type="button" class="slider-btn prev" aria-label="Previous slide">&#10094;</button>
    <button type="button" class="slider-btn next" aria-label="Next slide">&#10095;</button>
    <div class="slider-dots"></div>
</section>
@endif

{{-- Welcome --}}
<section class="intro">
    <div class="container">
        <span class="motto-pill">{{ config('school.motto') }}</span>
        <h1>{{ config('school.name') }}</h1>
        <p class="lead">Practical, career-focused training in community health, laboratory technology, health records, environmental health, public health and pharmacy technology, in Lapai, Niger State.</p>
        <div class="btn-row">
            <a class="btn btn-primary" href="{{ route('admissions') }}">How to apply</a>
            <a class="btn btn-outline" href="{{ route('courses') }}">View all courses</a>
        </div>
    </div>
</section>

{{-- Why us --}}
<section class="section">
    <div class="container">
        <div class="grid grid-4">
            <div class="card feature">
                <div class="icon">🔬</div>
                <h3>Hands-on laboratory training</h3>
                <p>Students learn at the microscope and the laboratory bench, not only from textbooks.</p>
            </div>
            <div class="card feature">
                <div class="icon">🩺</div>
                <h3>Practical clinical skills</h3>
                <p>Classroom practicals teach patient care, vital signs and basic procedures.</p>
            </div>
            <div class="card feature">
                <div class="icon">🏘️</div>
                <h3>Community health focus</h3>
                <p>Programmes prepare graduates to serve communities and strengthen primary healthcare.</p>
            </div>
            <div class="card feature">
                <div class="icon">🎓</div>
                <h3>Flexible entry routes</h3>
                <p>Certificate, Diploma and Direct Entry options, so more students can get started.</p>
            </div>
        </div>
    </div>
</section>

{{-- Courses --}}
@if ($courses->count())
<section class="section alt">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Our programmes</span>
            <h2>Choose your path in health</h2>
            <p>{{ $courseCount }} programmes at Certificate, Diploma and National Diploma level.</p>
        </div>
        <div class="grid grid-3">
            @foreach ($courses as $course)
                @include('partials.course-card', ['course' => $course])
            @endforeach
        </div>
        <div class="btn-row" style="margin-top:30px">
            <a class="btn btn-primary" href="{{ route('courses') }}">See all programmes</a>
        </div>
    </div>
</section>
@endif

{{-- News --}}
@if ($posts->count())
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">News</span>
            <h2>Latest from the college</h2>
        </div>
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
    </div>
</section>
@endif

{{-- Call to action --}}
<section class="cta">
    <div class="container">
        <h2>Ready to start your health career?</h2>
        <p>Visit the school to collect your application form, or call us and we will guide you.</p>
        <div class="btn-row">
            <a class="btn btn-accent" href="{{ route('admissions') }}">Admission details</a>
            <a class="btn btn-light" href="tel:+234{{ substr(config('school.phones')[0], 1) }}">Call {{ config('school.phones')[0] }}</a>
        </div>
    </div>
</section>
@endsection
