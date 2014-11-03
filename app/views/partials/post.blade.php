<article class="blogPost">
	<div class="published"><span class="date">{{ (new DateTime($post->date))->format('F j, Y') }}</span></div>
	<header>
		<h1>{{ link_to_post($post) }}</h1>
		<div class="byline">by {{ $post->author }}</div>
	</header>
	<div class="blogPost-body">
		{{ $post }}
	</div>
</article>
