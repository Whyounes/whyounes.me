<?php

if ( ! function_exists('link_to_post'))
{
	function link_to_post($post, $title = null, $jumpTo = null)
	{
		$year = date('Y', strtotime($post->date));
		$month = date('m', strtotime($post->date));
		$day = date('d', strtotime($post->date));
		$slug = $post->slug;

		if (is_null($title)) {
			$title = $post->title;
		}

		$link = app('url')->route('post', [$year, $month, $day, $slug]);

		if (! is_null($jumpTo)) {
			$link .= '#'.$jumpTo;
		}

		return app('html')->link($link, $title);
	}
}