<?php

if ( ! function_exists('url_to_post'))
{
	function url_to_post($post)	{
		$year = date('Y', strtotime($post->date));
		$month = date('m', strtotime($post->date));
		$day = date('d', strtotime($post->date));
		$slug = $post->slug;
		$url = app('url')->route('post', [$year, $month, $day, $slug]);
		return $url;
	}
}

if ( ! function_exists('link_to_post'))
{
	function link_to_post($post, $title = null, $jumpTo = null)	{
		$link = url_to_post($post);

		if (is_null($title)) {
			$title = $post->title;
		}

		if (! is_null($jumpTo)) {
			$link .= '#'.$jumpTo;
		}

		return app('html')->link($link, $title);
	}
}

if (!function_exists('elixir')) {
    function elixir($file)
    {
        static $manifest = null;

        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path() . '/build/rev-manifest.json'), true);
        }

        if (isset($manifest[$file])) {
            return '/build/' . $manifest[$file];
        }

        throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
    }
}
