@extends('_layout')

@section('body')
<div class="container">
    @include('partials.site-header')
    @yield('content')
</div>
@stop
