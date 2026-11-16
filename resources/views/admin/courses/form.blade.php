@extends('admin.layout')

@section('title', $course->exists ? 'Edit course' : 'Add course')

@section('content')
<h1>{{ $course->exists ? 'Edit course' : 'Add course' }}</h1>
<p class="sub"><a href="{{ route('admin.courses.index') }}">&larr; Back to courses</a></p>

<div class="panel">
    <form method="POST" action="{{ $course->exists ? route('admin.courses.update', $course) : route('admin.courses.store') }}">
        @csrf
        @if ($course->exists) @method('PUT') @endif

        <div class="f">
            <label for="name">Course name</label>
            <input id="name" type="text" name="name" value="{{ old('name', $course->name) }}" required maxlength="200">
        </div>
        <div class="cols">
            <div class="f">
                <label for="code">Short code (optional)</label>
                <input id="code" type="text" name="code" value="{{ old('code', $course->code) }}" maxlength="20" placeholder="e.g. MLT">
            </div>
            <div class="f">
                <label for="level">Level</label>
                <input id="level" type="text" name="level" list="levels" value="{{ old('level', $course->level) }}" required maxlength="60">
                <datalist id="levels">
                    <option value="Diploma"><option value="National Diploma"><option value="Certificate"><option value="ND &amp; HND">
                </datalist>
            </div>
            <div class="f">
                <label for="duration">Duration</label>
                <input id="duration" type="text" name="duration" value="{{ old('duration', $course->duration) }}" required maxlength="40" placeholder="e.g. 3 Years">
            </div>
            <div class="f">
                <label for="sort_order">Order on the website</label>
                <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $course->sort_order) }}" min="0" max="9999">
            </div>
        </div>
        <div class="f">
            <label for="summary">Short description (optional)</label>
            <textarea id="summary" name="summary" maxlength="1000">{{ old('summary', $course->summary) }}</textarea>
        </div>
        <div class="f">
            <label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $course->is_active))> Show this course on the website</label>
        </div>
        <button class="btn" type="submit">Save course</button>
    </form>
</div>
@endsection
