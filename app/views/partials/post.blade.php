<article>
	<header>
		<div class="published"><span class="date">August 23, 2013</span></div>
		<h1>{{ link_to_post($post) }}</h1>
		<div class="byline">by {{ $post->author }}</div>
	</header>
	<div class="content">
		{{ $post }}
	</div>
</article>