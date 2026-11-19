@extends('portal.layout')

@use('App\Support\Money')

@section('title', 'My fees | Student Portal')
@section('heading', 'Welcome, '.strtok(auth()->user()->name, ' '))

@section('portal')
<div class="portal-cards">
    <div class="card"><small>Course</small><strong>{{ $student->course?->name ?? 'Not set' }}</strong><span>{{ $student->level }}</span></div>
    <div class="card"><small>Outstanding</small><strong class="{{ $totalDue ? 'due' : 'clear' }}">{{ Money::naira($totalDue) }}</strong></div>
    <div class="card"><small>Total paid</small><strong>{{ Money::naira($totalPaid) }}</strong></div>
</div>

<h2>My fees</h2>
@if ($statement->count())
    <div class="table-wrap">
        <table class="data">
            <thead><tr><th>Fee</th><th>Session</th><th>Amount</th><th>Paid</th><th>Balance</th><th></th></tr></thead>
            <tbody>
                @foreach ($statement as $line)
                    <tr>
                        <td>{{ $line['fee']->name }}@if ($line['fee']->description)<br><small style="color:var(--muted)">{{ $line['fee']->description }}</small>@endif</td>
                        <td>{{ $line['fee']->session }}</td>
                        <td>{{ Money::naira($line['fee']->amount) }}</td>
                        <td>{{ Money::naira($line['paid']) }}</td>
                        <td><strong>{{ $line['balance'] ? Money::naira($line['balance']) : 'Cleared ✔' }}</strong></td>
                        <td>
                            @if ($line['balance'] > 0)
                                @if ($onlinePaymentReady)
                                    <form method="POST" action="{{ route('portal.fees.pay', $line['fee']) }}" style="margin:0">
                                        @csrf
                                        <button class="btn btn-primary btn-sm" type="submit">Pay {{ Money::naira($line['balance']) }}</button>
                                    </form>
                                @else
                                    <small>Pay at the bursary</small>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($onlinePaymentReady && $totalDue)
        <p style="margin-top:12px; font-size:.9rem; color:var(--muted)">🔒 Payments are processed securely by Paystack. You can pay with card, bank transfer or USSD. Your receipt is ready as soon as the payment is confirmed.</p>
    @endif
@else
    <p class="empty">No fees have been set for you yet.</p>
@endif

@if ($recentPayments->count())
    <h2 style="margin-top:36px">Recent payments</h2>
    @include('portal.partials.payments', ['payments' => $recentPayments])
@endif
@endsection
