@extends('admin.layout')

@use('App\Support\Money')

@section('title', $student->user->name)

@section('content')
<div class="row-actions" style="justify-content:space-between">
    <div>
        <h1>{{ $student->user->name }}</h1>
        <p class="sub"><a href="{{ route('admin.students.index') }}">&larr; All students</a> &middot; {{ $student->reg_number }}</p>
    </div>
    <div class="row-actions">
        <a class="btn" href="{{ route('admin.payments.create', ['student' => $student->id]) }}">+ Record payment</a>
        <a class="btn alt" href="{{ route('admin.students.edit', $student) }}">Edit</a>
        @if (auth()->user()->isSuperAdmin())
            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Delete this student and all their payment records? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button class="btn danger" type="submit">Delete</button>
            </form>
        @endif
    </div>
</div>

<div class="panel">
    <dl class="detail grid">
        <div><dt>Course</dt><dd>{{ $student->course?->name ?? '-' }}</dd></div>
        <div><dt>Level</dt><dd>{{ $student->level ?? '-' }}</dd></div>
        <div><dt>Admission session</dt><dd>{{ $student->admission_session ?? '-' }}</dd></div>
        <div><dt>Status</dt><dd>{{ \App\Models\Student::STATUSES[$student->status] ?? $student->status }}</dd></div>
        <div><dt>Phone</dt><dd>{{ $student->phone ?? '-' }}</dd></div>
        <div><dt>Email</dt><dd>{{ $student->user->email }}</dd></div>
        <div><dt>Gender</dt><dd>{{ $student->gender ? ucfirst($student->gender) : '-' }}</dd></div>
        <div><dt>Date of birth</dt><dd>{{ $student->date_of_birth?->format('j M Y') ?? '-' }}</dd></div>
        <div><dt>State of origin</dt><dd>{{ $student->state_of_origin ?? '-' }}</dd></div>
        <div><dt>Address</dt><dd>{{ $student->address ?? '-' }}</dd></div>
        <div><dt>Parent / guardian</dt><dd>{{ $student->guardian_name ?? '-' }} {{ $student->guardian_phone ? '('.$student->guardian_phone.')' : '' }}</dd></div>
    </dl>
</div>

<div class="panel" style="overflow-x:auto">
    <h2 style="margin-top:0">Fees</h2>
    @if ($statement->count())
        <table>
            <thead><tr><th>Fee</th><th>Session</th><th>Amount</th><th>Paid</th><th>Balance</th></tr></thead>
            <tbody>
                @foreach ($statement as $line)
                    <tr>
                        <td>{{ $line['fee']->name }}</td>
                        <td>{{ $line['fee']->session }}</td>
                        <td class="money">{{ Money::naira($line['fee']->amount) }}</td>
                        <td class="money">{{ Money::naira($line['paid']) }}</td>
                        <td class="money"><span class="pill {{ $line['balance'] ? 'warn' : 'ok' }}">{{ $line['balance'] ? Money::naira($line['balance']) : 'Cleared' }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No active fees apply to this student. <a href="{{ route('admin.fees.index') }}">Set up fees</a>.</p>
    @endif
</div>

<div class="panel" style="overflow-x:auto">
    <h2 style="margin-top:0">Payment history</h2>
    <p class="total-line">Total paid: {{ Money::naira($totalPaid) }}</p>
    @include('admin.payments.table', ['payments' => $payments, 'showStudent' => false])
</div>
@endsection
