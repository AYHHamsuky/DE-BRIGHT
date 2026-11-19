@extends('admin.layout')

@section('title', 'Staff accounts')

@section('content')
<div class="row-actions" style="justify-content:space-between">
    <div>
        <h1>Staff accounts</h1>
        <p class="sub"><strong>Super admins</strong> can do everything, including managing staff and deleting records. <strong>Admins</strong> manage the website, students, fees and payments.</p>
    </div>
    <a class="btn" href="{{ route('admin.users.create') }}">+ Add staff</a>
</div>

<div class="panel" style="padding:0; overflow-x:auto">
    <table>
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}@if ($user->is(auth()->user())) <span class="pill">You</span>@endif</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->label() }}</td>
                    <td><span class="pill {{ $user->is_active ? 'ok' : 'off' }}">{{ $user->is_active ? 'Active' : 'Deactivated' }}</span></td>
                    <td>
                        <div class="row-actions">
                            <a class="btn sm alt" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            @unless ($user->is(auth()->user()))
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this staff account?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn sm danger" type="submit">Delete</button>
                                </form>
                            @endunless
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
