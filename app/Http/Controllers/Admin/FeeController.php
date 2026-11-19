<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Fee;
use App\Support\Money;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        return view('admin.fees.index', [
            'fees' => Fee::with('course')
                ->withSum(['payments as collected' => fn ($query) => $query->where('status', PaymentStatus::Successful)], 'amount')
                ->orderByDesc('session')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create()
    {
        return view('admin.fees.form', [
            'fee' => new Fee(['is_active' => true, 'session' => Fee::max('session')]),
            'courses' => Course::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Fee::create($this->validated($request));

        return redirect()->route('admin.fees.index')->with('success', 'Fee added.');
    }

    public function edit(Fee $fee)
    {
        return view('admin.fees.form', ['fee' => $fee, 'courses' => Course::orderBy('sort_order')->get()]);
    }

    public function update(Request $request, Fee $fee)
    {
        $fee->update($this->validated($request));

        return redirect()->route('admin.fees.index')->with('success', 'Fee updated.');
    }

    public function destroy(Request $request, Fee $fee)
    {
        abort_unless($request->user()->isSuperAdmin(), 403, 'Only a super admin can delete fees.');

        if ($fee->payments()->exists()) {
            return back()->withErrors(['fee' => 'This fee already has payments. Untick "Students can pay this fee" to hide it instead.']);
        }

        $fee->delete();

        return redirect()->route('admin.fees.index')->with('success', 'Fee deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'session' => ['required', 'string', 'max:20', 'regex:/^\d{4}\/\d{4}$/'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'amount' => ['required', 'numeric', 'min:1', 'max:100000000'],
            'description' => ['nullable', 'string', 'max:500'],
        ], ['session.regex' => 'Write the session like 2026/2027.']);

        $data['amount'] = Money::toKobo($data['amount']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
