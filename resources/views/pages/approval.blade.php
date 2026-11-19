@extends('layouts.app')

@php
    $permit = config('school.permit');
    $issued = \Illuminate\Support\Carbon::parse($permit['issued_on']);
@endphp

@section('title', 'Government Approval | DE BRIGHT College of Health Science and Technology')
@section('description', 'DE BRIGHT College of Health Science and Technology, Lapai, holds a permit from the Niger State Ministry of Tertiary Education to operate as a private tertiary institution.')

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>Government Approval</h1>
        <p>Registered and permitted to operate by the Niger State Government</p>
    </div>
</div>

<section class="section">
    <div class="container two-col">
        <div class="prose">
            <span class="eyebrow">Approved institution</span>
            <h2>{{ $permit['title'] }}</h2>
            <p>The <strong>{{ $permit['authority'] }}</strong> has reviewed DE BRIGHT College of Health Science and Technology against the Minimal Standards for the Establishment of Private Tertiary Institutions.</p>
            <p>The Niger State Government has granted the college operational consent. It is registered and permitted to operate as a private tertiary institution in Niger State.</p>

            <dl class="permit-facts">
                <div><dt>Issued by</dt><dd>{{ $permit['authority'] }}</dd></div>
                <div><dt>Serial number</dt><dd>{{ $permit['serial'] }}</dd></div>
                <div><dt>Date issued</dt><dd>{{ $issued->format('j F Y') }}</dd></div>
                @foreach ($permit['signatories'] as $signatory)
                    <div><dt>{{ $signatory['role'] }}</dt><dd>{{ $signatory['name'] }}</dd></div>
                @endforeach
            </dl>

            <div class="btn-row" style="justify-content:flex-start">
                <a class="btn btn-primary" href="{{ asset($permit['pdf']) }}" target="_blank" rel="noopener">View the permit (PDF)</a>
                <a class="btn btn-outline" href="{{ route('admissions') }}">Apply now</a>
            </div>
        </div>
        <div>
            <a href="{{ asset($permit['image']) }}" target="_blank" rel="noopener">
                <img class="rounded" src="{{ asset($permit['image']) }}" alt="Niger State Ministry of Tertiary Education permit to operate as a private tertiary institution, issued to DE BRIGHT College of Health Science and Technology, Lapai">
            </a>
        </div>
    </div>
</section>
@endsection
