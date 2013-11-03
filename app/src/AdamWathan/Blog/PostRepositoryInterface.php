<?php namespace AdamWathan\Blog;

interface PostRepositoryInterface
{
	public function all();
	
	public function paginate($perPage);

	public function newest();

	public function byDateAndSlug($date, $slug);
}