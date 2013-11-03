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
            The lysine contingency - it's intended to prevent the spread of the animals in case they ever got off the island.
        </aside>
        <section>
            @yield('content')
        </section>
        <footer>
            <p>&copy; Adam Wathan. Proudly built with <a href="http://laravel.com/">Laravel</a>.</p>
        </footer>
    </div>
    {{ HTML::script('/js/prism.js') }}
</body>
</html>
