@extends('admin.layout')

@use('App\Support\Money')
@use('App\Enums\PaymentMethod')

@section('title', 'Record payment')

@section('content')
<h1>Record payment</h1>
<p class="sub"><a href="{{ $student ? route('admin.students.show', $student) : route('admin.payments.index') }}">&larr; Back</a> &middot; For money received at the bursary. Online payments are recorded automatically.</p>

@if (! $student)
    <div class="panel">
        <form method="GET">
            <div class="f">
                <label for="student">Choose the student</label>
                <select id="student" name="student" required>
                    <option value="">-- Select --</option>
                    @foreach ($students as $option)
                        <option value="{{ $option->id }}">{{ $option->reg_number }} &middot; {{ $option->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn" type="submit">Continue</button>
        </form>
    </div>
@else
    <div class="panel">
        <p><strong>{{ $student->user->name }}</strong> &middot; {{ $student->reg_number }}</p>

        @if ($statement->where('balance', '>', 0)->isEmpty())
            <p>This student has no outstanding fees.</p>
        @else
            <form method="POST" action="{{ route('admin.payments.store') }}">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <div class="cols">
                    <div class="f">
                        <label for="fee_id">Fee</label>
                        <select id="fee_id" name="fee_id" required>
                            @foreach ($statement->where('balance', '>', 0) as $line)
                                <option value="{{ $line['fee']->id }}" data-balance="{{ Money::toNaira($line['balance']) }}" @selected(old('fee_id') == $line['fee']->id)>
                                    {{ $line['fee']->label() }} &middot; owes {{ Money::naira($line['balance']) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="f">
                        <label for="amount">Amount received (₦)</label>
                        <input id="amount" type="number" name="amount" value="{{ old('amount') }}" required min="1" step="0.01">
                    </div>
                    <div class="f">
                        <label for="method">Method</label>
                        <select id="method" name="method" required>
                            @foreach (PaymentMethod::manual() as $method)
                                <option value="{{ $method->value }}" @selected(old('method') === $method->value)>{{ $method->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="f">
                        <label for="paid_at">Date received</label>
                        <input id="paid_at" type="datetime-local" name="paid_at" value="{{ old('paid_at', now()->format('Y-m-d\TH:i')) }}" required>
                    </div>
                </div>
                <div class="f">
                    <label for="note">Note (optional)</label>
                    <input id="note" type="text" name="note" value="{{ old('note') }}" maxlength="500" placeholder="e.g. bank teller number">
                </div>
                <button class="btn" type="submit">Save payment</button>
            </form>
            <script>
                (function () {
                    var fee = document.getElementById('fee_id'), amount = document.getElementById('amount');
                    function fill() { if (!amount.value) amount.value = fee.selectedOptions[0].dataset.balance; }
                    fee.addEventListener('change', function () { amount.value = ''; fill(); });
                    fill();
                })();
            </script>
        @endif
    </div>
@endif
@endsection
