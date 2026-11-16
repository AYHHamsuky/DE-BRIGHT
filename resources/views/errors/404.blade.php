@extends('layouts.app')

@section('title', 'Page not found | DE BRIGHT College')

@section('content')
<section class="section">
    <div class="container" style="text-align:center">
        <h1 style="font-size:4rem; margin-bottom:.2em">404</h1>
        <p>Sorry, we could not find that page.</p>
        <a class="btn btn-primary" href="{{ route('home') }}">Back to home</a>
    </div>
</section>
@endsection
