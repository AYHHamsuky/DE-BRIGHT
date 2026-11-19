@use('App\Support\Money')
@use('App\Enums\PaymentStatus')
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Reference</th>
            @if ($showStudent ?? true)<th>Student</th>@endif
            <th>Fee</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($payments as $payment)
            <tr>
                <td>{{ ($payment->paid_at ?? $payment->created_at)->format('j M Y, g:i a') }}</td>
                <td><a href="{{ route('admin.payments.show', $payment) }}">{{ $payment->reference }}</a></td>
                @if ($showStudent ?? true)
                    <td><a href="{{ route('admin.students.show', $payment->student) }}">{{ $payment->student->user->name }}</a><br><small>{{ $payment->student->reg_number }}</small></td>
                @endif
                <td>{{ $payment->fee?->label() ?? '-' }}</td>
                <td class="money">{{ Money::naira($payment->amount) }}</td>
                <td>{{ $payment->method->label() }}</td>
                <td><span class="pill {{ match ($payment->status) { PaymentStatus::Successful => 'ok', PaymentStatus::Pending => 'warn', default => 'off' } }}">{{ $payment->status->label() }}</span></td>
                <td>
                    @if ($payment->isSuccessful())
                        <a class="btn sm alt" href="{{ route('admin.payments.receipt', $payment) }}" target="_blank">Receipt</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="8">No payments yet.</td></tr>
        @endforelse
    </tbody>
</table>
