@extends('admin.layout')

@use('App\Enums\Role')

@section('title', $user->exists ? 'Edit staff' : 'Add staff')

@section('content')
<h1>{{ $user->exists ? 'Edit staff account' : 'Add staff account' }}</h1>
<p class="sub"><a href="{{ route('admin.users.index') }}">&larr; Back to staff</a></p>

<div class="panel">
    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
        @csrf
        @if ($user->exists) @method('PUT') @endif

        <div class="cols">
            <div class="f">
                <label for="name">Full name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="150">
            </div>
            <div class="f">
                <label for="email">Email (used to log in)</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="150">
            </div>
            <div class="f">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    @foreach ([Role::Admin, Role::SuperAdmin] as $role)
                        <option value="{{ $role->value }}" @selected(old('role', $user->role?->value) === $role->value)>{{ $role->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="f">
                <label for="password">{{ $user->exists ? 'New password (leave blank to keep)' : 'Password' }}</label>
                <input id="password" type="text" name="password" minlength="8" autocomplete="new-password" @required(! $user->exists)>
            </div>
        </div>
        <div class="f">
            <label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))> Account is active (can log in)</label>
        </div>
        <button class="btn" type="submit">Save</button>
    </form>
</div>
@endsection
