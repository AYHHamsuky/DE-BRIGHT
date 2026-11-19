@extends('admin.layout')

@section('title', 'My account')

@section('content')
<h1>My account</h1>
<p class="sub">{{ auth()->user()->name }} &middot; {{ auth()->user()->email }} &middot; {{ auth()->user()->role->label() }}</p>

<div class="panel" style="max-width:520px">
    <h2 style="margin-top:0">Change password</h2>
    <form method="POST" action="{{ route('admin.account.update') }}">
        @csrf
        @method('PUT')
        <div class="f"><label for="current_password">Current password</label><input id="current_password" type="password" name="current_password" required autocomplete="current-password"></div>
        <div class="f"><label for="password">New password</label><input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"></div>
        <div class="f"><label for="password_confirmation">Repeat new password</label><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"></div>
        <button class="btn" type="submit">Change password</button>
    </form>
</div>
@endsection
