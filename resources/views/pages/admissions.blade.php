@extends('layouts.app')

@section('title', 'Admissions | De Bright College of Health Science and Technology')
@section('description', 'How to apply to De Bright College of Health Science and Technology, Lapai. Entry requirements for Diploma, Certificate and Direct Entry.')

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>Admissions</h1>
        <p>How to apply and what you need</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">How to apply</span>
            <h2>Four simple steps</h2>
        </div>
        <ol class="steps" style="max-width:760px; margin-inline:auto">
            <li><strong>Choose your programme.</strong> See the <a href="{{ route('courses') }}">list of courses</a> below.</li>
            <li><strong>Check the entry requirements</strong> for your level (Certificate, Diploma or Direct Entry).</li>
            <li><strong>Visit the school</strong> to collect your application form at {{ config('school.address') }}</li>
            <li><strong>Need help?</strong> Call {{ implode(', ', config('school.phones')) }} or <a href="{{ route('contact') }}">send us a message</a>.</li>
        </ol>
    </div>
</section>

<section class="section alt">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Entry requirements</span>
            <h2>What you need to qualify</h2>
        </div>
        <div class="grid grid-3">
            @foreach (config('school.requirements') as $req)
                <div class="card">
                    <h3>{{ $req['title'] }}</h3>
                    <p style="color:var(--muted); margin:0">{{ $req['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

@if ($courses->count())
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Programmes</span>
            <h2>Courses and duration</h2>
        </div>
        <div class="table-wrap">
            <table class="data">
                <thead>
                    <tr><th>#</th><th>Programme</th><th>Level</th><th>Duration</th></tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $course->name }}@if ($course->code) ({{ $course->code }})@endif</td>
                            <td>{{ $course->level }}</td>
                            <td>{{ $course->duration }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endif

<section class="cta">
    <div class="container">
        <h2>{{ config('school.how_to_apply') }}</h2>
        <p>{{ config('school.address') }}</p>
        <div class="btn-row">
            <a class="btn btn-accent" href="{{ route('contact') }}">Find us</a>
            <a class="btn btn-light" href="tel:+234{{ substr(config('school.phones')[0], 1) }}">Call now</a>
        </div>
    </div>
</section>
@endsection
