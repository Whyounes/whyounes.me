@extends('_layout')

@section('content')
@foreach($posts as $post)
@include('partials.post')
@endforeach
{{ $posts->links() }}
@stop