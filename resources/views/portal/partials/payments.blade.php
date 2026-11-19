@use('App\Support\Money')
<div class="table-wrap">
    <table class="data">
        <thead><tr><th>Date</th><th>Reference</th><th>Fee</th><th>Amount</th><th>Method</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ ($payment->paid_at ?? $payment->created_at)->format('j M Y') }}</td>
                    <td>{{ $payment->reference }}</td>
                    <td>{{ $payment->fee?->label() ?? '-' }}</td>
                    <td>{{ Money::naira($payment->amount) }}</td>
                    <td>{{ $payment->method->label() }}</td>
                    <td>{{ $payment->status->label() }}</td>
                    <td>
                        @if ($payment->isSuccessful())
                            <a href="{{ route('portal.payments.receipt', $payment) }}" target="_blank">Receipt</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
