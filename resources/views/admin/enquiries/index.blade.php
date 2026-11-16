@extends('admin.layout')

@section('title', 'Enquiries')

@section('content')
<h1>Enquiries</h1>
<p class="sub">Messages sent through the Contact page. Unread ones are in bold.</p>

<div class="panel" style="padding:0; overflow-x:auto">
    <table>
        <thead><tr><th>Name</th><th>Phone</th><th>Programme</th><th>Received</th><th></th></tr></thead>
        <tbody>
            @forelse ($enquiries as $enquiry)
                <tr class="{{ $enquiry->is_read ? '' : 'unread' }}">
                    <td><a href="{{ route('admin.enquiries.show', $enquiry) }}">{{ $enquiry->name }}</a></td>
                    <td>{{ $enquiry->phone }}</td>
                    <td>{{ $enquiry->course?->name ?? '-' }}</td>
                    <td>{{ $enquiry->created_at->format('j M Y, g:i a') }}</td>
                    <td><a class="btn sm alt" href="{{ route('admin.enquiries.show', $enquiry) }}">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="5">No enquiries yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="row-actions" style="justify-content:space-between">
    <span>@if ($enquiries->previousPageUrl())<a class="btn sm alt" href="{{ $enquiries->previousPageUrl() }}">&larr; Newer</a>@endif</span>
    <span>@if ($enquiries->nextPageUrl())<a class="btn sm alt" href="{{ $enquiries->nextPageUrl() }}">Older &rarr;</a>@endif</span>
</div>
@endsection
