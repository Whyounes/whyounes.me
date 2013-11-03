@extends('_layout')

@section('content')
<div class="archives">
	<header>
		<h1>Archives</h1>
	</header>
	<div class="recent-posts">
		<h2>Recent Posts</h2>
		<ul>
			@foreach($posts as $post)
			<li>{{ link_to_post($post) }} <span class="date">{{ date('F j, Y', strtotime($post->date)) }}</span></li>
			@endforeach
		</ul>
	</div>
</div>
@stop