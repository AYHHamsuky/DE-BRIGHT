<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return view('admin.courses.index', ['courses' => Course::orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function create()
    {
        return view('admin.courses.form', ['course' => new Course(['sort_order' => (Course::max('sort_order') ?? 0) + 1, 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        Course::create($this->validated($request));

        return redirect()->route('admin.courses.index')->with('success', 'Course added.');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.form', ['course' => $course]);
    }

    public function update(Request $request, Course $course)
    {
        $course->update($this->validated($request));

        return redirect()->route('admin.courses.index')->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'code' => ['nullable', 'string', 'max:20'],
            'level' => ['required', 'string', 'max:60'],
            'duration' => ['required', 'string', 'max:40'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
