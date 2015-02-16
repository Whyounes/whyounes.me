<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="alternate" type="application/rss+xml" title="adamwathan.me RSS Feed" href="/rss">
    <link rel="stylesheet" href="//brick.a.ssl.fastly.net/Open+Sans:300,400,700/Linux+Libertine:400,400i,700,700i">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ elixir('css/application.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/styles/github.min.css">
    <title>{{ $title }}</title>
</head>
<body>
    @yield('body')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script>
        $(function() {
            var preElement = $('.js-blog-post-body pre').each(function (index) {
                var lineNumbers = '<div class="line-numbers">';
                var numberOfLines = $(this).find('code').html().split(/\n/).length - 1;

                for (var i = 1; i <= numberOfLines; i++) {
                    lineNumbers = lineNumbers + i.toString() + "\n";
                }

                lineNumbers = lineNumbers + '</div>';

                $(this).append(lineNumbers);
            });
        });
    </script>
    @yield('scripts')
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-46803874-1', 'adamwathan.me');
        ga('send', 'pageview');

    </script>
</body>
</html>
