@extends('_layout')

@section('content')
@foreach($posts as $post)
@include('partials.post')
<p class="comments-link">{{ link_to_post($post, 'Comments', 'comments') }}</p>
@endforeach
{{ $posts->links() }}
@stop