@extends('_layout')

@section('content')
@include('partials.site-header')
<div class="contentSection">
    <ul class="postList">
        @foreach($posts as $post)
        <li class="postList-item">
            <aside class="date">{{ date('F j, Y', strtotime($post->date)) }}</aside>
            {{ link_to_post($post, null, ['class' => 'postList-link']) }}
        </li>
        @endforeach
    </ul>
</div>
@stop
