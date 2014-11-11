<article class="blogPost">
	<div class="blogPost-published"><span class="blogPost-published-date">{{ (new DateTime($post->date))->format('F j, Y') }}</span></div>
	<header class="blogPost-header">
		<h1 class="blogPost-header-title">{{ link_to_post($post) }}</h1>
	</header>
	<div class="blogPost-body bodyCopy">
		{{ $post }}
	</div>
</article>
