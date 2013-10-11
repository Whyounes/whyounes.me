<?php namespace Blog;

interface PostRepositoryInterface
{
	public function all();

	public function newest();

	public function bySlug($slug);
}