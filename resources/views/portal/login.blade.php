@extends('layouts.app')

@section('title', 'Student Portal Login | '.config('school.short_name'))

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>Student Portal</h1>
        <p>Check your fees, pay online and print your receipts</p>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width:480px">
        <div class="form-card">
            @if ($errors->any())
                <div class="alert alert-error">@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
            @endif
            <form method="POST" action="{{ route('portal.login.attempt') }}">
                @csrf
                <div class="field">
                    <label for="login">Registration number</label>
                    <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus autocomplete="username" placeholder="e.g. DBC/2026/MLT/001">
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password">
                </div>
                <div class="field">
                    <label style="display:flex; gap:8px; align-items:center; font-weight:500"><input type="checkbox" name="remember" value="1" style="width:auto"> Keep me logged in</label>
                </div>
                <button class="btn btn-primary" type="submit" style="width:100%">Log in</button>
            </form>
            <p style="margin:18px 0 0; font-size:.9rem; color:var(--muted)">No login yet, or forgot your password? Visit the school office or call {{ config('school.phones')[0] }}.</p>
        </div>
    </div>
</section>
@endsection
