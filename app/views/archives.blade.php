@extends('_layout')

@section('content')
<div class="archives contentSection">
	<header>
		<h1>Archives</h1>
	</header>
	<div class="recent-posts">
		<ul>
			@foreach($posts as $post)
			<li>
				<aside class="date">{{ date('F j, Y', strtotime($post->date)) }}</aside>
				{{ link_to_post($post) }}
			</li>
			@endforeach
		</ul>
	</div>
</div>
@stop
