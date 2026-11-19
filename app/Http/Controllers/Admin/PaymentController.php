<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use App\Services\Paystack;
use App\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student.user', 'fee'])
            ->when($request->query('q'), function ($query, $term) {
                $query->where(fn ($inner) => $inner->where('reference', 'like', "%{$term}%")
                    ->orWhereHas('student', fn ($student) => $student->search($term)));
            })
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($request->query('fee'), fn ($query, $fee) => $query->where('fee_id', $fee))
            ->when($request->query('method'), fn ($query, $method) => $query->where('method', $method))
            ->when($request->query('from'), fn ($query, $from) => $query->whereDate('created_at', '>=', $from))
            ->when($request->query('to'), fn ($query, $to) => $query->whereDate('created_at', '<=', $to));

        return view('admin.payments.index', [
            'total' => (int) (clone $query)->where('status', PaymentStatus::Successful)->sum('amount'),
            'payments' => $query->latest()->paginate(30)->withQueryString(),
            'fees' => Fee::orderByDesc('session')->orderBy('name')->get(),
        ]);
    }

    public function create(Request $request)
    {
        $student = $request->query('student') ? Student::with('user')->find($request->query('student')) : null;

        return view('admin.payments.create', [
            'student' => $student,
            'statement' => $student?->feeStatement(),
            'students' => $student ? collect() : Student::with('user')->orderBy('reg_number')->get(),
            'fees' => Fee::where('is_active', true)->orderByDesc('session')->orderBy('name')->get(),
        ]);
    }

    /**
     * Record a payment made at the bursary (cash, transfer or POS).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'fee_id' => ['required', 'exists:fees,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'method' => ['required', Rule::in(array_map(fn (PaymentMethod $method) => $method->value, PaymentMethod::manual()))],
            'paid_at' => ['required', 'date', 'before_or_equal:now'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $student = Student::findOrFail($data['student_id']);
        $fee = Fee::findOrFail($data['fee_id']);
        $amount = Money::toKobo($data['amount']);

        if (! $student->feeApplies($fee)) {
            throw ValidationException::withMessages(['fee_id' => 'This fee does not apply to the student\'s course.']);
        }

        if ($amount > $student->balanceFor($fee)) {
            throw ValidationException::withMessages(['amount' => 'The amount is more than what the student still owes ('.Money::naira($student->balanceFor($fee)).').']);
        }

        $payment = $student->payments()->create([
            'fee_id' => $fee->id,
            'reference' => Payment::newReference('MAN'),
            'amount' => $amount,
            'method' => $data['method'],
            'status' => PaymentStatus::Successful,
            'paid_at' => $data['paid_at'],
            'recorded_by' => $request->user()->id,
            'note' => $data['note'] ?? null,
        ]);

        return redirect()->route('admin.payments.show', $payment)->with('success', 'Payment recorded.');
    }

    public function show(Payment $payment)
    {
        return view('admin.payments.show', ['payment' => $payment->load('student.user', 'fee', 'recorder')]);
    }

    public function receipt(Payment $payment)
    {
        abort_unless($payment->isSuccessful(), 404);

        return view('receipt', ['payment' => $payment->load('student.user', 'student.course', 'fee'), 'backUrl' => route('admin.payments.show', $payment)]);
    }

    /**
     * Re-check a pending online payment with Paystack.
     */
    public function verify(Payment $payment, Paystack $paystack)
    {
        if ($payment->method !== PaymentMethod::Paystack || $payment->status !== PaymentStatus::Pending) {
            return back();
        }

        try {
            $payment->applyGatewayResult($paystack->verify($payment->reference));
        } catch (RuntimeException $exception) {
            return back()->withErrors(['payment' => $exception->getMessage()]);
        }

        return back()->with('success', 'Payment status: '.$payment->status->label().'.');
    }
}
