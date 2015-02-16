@extends('_layout')

@section('body')
<div class="container">
    @include('partials.site-header')
    @yield('content')
    <footer>
        <p class="footer-content">&copy; Adam Wathan. Proudly built with <a href="http://laravel.com/">Laravel</a>.</p>
    </footer>
</div>
@stop
