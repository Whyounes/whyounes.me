@extends('_layout')

@section('content')
@include('partials.post')
{{ Disqus::comments() }}
@stop