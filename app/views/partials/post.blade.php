<article class="blogPost">
	<div class="blogPost-published"><span class="blogPost-published-date">{{ (new DateTime($post->date))->format('F j, Y') }}</span></div>
	<h1 class="blogPost-title">{{ link_to_post($post) }}</h1>
	<div class="blogPost-body bodyCopy js-blog-post-body">
		{{ $post }}
	</div>
</article>
