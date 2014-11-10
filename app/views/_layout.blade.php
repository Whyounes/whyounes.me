<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700,900|Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    @stylesheets('application')
    {{ HTML::style('/css/prism.css') }}
    <title>{{ $title }}</title>
</head>
<body>
    <div class="container">
        <header class="siteHeader">
            <h1 class="siteHeader-title">{{ link_to_route('home', 'adamwathan.me') }}</h1>
        </header>
        <nav class="siteNav">
            <ul class="horizontalNav">
                <li class="horizontalNav-item">
                    {{ link_to_route('about', 'About', [], ['class' => 'siteNav-link']) }}
                </li>
                <li class="horizontalNav-item">
                    {{ link_to_route('archives', 'Archives', [], ['class' => 'siteNav-link']) }}
                </li>
                <li class="horizontalNav-item">
                    {{ link_to_route('talks', 'Talks', [], ['class' => 'siteNav-link']) }}
                </li>
                <li class="horizontalNav-item">
                    {{ link_to('http://fullstackradio.com', 'Podcast', ['class' => 'siteNav-link']) }}
                </li>
            </ul>
        </nav>
        <aside class="siteDescription">
            Thoughts on modern web application development.
        </aside>
        <section>
            @yield('content')
        </section>
        <footer>
            <p class="footer-content">&copy; Adam Wathan. Proudly built with <a href="http://laravel.com/">Laravel</a>.</p>
        </footer>
    </div>
    {{ HTML::script('/js/prism.js') }}
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
