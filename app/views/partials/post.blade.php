<article class="blogPost">
    <header class="blogPost-header">
        <h1 class="blogPost-title">{{ $post->title }}</h1>
        <aside class="blogPost-bySection">
            <span class="avatar avatar--small u-alignMiddle"></span>
            <span class="u-alignMiddle u-padLeft">
                <strong>{{ $post->author }}</strong>
                on {{ $post->prettyDate() }}
            </span>
        </aside>
    </header>
    <div class="blogPost-body bodyCopy js-blog-post-body">
        {{ $post }}
    </div>
</article>
