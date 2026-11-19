@extends('portal.layout')

@section('title', 'Change password | Student Portal')
@section('heading', 'Change password')

@section('portal')
<div class="form-card" style="max-width:480px">
    <form method="POST" action="{{ route('portal.password.update') }}">
        @csrf
        @method('PUT')
        <div class="field"><label for="current_password">Current password</label><input id="current_password" type="password" name="current_password" required autocomplete="current-password"></div>
        <div class="field"><label for="password">New password (at least 8 characters)</label><input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"></div>
        <div class="field"><label for="password_confirmation">Repeat new password</label><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"></div>
        <button class="btn btn-primary" type="submit">Change password</button>
    </form>
</div>
@endsection
