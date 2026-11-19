@extends('admin.layout')

@use('App\Support\Money')
@use('App\Enums\PaymentMethod')
@use('App\Enums\PaymentStatus')

@section('title', 'Payment '.$payment->reference)

@section('content')
<div class="row-actions" style="justify-content:space-between">
    <div>
        <h1>Payment {{ $payment->reference }}</h1>
        <p class="sub"><a href="{{ route('admin.payments.index') }}">&larr; All payments</a></p>
    </div>
    <div class="row-actions">
        @if ($payment->isSuccessful())
            <a class="btn" href="{{ route('admin.payments.receipt', $payment) }}" target="_blank">Print receipt</a>
        @endif
        @if ($payment->method === PaymentMethod::Paystack && $payment->status === PaymentStatus::Pending)
            <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                @csrf
                <button class="btn alt" type="submit">Check with Paystack</button>
            </form>
        @endif
    </div>
</div>

<div class="panel">
    <dl class="detail grid">
        <div><dt>Student</dt><dd><a href="{{ route('admin.students.show', $payment->student) }}">{{ $payment->student->user->name }}</a> ({{ $payment->student->reg_number }})</dd></div>
        <div><dt>Fee</dt><dd>{{ $payment->fee?->label() ?? '-' }}</dd></div>
        <div><dt>Amount</dt><dd>{{ Money::naira($payment->amount) }}</dd></div>
        <div><dt>Status</dt><dd>{{ $payment->status->label() }}</dd></div>
        <div><dt>Method</dt><dd>{{ $payment->method->label() }}</dd></div>
        <div><dt>Started</dt><dd>{{ $payment->created_at->format('j M Y, g:i a') }}</dd></div>
        <div><dt>Paid</dt><dd>{{ $payment->paid_at?->format('j M Y, g:i a') ?? '-' }}</dd></div>
        <div><dt>Recorded by</dt><dd>{{ $payment->recorder?->name ?? ($payment->method === PaymentMethod::Paystack ? 'Paystack (automatic)' : '-') }}</dd></div>
        @if ($payment->gateway_response)
            <div><dt>Paystack channel</dt><dd>{{ $payment->gateway_response['channel'] ?? '-' }}</dd></div>
            <div><dt>Paystack message</dt><dd>{{ $payment->gateway_response['gateway_response'] ?? '-' }}</dd></div>
        @endif
        @if ($payment->note)
            <div><dt>Note</dt><dd>{{ $payment->note }}</dd></div>
        @endif
    </dl>
</div>
@endsection
