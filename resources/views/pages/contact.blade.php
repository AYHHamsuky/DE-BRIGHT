@extends('layouts.app')

@section('title', 'Contact Us | DE BRIGHT College of Health Science and Technology')
@section('description', 'Contact DE BRIGHT College of Health Science and Technology, Minna Road, Lapai, Niger State. Phone numbers, address, map and enquiry form.')

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We would love to hear from you</p>
    </div>
</div>

<section class="section">
    <div class="container two-col" style="align-items:start">
        <div>
            <h2>Visit or call us</h2>
            <ul class="contact-list">
                <li><span class="ic">📍</span><span>{{ config('school.address') }}</span></li>
                <li>
                    <span class="ic">📞</span>
                    <span>
                        @foreach (config('school.phones') as $phone)
                            <a href="tel:+234{{ substr($phone, 1) }}">{{ $phone }}</a><br>
                        @endforeach
                    </span>
                </li>
                @if (config('school.email'))
                    <li><span class="ic">✉️</span><span><a href="mailto:{{ config('school.email') }}">{{ config('school.email') }}</a></span></li>
                @endif
                <li>
                    <span class="ic">💬</span>
                    <span><a href="https://wa.me/{{ config('school.whatsapp') }}" target="_blank" rel="noopener">Chat on WhatsApp</a></span>
                </li>
            </ul>
            <iframe class="map" loading="lazy" title="Map showing DE BRIGHT College"
                src="https://www.google.com/maps?q={{ urlencode(config('school.map_query')) }}&output=embed"
                allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <div class="form-card">
            <h2>Send us a message</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">Please fix the highlighted fields and try again.</div>
            @endif

            <form method="POST" action="{{ route('contact.send') }}" novalidate>
                @csrf
                <div class="hp" aria-hidden="true">
                    <label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                <div class="field">
                    <label for="name">Full name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="phone">Phone number</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required>
                    @error('phone') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="email">Email (optional)</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}">
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="course_id">Programme you are interested in (optional)</label>
                    <select id="course_id" name="course_id">
                        <option value="">Select a programme</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->name }}</option>
                        @endforeach
                    </select>
                    @error('course_id') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div class="field">
                    <label for="message">Your message</label>
                    <textarea id="message" name="message" required>{{ old('message') }}</textarea>
                    @error('message') <div class="error">{{ $message }}</div> @enderror
                </div>
                <button class="btn btn-primary" type="submit">Send message</button>
            </form>
        </div>
    </div>
</section>
@endsection
