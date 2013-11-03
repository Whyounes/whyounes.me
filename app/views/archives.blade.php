@extends('_layout')

@section('content')
<ul>
	@foreach($posts as $post)
	<li>{{ $post->title }}</li>
	@endforeach
</ul>
@stop