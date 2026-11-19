@extends('admin.layout')

@use('App\Support\Money')

@section('title', 'Dashboard')

@section('content')
<h1>Dashboard</h1>
<p class="sub">Welcome, {{ auth()->user()->name }} ({{ auth()->user()->role->label() }}).</p>

<div class="stats">
    <a class="stat" href="{{ route('admin.payments.index', ['status' => 'success', 'from' => today()->toDateString()]) }}"><b class="money">{{ Money::naira($money['today']) }}</b><span>Paid today</span></a>
    <a class="stat" href="{{ route('admin.payments.index', ['status' => 'success', 'from' => now()->startOfMonth()->toDateString()]) }}"><b class="money">{{ Money::naira($money['month']) }}</b><span>Paid this month</span></a>
    <a class="stat" href="{{ route('admin.payments.index', ['status' => 'success']) }}"><b class="money">{{ Money::naira($money['all']) }}</b><span>Paid in total</span></a>
    <a class="stat" href="{{ route('admin.students.index', ['status' => 'active']) }}"><b>{{ $counts['students'] }}</b><span>Active students</span></a>
</div>

<div class="stats">
    <a class="stat" href="{{ route('admin.enquiries.index') }}"><b>{{ $counts['unread'] }}</b><span>Unread enquiries</span></a>
    <a class="stat" href="{{ route('admin.slides.index') }}"><b>{{ $counts['slides'] }}</b><span>Slider images</span></a>
    <a class="stat" href="{{ route('admin.gallery.index') }}"><b>{{ $counts['gallery'] }}</b><span>Gallery photos</span></a>
    <a class="stat" href="{{ route('admin.courses.index') }}"><b>{{ $counts['courses'] }}</b><span>Courses</span></a>
    <a class="stat" href="{{ route('admin.posts.index') }}"><b>{{ $counts['posts'] }}</b><span>News posts</span></a>
</div>

<div class="panel" style="overflow-x:auto">
    <h2 style="margin-top:0">Latest payments</h2>
    @include('admin.payments.table', ['payments' => $latestPayments])
</div>

<div class="panel">
    <h2 style="margin-top:0">Latest enquiries</h2>
    @if ($latest->count())
        <table>
            <thead><tr><th>Name</th><th>Phone</th><th>Programme</th><th>Received</th></tr></thead>
            <tbody>
                @foreach ($latest as $enquiry)
                    <tr class="{{ $enquiry->is_read ? '' : 'unread' }}">
                        <td><a href="{{ route('admin.enquiries.show', $enquiry) }}">{{ $enquiry->name }}</a></td>
                        <td>{{ $enquiry->phone }}</td>
                        <td>{{ $enquiry->course?->name ?? '-' }}</td>
                        <td>{{ $enquiry->created_at->format('j M Y, g:i a') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No enquiries yet.</p>
    @endif
</div>
@endsection
