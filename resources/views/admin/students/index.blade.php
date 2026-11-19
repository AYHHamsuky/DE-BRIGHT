@extends('admin.layout')

@section('title', 'Students')

@section('content')
<div class="row-actions" style="justify-content:space-between">
    <div>
        <h1>Students</h1>
        <p class="sub">Student records and portal logins. Students log in at <a href="{{ route('portal.login') }}" target="_blank">{{ route('portal.login') }}</a> with their registration number.</p>
    </div>
    <a class="btn" href="{{ route('admin.students.create') }}">+ Add student</a>
</div>

<form class="filters" method="GET">
    <div class="f"><label for="q">Search</label><input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Name, reg. number, phone, email"></div>
    <div class="f">
        <label for="course">Course</label>
        <select id="course" name="course">
            <option value="">All courses</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected(request('course') == $course->id)>{{ $course->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="f">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Any status</option>
            @foreach (\App\Models\Student::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn" type="submit">Filter</button>
    @if (request()->hasAny(['q', 'course', 'status']))<a class="btn alt" href="{{ route('admin.students.index') }}">Clear</a>@endif
</form>

<div class="panel" style="padding:0; overflow-x:auto">
    <table>
        <thead><tr><th>Reg. number</th><th>Name</th><th>Course</th><th>Level</th><th>Phone</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($students as $student)
                <tr>
                    <td><a href="{{ route('admin.students.show', $student) }}">{{ $student->reg_number }}</a></td>
                    <td>{{ $student->user->name }}</td>
                    <td>{{ $student->course?->name ?? '-' }}</td>
                    <td>{{ $student->level ?? '-' }}</td>
                    <td>{{ $student->phone ?? '-' }}</td>
                    <td><span class="pill {{ $student->status === 'active' ? 'ok' : 'off' }}">{{ \App\Models\Student::STATUSES[$student->status] ?? $student->status }}</span></td>
                    <td><a class="btn sm alt" href="{{ route('admin.students.show', $student) }}">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="7">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@include('admin.partials.pager', ['paginator' => $students])
@endsection
