@extends('admin.layout')

@use('App\Support\Money')
@use('App\Enums\PaymentStatus')
@use('App\Enums\PaymentMethod')

@section('title', 'Payments')

@section('content')
<div class="row-actions" style="justify-content:space-between">
    <div>
        <h1>Payments</h1>
        <p class="sub">Online payments appear here automatically. Record cash, transfer and POS payments by hand.</p>
    </div>
    <a class="btn" href="{{ route('admin.payments.create') }}">+ Record payment</a>
</div>

<form class="filters" method="GET">
    <div class="f"><label for="q">Search</label><input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Reference, name, reg. number"></div>
    <div class="f">
        <label for="fee">Fee</label>
        <select id="fee" name="fee">
            <option value="">All fees</option>
            @foreach ($fees as $fee)
                <option value="{{ $fee->id }}" @selected(request('fee') == $fee->id)>{{ $fee->label() }}</option>
            @endforeach
        </select>
    </div>
    <div class="f">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Any</option>
            @foreach (PaymentStatus::cases() as $status)
                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>
    <div class="f">
        <label for="method">Method</label>
        <select id="method" name="method">
            <option value="">Any</option>
            @foreach (PaymentMethod::cases() as $method)
                <option value="{{ $method->value }}" @selected(request('method') === $method->value)>{{ $method->label() }}</option>
            @endforeach
        </select>
    </div>
    <div class="f"><label for="from">From</label><input id="from" type="date" name="from" value="{{ request('from') }}"></div>
    <div class="f"><label for="to">To</label><input id="to" type="date" name="to" value="{{ request('to') }}"></div>
    <button class="btn" type="submit">Filter</button>
    @if (request()->hasAny(['q', 'fee', 'status', 'method', 'from', 'to']))<a class="btn alt" href="{{ route('admin.payments.index') }}">Clear</a>@endif
</form>

<p class="total-line">Total paid (matching filters): {{ Money::naira($total) }}</p>

<div class="panel" style="padding:0; overflow-x:auto">
    @include('admin.payments.table', ['payments' => $payments])
</div>
@include('admin.partials.pager', ['paginator' => $payments])
@endsection
