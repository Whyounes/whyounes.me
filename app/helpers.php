<?php

if ( ! function_exists('link_to_post'))
{
	function link_to_post($post, $title = null)
	{
		$year = date('Y', strtotime($post->date));
		$month = date('m', strtotime($post->date));
		$day = date('d', strtotime($post->date));
		$slug = $post->slug;

		if (is_null($title)) {
			$title = $post->title;
		}

		return link_to_route('post', $title, [$year, $month, $day, $slug]);
	}
}