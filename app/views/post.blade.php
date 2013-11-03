@extends('_layout')

@section('content')
@include('partials.post')
<div id="comments">
	{{ Disqus::comments() }}
</div>
@stop