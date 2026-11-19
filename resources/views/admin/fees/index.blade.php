@extends('admin.layout')

@use('App\Support\Money')

@section('title', 'Fees')

@section('content')
<div class="row-actions" style="justify-content:space-between">
    <div>
        <h1>Fees</h1>
        <p class="sub">What students pay, per session. A fee with no course applies to every student.</p>
    </div>
    <a class="btn" href="{{ route('admin.fees.create') }}">+ Add fee</a>
</div>

<div class="panel" style="padding:0; overflow-x:auto">
    <table>
        <thead><tr><th>Fee</th><th>Session</th><th>For</th><th>Amount</th><th>Collected</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($fees as $fee)
                <tr>
                    <td>{{ $fee->name }}</td>
                    <td>{{ $fee->session }}</td>
                    <td>{{ $fee->course?->name ?? 'All students' }}</td>
                    <td class="money">{{ Money::naira($fee->amount) }}</td>
                    <td class="money"><a href="{{ route('admin.payments.index', ['fee' => $fee->id, 'status' => 'success']) }}">{{ Money::naira((int) $fee->collected) }}</a></td>
                    <td><span class="pill {{ $fee->is_active ? 'ok' : 'off' }}">{{ $fee->is_active ? 'Open' : 'Closed' }}</span></td>
                    <td>
                        <div class="row-actions">
                            <a class="btn sm alt" href="{{ route('admin.fees.edit', $fee) }}">Edit</a>
                            @if (auth()->user()->isSuperAdmin())
                                <form method="POST" action="{{ route('admin.fees.destroy', $fee) }}" onsubmit="return confirm('Delete this fee?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn sm danger" type="submit">Delete</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No fees yet. Add school fees, acceptance fees and so on, so students can pay online.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
