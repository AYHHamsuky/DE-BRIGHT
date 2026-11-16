@extends('admin.layout')

@section('title', 'Enquiry from '.$enquiry->name)

@section('content')
<h1>Enquiry from {{ $enquiry->name }}</h1>
<p class="sub"><a href="{{ route('admin.enquiries.index') }}">&larr; Back to enquiries</a></p>

<div class="panel">
    <dl class="detail" style="margin:0">
        <dt>Phone</dt>
        <dd>{{ $enquiry->phone }}</dd>
        @if ($enquiry->email)
            <dt>Email</dt>
            <dd>{{ $enquiry->email }}</dd>
        @endif
        <dt>Programme of interest</dt>
        <dd>{{ $enquiry->course?->name ?? 'Not specified' }}</dd>
        <dt>Received</dt>
        <dd>{{ $enquiry->created_at->format('l, j F Y, g:i a') }}</dd>
        <dt>Message</dt>
        <dd>{{ $enquiry->message }}</dd>
    </dl>

    <div class="row-actions" style="margin-top:20px">
        <a class="btn" href="tel:{{ preg_replace('/\s+/', '', $enquiry->phone) }}">Call</a>
        <a class="btn" style="background:#25d366" href="https://wa.me/{{ $enquiry->whatsappNumber() }}" target="_blank" rel="noopener">Reply on WhatsApp</a>
        @if ($enquiry->email)
            <a class="btn alt" href="mailto:{{ $enquiry->email }}">Email</a>
        @endif
        <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" onsubmit="return confirm('Delete this enquiry?')">
            @csrf
            @method('DELETE')
            <button class="btn danger" type="submit">Delete</button>
        </form>
    </div>
</div>
@endsection
