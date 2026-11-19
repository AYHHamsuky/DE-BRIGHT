<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with(['user', 'course'])
            ->search($request->query('q'))
            ->when($request->query('course'), fn ($query, $course) => $query->where('course_id', $course))
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderBy('reg_number')
            ->paginate(25)
            ->withQueryString();

        return view('admin.students.index', [
            'students' => $students,
            'courses' => Course::orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.students.form', [
            'student' => new Student(['status' => 'active']),
            'courses' => Course::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $password = $data['password'] ?: Str::password(10, symbols: false);

        $student = DB::transaction(function () use ($data, $password) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'] ?: $this->placeholderEmail($data['reg_number']),
                'password' => $password,
                'role' => Role::Student,
                'is_active' => $data['status'] === 'active',
            ]);

            return $user->student()->create($this->studentFields($data));
        });

        $message = 'Student added. Login: '.$student->reg_number.($data['password'] ? '' : ' / password: '.$password.' (write this down and give it to the student)');

        return redirect()->route('admin.students.show', $student)->with('success', $message);
    }

    public function show(Student $student)
    {
        $student->load(['user', 'course']);

        return view('admin.students.show', [
            'student' => $student,
            'statement' => $student->feeStatement(),
            'payments' => $student->payments()->with('fee', 'recorder')->latest()->get(),
            'totalPaid' => (int) $student->payments()->where('status', PaymentStatus::Successful)->sum('amount'),
        ]);
    }

    public function edit(Student $student)
    {
        return view('admin.students.form', [
            'student' => $student->load('user'),
            'courses' => Course::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $data = $this->validated($request, $student);

        DB::transaction(function () use ($data, $student) {
            $userFields = [
                'name' => $data['name'],
                'email' => $data['email'] ?: $this->placeholderEmail($data['reg_number']),
                'is_active' => $data['status'] === 'active',
            ];

            if ($data['password']) {
                $userFields['password'] = $data['password'];
            }

            $student->user->update($userFields);
            $student->update($this->studentFields($data));
        });

        return redirect()->route('admin.students.show', $student)->with('success', 'Student record updated.'.($data['password'] ? ' New password set.' : ''));
    }

    public function destroy(Request $request, Student $student)
    {
        abort_unless($request->user()->isSuperAdmin(), 403, 'Only a super admin can delete student records.');

        // Deleting the login also removes the student record and its payments.
        $student->user->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Student $student = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'reg_number' => ['required', 'string', 'max:40', Rule::unique('students', 'reg_number')->ignore($student?->id)],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('users', 'email')->ignore($student?->user_id)],
            'password' => ['nullable', 'string', Password::min(8)],
            'course_id' => ['nullable', 'exists:courses,id'],
            'level' => ['nullable', 'string', 'max:40'],
            'admission_session' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:30'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'state_of_origin' => ['nullable', 'string', 'max:60'],
            'address' => ['nullable', 'string', 'max:300'],
            'guardian_name' => ['nullable', 'string', 'max:120'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::in(array_keys(Student::STATUSES))],
        ]);

        // Optional fields left off the form are absent from the validated data.
        return array_merge(['email' => null, 'password' => null], $data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function studentFields(array $data): array
    {
        return collect($data)->only([
            'reg_number', 'course_id', 'level', 'admission_session', 'phone', 'gender',
            'date_of_birth', 'state_of_origin', 'address', 'guardian_name', 'guardian_phone', 'status',
        ])->all();
    }

    /**
     * Paystack needs an email for every payment, so students without one get a stand-in.
     */
    private function placeholderEmail(string $regNumber): string
    {
        return Str::slug($regNumber).'@'.config('school.student_email_domain');
    }
}
