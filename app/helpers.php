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