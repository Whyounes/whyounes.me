<article class="blogPost">
    <header class="blogPost-header">
        <h1 class="blogPost-title">{{ $post->title }}</h1>
        <aside class="blogPost-bySection">
            <a class="link--plain" href="/"><span class="avatar avatar--small u-alignMiddle"></span></a>
            <span class="u-alignMiddle u-padLeft">
                <a class="link--plain" href="/"><strong>{{ $post->author }}</strong></a>
                on {{ $post->prettyDate() }}
            </span>
        </aside>
    </header>
    <div class="blogPost-body bodyCopy js-blog-post-body">
        {{ $post }}
    </div>
</article>
