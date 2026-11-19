@extends('layouts.app')

@section('content')
<div class="page-banner">
    <div class="container">
        <h1>@yield('heading', 'Student Portal')</h1>
        <p>{{ auth()->user()->name }} &middot; {{ auth()->user()->student?->reg_number }}</p>
    </div>
</div>

<section class="section portal">
    <div class="container">
        <nav class="portal-nav" aria-label="Student portal">
            <a href="{{ route('portal.dashboard') }}" class="{{ request()->routeIs('portal.dashboard') ? 'active' : '' }}">My fees</a>
            <a href="{{ route('portal.payments.index') }}" class="{{ request()->routeIs('portal.payments.*') ? 'active' : '' }}">Payment history</a>
            <a href="{{ route('portal.password.edit') }}" class="{{ request()->routeIs('portal.password.*') ? 'active' : '' }}">Change password</a>
            <form method="POST" action="{{ route('portal.logout') }}">
                @csrf
                <button type="submit">Log out</button>
            </form>
        </nav>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        @yield('portal')
    </div>
</section>
@endsection
