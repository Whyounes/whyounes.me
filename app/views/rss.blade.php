{{ '<?xml version="1.0"?>' }}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
        <channel>
                <title>adamwathan.me</title>
                <link>{{ URL::to('/') }}</link>
                <atom:link href="{{ URL::route('rss') }}" rel="self" type="application/rss+xml" />
                <description>Software design, Laravel, PHP, whatever.</description>
                <ttl>30</ttl>

                @foreach ($posts as $post)
                        <item>
                                <title>{{ $post->title }}</title>
                                <description>
                                        {{ htmlspecialchars($post->render()) }}
                                </description>
                                <link>{{ url_to_post($post) }}</link>
                                <guid isPermaLink="true">{{ url_to_post($post) }}</guid>
                                <pubDate>{{ $post->rssDate() }}</pubDate>
                        </item>
                @endforeach
        </channel>
</rss>