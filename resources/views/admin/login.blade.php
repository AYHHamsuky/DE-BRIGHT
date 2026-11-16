<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin login | {{ config('school.short_name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="login">
    <div class="panel">
        <h1>{{ config('school.short_name') }}</h1>
        <p class="sub" style="text-align:center">Admin login</p>

        <form method="POST" action="{{ route('admin.login.attempt') }}">
            @csrf
            <div class="f">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                @error('email') <div class="err">{{ $message }}</div> @enderror
            </div>
            <div class="f">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>
            <div class="f">
                <label class="check"><input type="checkbox" name="remember" value="1"> Keep me logged in</label>
            </div>
            <button class="btn" type="submit" style="width:100%">Log in</button>
        </form>
    </div>
    <p style="text-align:center"><a href="{{ route('home') }}">&larr; Back to website</a></p>
</div>
</body>
</html>
