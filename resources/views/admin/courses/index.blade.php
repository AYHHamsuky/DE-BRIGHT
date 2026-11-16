@extends('admin.layout')

@section('title', 'Courses')

@section('content')
<div class="row-actions" style="justify-content:space-between">
    <div>
        <h1>Courses</h1>
        <p class="sub">The programmes shown on the Courses, Admissions and Contact pages.</p>
    </div>
    <a class="btn" href="{{ route('admin.courses.create') }}">+ Add course</a>
</div>

<div class="panel" style="padding:0; overflow-x:auto">
    <table>
        <thead><tr><th>Order</th><th>Course</th><th>Level</th><th>Duration</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($courses as $course)
                <tr>
                    <td>{{ $course->sort_order }}</td>
                    <td>{{ $course->name }}@if ($course->code) <span class="pill">{{ $course->code }}</span>@endif</td>
                    <td>{{ $course->level }}</td>
                    <td>{{ $course->duration }}</td>
                    <td><span class="pill {{ $course->is_active ? 'ok' : 'off' }}">{{ $course->is_active ? 'Visible' : 'Hidden' }}</span></td>
                    <td>
                        <div class="row-actions">
                            <a class="btn sm alt" href="{{ route('admin.courses.edit', $course) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Delete this course?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn sm danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No courses yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
