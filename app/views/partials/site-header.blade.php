<header class="siteHeader">
    <div class="u-textCenter">
        <a href="/"><span class="avatar avatar--bordered"></span></a>
    </div>
    <h1 class="siteHeader-title">{{ link_to_route('home', 'Adam Wathan') }}</h1>
    <div class="socialLinks u-textCenter">
        <a class="link--greyMedium" href="https://twitter.com/adamwathan"><i class="fa fa-lg fa-twitter"></i></a>
        <a class="link--greyMedium u-padLeft" href="https://twitter.com/github"><i class="fa fa-lg fa-github"></i></a>
    </div>
</header>
<nav class="siteNav">
    <ul class="horizontalNav">
        <li class="horizontalNav-item">
            {{ link_to_route('home', 'Blog', [], ['class' => 'siteNav-link']) }}
        </li>
        <li class="horizontalNav-item">
            {{ link_to_route('talks', 'Talks', [], ['class' => 'siteNav-link']) }}
        </li>
        <li class="horizontalNav-item">
            {{ link_to('http://fullstackradio.com', 'Podcast', ['class' => 'siteNav-link']) }}
        </li>
        <li class="horizontalNav-item">
            {{ link_to_route('about', 'About', [], ['class' => 'siteNav-link']) }}
        </li>
    </ul>
</nav>
