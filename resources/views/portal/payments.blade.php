@extends('portal.layout')

@section('title', 'Payment history | Student Portal')
@section('heading', 'Payment history')

@section('portal')
@if ($payments->count())
    @include('portal.partials.payments', ['payments' => $payments])
    @if ($payments->hasPages())
        <div class="pager">
            @if ($payments->previousPageUrl())<a class="btn btn-outline btn-sm" href="{{ $payments->previousPageUrl() }}">&larr; Newer</a>@else<span></span>@endif
            @if ($payments->nextPageUrl())<a class="btn btn-outline btn-sm" href="{{ $payments->nextPageUrl() }}">Older &rarr;</a>@endif
        </div>
    @endif
@else
    <p class="empty">You have not made any payments yet.</p>
@endif
@endsection
