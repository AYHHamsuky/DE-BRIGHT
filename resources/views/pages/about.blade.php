@extends('layouts.app')

@section('title', 'About Us | DE BRIGHT College of Health Science and Technology')
@section('description', 'Learn about DE BRIGHT College of Health Science and Technology in Lapai, Niger State, and the health programmes we offer.')

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>About Us</h1>
        <p>{{ config('school.motto') }}</p>
    </div>
</div>

<section class="section">
    <div class="container two-col">
        <div class="prose">
            <span class="eyebrow">Who we are</span>
            <h2>{{ config('school.name') }}</h2>
            <p>DE BRIGHT College of Health Science and Technology is a health training institution located in Lapai, Niger State. The college trains students for careers in community health, medical laboratory technology, health information management, environmental health, public health and pharmacy technology.</p>
            <p>Our motto, <strong>{{ config('school.motto') }}</strong>, guides everything we do. We combine classroom teaching with practical sessions in the laboratory and in clinical skills, so that graduates are ready to serve their communities.</p>
            <p>We offer Certificate, Diploma and National Diploma programmes, with Direct Entry available to applicants who hold a relevant professional certificate.</p>
            <div class="btn-row" style="justify-content:flex-start">
                <a class="btn btn-primary" href="{{ route('courses') }}">Our courses</a>
                <a class="btn btn-outline" href="{{ route('contact') }}">Contact us</a>
            </div>
        </div>
        <div>
            <img class="rounded" src="{{ asset('images/gallery/practical-mannequin.jpg') }}" alt="Students and lecturer during a practical class at DE BRIGHT College">
        </div>
    </div>
</section>

<section class="section alt">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">What to expect</span>
            <h2>Learning that prepares you for work</h2>
        </div>
        <div class="grid grid-3">
            <div class="card feature">
                <div class="icon">📚</div>
                <h3>Quality teaching</h3>
                <p>Structured lessons that cover the theory behind every health programme we offer.</p>
            </div>
            <div class="card feature">
                <div class="icon">🧪</div>
                <h3>Practical sessions</h3>
                <p>Laboratory work and clinical skills practice give students confidence before they graduate.</p>
            </div>
            <div class="card feature">
                <div class="icon">🤝</div>
                <h3>Community service</h3>
                <p>Our programmes are built around improving health and wellbeing in the communities we serve.</p>
            </div>
        </div>
    </div>
</section>
@endsection
