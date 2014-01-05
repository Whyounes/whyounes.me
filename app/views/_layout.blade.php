<!doctype html>
<html lang="en">
<head>
    <!-- <link href='http://fonts.googleapis.com/css?family=Lora:400,400italic' rel='stylesheet' type='text/css'> -->
    @stylesheets('application')
    {{ HTML::style('/css/prism.css') }}
    <title>{{ $title }}</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>{{ link_to_route('home', 'adamwathan.me') }}</h1>
        </header>
        <nav>
            <ul>
                <li>{{ link_to_route('about', 'About') }}</li>
                <li>{{ link_to_route('archives', 'Archives') }}</li>
            </ul>
        </nav>
        <aside id="site-description">
            Laravel, software development, technology, powerlifting, whatever.
        </aside>
        <section>
            @yield('content')
        </section>
        <footer>
            <p>&copy; Adam Wathan. Proudly built with <a href="http://laravel.com/">Laravel</a>.</p>
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
