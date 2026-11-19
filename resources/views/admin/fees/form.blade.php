@extends('admin.layout')

@use('App\Support\Money')

@section('title', $fee->exists ? 'Edit fee' : 'Add fee')

@section('content')
<h1>{{ $fee->exists ? 'Edit fee' : 'Add fee' }}</h1>
<p class="sub"><a href="{{ route('admin.fees.index') }}">&larr; Back to fees</a></p>

<div class="panel">
    <form method="POST" action="{{ $fee->exists ? route('admin.fees.update', $fee) : route('admin.fees.store') }}">
        @csrf
        @if ($fee->exists) @method('PUT') @endif

        <div class="cols">
            <div class="f">
                <label for="name">Fee name</label>
                <input id="name" type="text" name="name" list="fee-names" value="{{ old('name', $fee->name) }}" required maxlength="150">
                <datalist id="fee-names"><option value="School fees"><option value="Acceptance fee"><option value="Registration fee"><option value="Examination fee"><option value="Laboratory fee"><option value="Hostel fee"></datalist>
            </div>
            <div class="f">
                <label for="session">Session</label>
                <input id="session" type="text" name="session" value="{{ old('session', $fee->session) }}" required maxlength="20" placeholder="2026/2027">
            </div>
            <div class="f">
                <label for="amount">Amount (₦)</label>
                <input id="amount" type="number" name="amount" value="{{ old('amount', $fee->amount ? Money::toNaira($fee->amount) : '') }}" required min="1" step="0.01">
            </div>
            <div class="f">
                <label for="course_id">Who pays</label>
                <select id="course_id" name="course_id">
                    <option value="">All students</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected(old('course_id', $fee->course_id) == $course->id)>Only {{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="f">
            <label for="description">Note for students (optional)</label>
            <input id="description" type="text" name="description" value="{{ old('description', $fee->description) }}" maxlength="500">
        </div>
        <div class="f">
            <label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $fee->is_active))> Students can pay this fee</label>
        </div>
        <button class="btn" type="submit">Save fee</button>
    </form>
</div>
@endsection
