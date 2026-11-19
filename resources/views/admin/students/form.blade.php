@extends('admin.layout')

@section('title', $student->exists ? 'Edit student' : 'Add student')

@section('content')
<h1>{{ $student->exists ? 'Edit student' : 'Add student' }}</h1>
<p class="sub"><a href="{{ $student->exists ? route('admin.students.show', $student) : route('admin.students.index') }}">&larr; Back</a></p>

<form method="POST" action="{{ $student->exists ? route('admin.students.update', $student) : route('admin.students.store') }}">
    @csrf
    @if ($student->exists) @method('PUT') @endif

    <div class="panel">
        <h2 style="margin-top:0">Portal login</h2>
        <div class="cols">
            <div class="f">
                <label for="reg_number">Registration number</label>
                <input id="reg_number" type="text" name="reg_number" value="{{ old('reg_number', $student->reg_number) }}" required maxlength="40" placeholder="e.g. DBC/2026/MLT/001">
                <div class="hint">The student uses this to log in.</div>
            </div>
            <div class="f">
                <label for="name">Full name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $student->user?->name) }}" required maxlength="150">
            </div>
            <div class="f">
                <label for="email">Email (optional)</label>
                <input id="email" type="email" name="email" value="{{ old('email', $student->user && ! str_ends_with($student->user->email, '@'.config('school.student_email_domain')) ? $student->user->email : '') }}" maxlength="150">
                <div class="hint">Payment receipts from Paystack go here.</div>
            </div>
            <div class="f">
                <label for="password">{{ $student->exists ? 'New password (leave blank to keep)' : 'Password (leave blank to generate one)' }}</label>
                <input id="password" type="text" name="password" minlength="8" autocomplete="new-password">
            </div>
        </div>
    </div>

    <div class="panel">
        <h2 style="margin-top:0">Academic record</h2>
        <div class="cols">
            <div class="f">
                <label for="course_id">Course</label>
                <select id="course_id" name="course_id">
                    <option value="">-- Not set --</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected(old('course_id', $student->course_id) == $course->id)>{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="f">
                <label for="level">Level / year</label>
                <input id="level" type="text" name="level" list="levels" value="{{ old('level', $student->level) }}" maxlength="40">
                <datalist id="levels"><option value="Year 1"><option value="Year 2"><option value="Year 3"></datalist>
            </div>
            <div class="f">
                <label for="admission_session">Admission session</label>
                <input id="admission_session" type="text" name="admission_session" value="{{ old('admission_session', $student->admission_session) }}" maxlength="20" placeholder="2026/2027">
            </div>
            <div class="f">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    @foreach (\App\Models\Student::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $student->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="hint">Only active students can log in.</div>
            </div>
        </div>
    </div>

    <div class="panel">
        <h2 style="margin-top:0">Personal details</h2>
        <div class="cols">
            <div class="f"><label for="phone">Phone</label><input id="phone" type="tel" name="phone" value="{{ old('phone', $student->phone) }}" maxlength="30"></div>
            <div class="f">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="">--</option>
                    <option value="male" @selected(old('gender', $student->gender) === 'male')>Male</option>
                    <option value="female" @selected(old('gender', $student->gender) === 'female')>Female</option>
                </select>
            </div>
            <div class="f"><label for="date_of_birth">Date of birth</label><input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth?->toDateString()) }}"></div>
            <div class="f"><label for="state_of_origin">State of origin</label><input id="state_of_origin" type="text" name="state_of_origin" value="{{ old('state_of_origin', $student->state_of_origin) }}" maxlength="60"></div>
        </div>
        <div class="f"><label for="address">Home address</label><input id="address" type="text" name="address" value="{{ old('address', $student->address) }}" maxlength="300"></div>
        <div class="cols">
            <div class="f"><label for="guardian_name">Parent / guardian name</label><input id="guardian_name" type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" maxlength="120"></div>
            <div class="f"><label for="guardian_phone">Parent / guardian phone</label><input id="guardian_phone" type="tel" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone) }}" maxlength="30"></div>
        </div>
    </div>

    <button class="btn" type="submit">Save student</button>
</form>
@endsection
