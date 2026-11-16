@extends('layouts.app')

@section('title', 'Courses | De Bright College of Health Science and Technology')
@section('description', 'Available courses at De Bright College: Community Health, Medical Laboratory Technology, Health Information Management, Environmental Health, Public Health and Pharmacy Technician.')

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>Available Courses</h1>
        <p>Certificate, Diploma and National Diploma programmes</p>
    </div>
</div>

<section class="section">
    <div class="container">
        @if ($courses->count())
            <div class="grid grid-3">
                @foreach ($courses as $course)
                    @include('partials.course-card', ['course' => $course])
                @endforeach
            </div>
        @else
            <p class="empty">Courses will be listed here soon.</p>
        @endif

        <div class="card" style="margin-top:36px; text-align:center">
            <h3>Not sure which course is right for you?</h3>
            <p>Check the entry requirements, or call us and we will help you choose.</p>
            <div class="btn-row">
                <a class="btn btn-primary" href="{{ route('admissions') }}">Entry requirements</a>
                <a class="btn btn-outline" href="{{ route('contact') }}">Ask a question</a>
            </div>
        </div>
    </div>
</section>
@endsection
